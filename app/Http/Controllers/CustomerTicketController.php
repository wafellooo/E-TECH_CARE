<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerTicketController extends Controller
{
    /**
     * Display Support Dashboard and seed parent parameters.
     */
    public function index(Request $request)
    {
        $customerId = session()->get('customer_id');

        $tickets = DB::table('ticket')
            ->where('customer_id', $customerId)
            ->orderBy('date_reported', 'desc')
            ->get();

        $allCustomerTickets = DB::table('ticket')->where('customer_id', $customerId)->get();

        $stats = [
            'total'    => $allCustomerTickets->count(),
            'pending'  => $allCustomerTickets->where('status', 'pending')->count(),
            'resolved' => $allCustomerTickets->where('status', 'resolved')->count(),
        ];

        $devices = DB::table('personal')
            ->join('device_model', 'personal.model_id', '=', 'device_model.model_id')
            ->where('personal.owner_id', $customerId)
            ->select('personal.model_id', 'device_model.brand', 'device_model.model_name', 'device_model.device_type')
            ->get();

        $campuses = DB::table('campus')->orderBy('campus_name', 'asc')->get();

        return view('customer.tickets.dashboard', compact('tickets', 'stats', 'devices', 'campuses'));
    }

    /**
     * AJAX CASCADING LOCATION PATHWAY 1: Fetch Colleges tied to Campus Node
     * Route: GET /customer/api/campus/{campus_id}/colleges
     */
    public function getCollegesByCampus($campusId)
    {
        $colleges = DB::table('college')
            ->where('campus_id', $campusId)
            ->orderBy('college_name', 'asc')
            ->get(['college_id', 'college_name']);

        return response()->json($colleges);
    }

    /**
     * Process multi-step ticket creation payloads.
     * Handles manual building + location creation before inserting ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'p_category'           => 'required|in:hardware,software,network,account',
            'p_desc'               => 'required|string|min:10|max:500',
            'device_entity_type'   => 'required|in:registered,unregistered',
            'model_id'             => 'required_if:device_entity_type,registered|nullable',
            'manual_device_type'   => 'required_if:device_entity_type,unregistered|nullable|string',
            'manual_model_name'    => 'required_if:device_entity_type,unregistered|nullable|string',
            'campus'               => 'required|string',
            'college'              => 'required|string',
            'manual_building_name' => 'required|string|max:80',
            'manual_floor_no'      => 'required|integer|min:1',
            'manual_room_no'       => 'required|string|max:10',
            'manual_room_name'     => 'nullable|string|max:80',
            'manual_room_type'     => 'required|in:laboratory,lecture_hall,office,server_room',
        ]);

        $customerId = session()->get('customer_id');

        // ── STEP A: Generate a new building_id and insert into building ──
        $lastBuilding = DB::table('building')
            ->orderByRaw("CAST(SUBSTRING(building_id, 4) AS UNSIGNED) DESC")
            ->first();

        $nextBuildingNum = $lastBuilding
            ? (int) substr($lastBuilding->building_id, 3) + 1
            : 1;

        $newBuildingId = 'BLD' . str_pad($nextBuildingNum, 3, '0', STR_PAD_LEFT);

        DB::table('building')->insert([
            'building_id'   => $newBuildingId,
            'building_name' => $request->manual_building_name,
            'campus_id'     => $request->campus,
        ]);

        // ── STEP B: Generate a new loc_id and insert into location ──
        $lastLocation = DB::table('location')
            ->orderByRaw("CAST(SUBSTRING(loc_id, 4) AS UNSIGNED) DESC")
            ->first();

        $nextLocNum = $lastLocation
            ? (int) substr($lastLocation->loc_id, 3) + 1
            : 1;

        $newLocId = 'LOC' . str_pad($nextLocNum, 3, '0', STR_PAD_LEFT);

        DB::table('location')->insert([
            'loc_id'      => $newLocId,
            'floor_no'    => $request->manual_floor_no,
            'room_no'     => $request->manual_room_no,
            'room_name'   => $request->manual_room_name ?? null,
            'room_type'   => $request->manual_room_type,
            'building_id' => $newBuildingId,
        ]);

        // ── STEP C: Generate incremental Ticket ID (TKT050, TKT051, etc.) ──
        $lastTicket = DB::table('ticket')
            ->orderByRaw("CAST(SUBSTRING(ticket_id, 4) AS UNSIGNED) DESC")
            ->first();

        $nextTicketNum = $lastTicket
            ? (int) substr($lastTicket->ticket_id, 3) + 1
            : 1;

        $ticketId = 'TKT' . str_pad($nextTicketNum, 3, '0', STR_PAD_LEFT);

        // ── STEP D: Insert the ticket referencing the new location ──

        DB::table('ticket')->insert([
            'ticket_id'     => $ticketId,
            'p_category'    => $request->p_category,
            'p_desc'        => $request->p_desc,
            'p_priority'    => 'low',
            'status'        => 'pending',
            'date_reported' => Carbon::now()->toDateTimeString(),
            'date_resolved' => null,
            'customer_id'   => $customerId,
            'loc_id'        => $newLocId,
            'model_id'      => ($request->device_entity_type === 'registered')
                                ? $request->model_id
                                : 'MDL001',
        ]);

        return redirect()->route('customer.tickets.index')
            ->with('success', "Ticket #{$ticketId} was successfully logged into the live engineering ledger.");
    }

    /**
     * Show individual ticket detail/edit view.
     */
    public function edit($id)
    {
        $customerId = session()->get('customer_id');

        $ticket = DB::table('ticket')
            ->where('ticket_id', $id)
            ->where('customer_id', $customerId)
            ->first();

        if (!$ticket) {
            return redirect()->route('customer.tickets.index')
                ->withErrors(['error' => 'Ticket not found or access denied.']);
        }

        $location = DB::table('location')
            ->join('building', 'location.building_id', '=', 'building.building_id')
            ->where('location.loc_id', $ticket->loc_id)
            ->select('location.*', 'building.building_name')
            ->first();

        return view('customer.tickets.edit', compact('ticket', 'location'));
    }

    /**
     * Process ticket update — customer can edit category, description, and location.
     */
    public function update(Request $request, $id)
    {
        $customerId = session()->get('customer_id');

        $request->validate([
            'p_category'           => 'required|in:hardware,software,network,infrastructure,account',
            'p_desc'               => 'required|string|min:10|max:500',
            'manual_building_name' => 'nullable|string|max:80',
            'manual_floor_no'      => 'nullable|integer|min:1',
            'manual_room_no'       => 'nullable|string|max:10',
            'manual_room_name'     => 'nullable|string|max:80',
            'manual_room_type'     => 'nullable|in:laboratory,lecture_hall,office,server_room',
        ]);

        // Update ticket details
        $updated = DB::table('ticket')
            ->where('ticket_id', $id)
            ->where('customer_id', $customerId)
            ->update([
                'p_category' => $request->p_category,
                'p_desc'     => $request->p_desc,
            ]);

        if (!$updated) {
            return redirect()->route('customer.tickets.index')
                ->withErrors(['error' => 'Ticket not found or access denied.']);
        }

        // Update location if provided
        $ticket = DB::table('ticket')->where('ticket_id', $id)->first();
        if ($ticket && $ticket->loc_id && $request->manual_building_name) {
            DB::table('location')
                ->where('loc_id', $ticket->loc_id)
                ->update([
                    'floor_no'   => $request->manual_floor_no ?? null,
                    'room_no'    => $request->manual_room_no ?? null,
                    'room_name'  => $request->manual_room_name ?? null,
                    'room_type'  => $request->manual_room_type ?? null,
                ]);

            // Also update building name if location exists
            $location = DB::table('location')->where('loc_id', $ticket->loc_id)->first();
            if ($location) {
                DB::table('building')
                    ->where('building_id', $location->building_id)
                    ->update(['building_name' => $request->manual_building_name]);
            }
        }

        return redirect()->route('customer.tickets.index')
            ->with('success', "Ticket #{$id} was successfully updated.");
    }
    public function show($id)
    {
        $customerId = session()->get('customer_id');
 
        // Fetch the ticket — enforce ownership
        $ticket = DB::table('ticket')
            ->where('ticket_id', $id)
            ->where('customer_id', $customerId)
            ->first();
 
        if (!$ticket) {
            return redirect()->route('customer.tickets.index')
                ->withErrors(['error' => 'Ticket not found or access denied.']);
        }
 
        // Location + Building
        $location = DB::table('location')
            ->join('building', 'location.building_id', '=', 'building.building_id')
            ->join('campus', 'building.campus_id', '=', 'campus.campus_id')
            ->where('location.loc_id', $ticket->loc_id)
            ->select(
                'location.*',
                'building.building_name',
                'campus.campus_name'
            )
            ->first();
 
        // Device / model info
        $device = DB::table('device_model')
            ->where('model_id', $ticket->model_id)
            ->first();
 
        // Customer name (already in session, but fetch full record for display)
        $customer = DB::table('customer')
            ->where('customer_id', $customerId)
            ->first();
 
        return view('customer.tickets.show', compact('ticket', 'location', 'device', 'customer'));
    }
 
    /**
     * Cancel a ticket — only allowed when status = 'pending'.
     * Sets status to 'closed' (soft cancel) rather than hard-deleting.
     * Route: DELETE /customer/tickets/{id}  →  customer.tickets.destroy
     */
    public function destroy($id)
    {
        $customerId = session()->get('customer_id');
 
        $ticket = DB::table('ticket')
            ->where('ticket_id', $id)
            ->where('customer_id', $customerId)
            ->first();
 
        if (!$ticket) {
            return redirect()->route('customer.tickets.index')
                ->withErrors(['error' => 'Ticket not found or access denied.']);
        }
 
        if ($ticket->status !== 'pending') {
            return redirect()->route('customer.tickets.show', $id)
                ->withErrors(['error' => 'Only tickets under support review can be cancelled.']);
        }
 
        DB::table('ticket')
            ->where('ticket_id', $id)
            ->where('customer_id', $customerId)
            ->update([
                'status'        => 'closed',
                'date_resolved' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);
 
        return redirect()->route('customer.tickets.index')
            ->with('success', "Ticket #{$id} has been cancelled successfully.");
    }
}