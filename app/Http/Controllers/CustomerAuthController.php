<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomerAuthController extends Controller
{
    /**
     * Show the login gateway view or fallback path.
     */
    public function showLogin()
    {
        // If already validated in session memory, skip form and route straight to dashboard
        if (Session::has('customer_id')) {
            return redirect()->route('customer.tickets.index');
        }
        return redirect('/')->withErrors(['email' => 'Please perform login authentication first.']);
    }

    /**
     * Authenticate database entities against customer inputs.
     */
public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $customer = DB::table('customer')
        ->where('c_email', $request->email)
        ->where('c_password', $request->password)
        ->first();

    if (!$customer) {
        return back()->withErrors(['email' => 'Invalid institutional email address or password configuration.'])
                     ->withInput();
    }

    Session::put('customer_id',   $customer->customer_id);
    Session::put('customer_name', $customer->c_fname . ' ' . $customer->c_lname);
    Session::put('customer_type', $customer->cust_type);

    return redirect()->route('customer.tickets.index')
                     ->with('success', 'Login successful. Welcome to the E-Tech Care Support Portal!');
}

    /**
     * Terminate the customer active operational session.
     */
    public function logout()
    {
        Session::forget(['customer_id', 'customer_name', 'customer_type']);
        Session::flush();

        return redirect('/')->with('success', 'Portal session closed successfully.');
    }
}