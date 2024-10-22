<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function InvoicePage() {
        return view('pages.dashboard.invoice-page');
    }

    public function SalePage() {
        return view('pages.dashboard.sale-page');
    }

    public function InvoiceCreate(Request $request) {
        DB::beginTransaction();

        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');

            $customer_id = $request->input('customer_id');

            $invoice = Invoice::create([
                'user_id' => $user_id,
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'customer_id' => $customer_id
            ]);

            $invoice_id = $invoice->id;

            $products = $request->input('products');

            foreach ($products as $product) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice_id,
                    'user_id' => $user_id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'sale_price' => $product['sale_price'],
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice Created Successfully'
            ]);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => 'Invoice Creation Failed'
            ]);
        }
    }

    public function InvoiceSelect(Request $request) {
        $user_id = $request->header('id');  
        return Invoice::where('user_id', $user_id)->with('customer')->get();
    }

    public function InvoiceDetails(Request $request) {
        $user_id = $request->header('id');

        $customer_id = $request->input('customer_id');
        $invoice_id = $request->input('invoice_id');

        $customerDetails = Customer::where('user_id', $user_id)->where('id', $customer_id)->first();
        $invoiceTotal = Invoice::where('user_id', $user_id)->where('id', $invoice_id)->first();

        $invoiceProducts = InvoiceProduct::where('user_id', $user_id)->where('invoice_id', $invoice_id)->with('product')->get();
        
        
        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProducts
        );
    }

    public function InvoiceDelete(Request $request) {
        DB::beginTransaction();

        try {
            $user_id = $request->header('id');
            $invoice_id = $request->input('invoice_id');

            InvoiceProduct::where('user_id', $user_id)->where('invoice_id', $invoice_id)->delete();
            Invoice::where('id', $invoice_id)->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice Deleted Successfully'
            ]);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => 'Invoice Deletion Failed'
            ]);
        }
    }


}
