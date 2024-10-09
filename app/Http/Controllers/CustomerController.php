<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function CustomerPage(): View {
        return view('pages.dashboard.customer-page');
    }

    public function CustomerCreate(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255|',
                'email' => 'required|email|max:255|unique:customers',
                'mobile' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            ]);
            
            $user_id = $request->header('id');
    
            Customer::create([
               'name' => $request->input('name'),
               'email' => $request->input('email'),
               'mobile' => $request->input('mobile'),
               'user_id' => $user_id,
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Customer Created Successfully'
            ]);
        }
        catch(ValidationException $e) {
            // Catch validation exception and return validation errors
            return response()->json([
                'status' => 'failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'status' => 'failed',
                'message' => 'Error creating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function CustomerList(Request $request) {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();

        return $customers;
    }

    public function CustomerUpdate(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255|',
                'mobile' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            ]);

            $user_id = $request->header('id');
            $customer_id = $request->input('id');
            Customer::where('user_id', $user_id)->where('id', $customer_id)->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer Updated Successfully'
            ]);
        }
        catch(ValidationException $e) {
            // Catch validation exception and return validation errors
            return response()->json([
                'status' => 'failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'status' => 'failed',
                'message' => 'Error updating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function CustomerDelete(Request $request) {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');
        return Customer::where('user_id', $user_id)->where('id', $customer_id)->delete();
    }

    public function CustomerById(Request $request) {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');
        
        return Customer::where('user_id', $user_id)->where('id', $customer_id)->first();
    }


}
