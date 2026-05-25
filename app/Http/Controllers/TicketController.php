<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::orderBy('date_reported', 'desc')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'p_category'  => 'required',
            'p_desc'      => 'required|min:10',
            'customer_id' => 'required',
            'loc_id'      => 'nullable',
            'model_id'    => 'nullable',
        ]);

        // Auto-generate Ticket ID
        $last = Ticket::orderBy('ticket_id', 'desc')->first();

        if ($last) {
            $lastNumber = intval(substr($last->ticket_id, 3));
            $newNumber  = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $newTicketId = 'TKT' . $newNumber;

        // Create ticket
        Ticket::create([
            'ticket_id'     => $newTicketId,
            'p_category'    => $request->p_category,
            'p_desc'        => $request->p_desc,
            'p_priority'    => 'low',        // default, admin will set later
            'status'        => 'pending',    // always starts as pending
            'date_reported' => now(),
            'customer_id'   => $request->customer_id,
            'loc_id'        => $request->loc_id,
            'model_id'      => $request->model_id,
        ]);

        return redirect()->route('tickets.index')
                         ->with('success', 'Ticket ' . $newTicketId . ' submitted successfully!');
    }

    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'p_priority' => 'required',
            'status'     => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'p_priority'    => $request->p_priority,
            'status'        => $request->status,
            'date_resolved' => $request->status == 'resolved' ? now() : null,
        ]);

        return redirect()->route('tickets.index')
                         ->with('success', 'Ticket ' . $id . ' updated successfully!');
    }

    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index')
                         ->with('success', 'Ticket ' . $id . ' deleted successfully!');
    }
public function lookupStudent(Request $request)
{
    $student = \DB::table('student')
        ->join('customer', 'student.customer_id', '=', 'customer.customer_id')
        ->where('student.student_no', $request->student_no)
        ->select(
            'customer.customer_id',
            'customer.c_fname',
            'customer.c_lname',
            'customer.c_email',
            'customer.dept_id'
        )
        ->first();

    if (!$student) {
        return response()->json(['found' => false]);
    }

    return response()->json(['found' => true, 'data' => $student]);
}

public function lookupFaculty(Request $request)
{
    $faculty = \DB::table('faculty')
        ->join('customer', 'faculty.customer_id', '=', 'customer.customer_id')
        ->where('customer.customer_id', $request->customer_id)
        ->select('customer.customer_id', 'customer.c_fname', 'customer.c_lname', 'customer.c_email', 'faculty.position')
        ->first();

    if (!$faculty) {
        return response()->json(['found' => false]);
    }

    return response()->json(['found' => true, 'data' => $faculty]);
}

public function lookupDevices(Request $request)
{
    $devices = \DB::table('personal')
        ->join('device', 'personal.device_id', '=', 'device.device_id')
        ->join('device_model', 'personal.model_id', '=', 'device_model.model_id')
        ->where('personal.owner_id', $request->customer_id)
        ->select(
            'device.device_id',
            'personal.model_id',
            'device.current_condition',
            'device_model.brand',
            'device_model.model_name',
            'device_model.device_type'
        )
        ->get();

    return response()->json(['devices' => $devices]);
}
}