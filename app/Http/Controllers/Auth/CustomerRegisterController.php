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
            // Honeypot field — leave empty for real users.
            if (filled($request->input('website'))) {
                return redirect()->back()->with('success', 'Successfully Registered Your Account');
            }

            $validated = $request->validate([
                'customer_type' => 'required|in:individual,company',
                'first_name'    => 'required|string|max:100',
                'last_name'     => 'required|string|max:100',
                'company_name'  => 'required_if:customer_type,company|nullable|string|max:150',
                'email'         => 'required|email|unique:contacts,email',
                'password'      => 'required|confirmed|min:8',
            ]);

            DB::transaction(function () use ($validated) {
                $customer = Customer::create([
                    'customer_type' => $validated['customer_type'],
                    'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'company_name'  => $validated['customer_type'] == 'company' ? $validated['company_name'] : null,
                    // Web self-signup starts In-Active; admin can activate after review.
                    'status'        => 0,
                ]);


                Contact::create([
                    'customer_id' => $customer->id,
                    'first_name'  => $validated['first_name'],
                    'last_name'   => $validated['last_name'],
                    'email'       => $validated['email'],
                    'password'    => Hash::make($validated['password']),
                    'status'      => 0,
                    'contact_type' => 'primary',
                ]);
            });

            return redirect()->back()->with('success', 'Successfully Registered Your Account. Our team will activate it shortly.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();

        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected Error: ' . $e->getMessage());
        }

    }



}
