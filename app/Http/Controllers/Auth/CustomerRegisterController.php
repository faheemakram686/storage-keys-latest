<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Repo\Interfaces\CustomerInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerRegisterController extends Controller
{

    private $customer;

    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    public function create()
    {
        return view('ui.pages.customer.register');
    }

    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'customer_type' => 'required|in:individual,company',
                'first_name'    => 'required',
                'last_name'     => 'required',
                'company_name'  => 'required_if:customer_type,company',
                'email'         => 'required|email|unique:contacts,email',
                'password'      => 'required|confirmed|min:8',
            ]);

            DB::transaction(function () use ($validated) {
                $customer = Customer::create([
                    'customer_type' => $validated['customer_type'],
                    'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'company_name'  => $validated['customer_type'] == 'company' ? $validated['company_name'] : null,
                    'status'        => 1,
                ]);


                $contact = Contact::create([
                    'customer_id' => $customer->id,
                    'first_name'  => $validated['first_name'],
                    'last_name'   => $validated['last_name'],
                    'email'       => $validated['email'],
                    'password'    => Hash::make($validated['password']),
                    'status'      => 1,
                    'contact_type' => 'primary',
                ]);

                resolve(\App\Services\Contact\ContactRoleSyncService::class)
                    ->assignRole($contact, \App\Services\Contact\ContactRoleSyncService::OWNER);
            });

            return redirect()->back()->with('success', 'Successfully Registered Your Account');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();

        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected Error: ' . $e->getMessage());
        }

    }



}
