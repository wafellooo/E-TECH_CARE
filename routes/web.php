<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerTicketController;

// ─────────────────────────────────────────────
// Welcome / Landing Page
// ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ─────────────────────────────────────────────
// ADMIN SIDE (Existing)
// ─────────────────────────────────────────────
Route::resource('tickets', TicketController::class);

Route::get('/lookup/student',  [TicketController::class, 'lookupStudent']);
Route::get('/lookup/faculty',  [TicketController::class, 'lookupFaculty']);
Route::get('/lookup/devices',  [TicketController::class, 'lookupDevices']);

// ─────────────────────────────────────────────
// CUSTOMER SIDE - Authentication Action Entry Points
// ─────────────────────────────────────────────
Route::get('/customer/login',    [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login',   [CustomerAuthController::class, 'login'])->name('customer.login.post');
Route::post('/customer/logout',  [CustomerAuthController::class, 'logout'])->name('customer.logout');

// ─────────────────────────────────────────────
// CUSTOMER SIDE - Ticket CRUD & Dynamic Cascading Lookups
// ─────────────────────────────────────────────
Route::prefix('customer')->name('customer.')->group(function () {
    // Core CRUD views and processors
    Route::get('tickets',              [CustomerTicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/create',       [CustomerTicketController::class, 'create'])->name('tickets.create');
    Route::post('tickets',             [CustomerTicketController::class, 'store'])->name('tickets.store');
    Route::get('tickets/{id}',         [CustomerTicketController::class, 'show'])->name('tickets.show');
    Route::get('tickets/{id}/edit',    [CustomerTicketController::class, 'edit'])->name('tickets.edit');
    Route::put('tickets/{id}',         [CustomerTicketController::class, 'update'])->name('tickets.update');
    Route::delete('tickets/{id}',      [CustomerTicketController::class, 'destroy'])->name('tickets.destroy');
    
    // NEW Backend Data Lookup Channels for Step 4 Location Filters
    Route::get('api/campus/{campus_id}/colleges', [CustomerTicketController::class, 'getCollegesByCampus'])->name('api.colleges');
    Route::get('api/college/{college_id}/buildings', [CustomerTicketController::class, 'getBuildingsByCollege'])->name('api.buildings');
    Route::get('api/building/{building_id}/rooms', [CustomerTicketController::class, 'getRoomsByBuilding'])->name('api.rooms');
});