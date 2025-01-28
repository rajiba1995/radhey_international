<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderMeasurement;
use App\Models\Ledger;
use App\Helpers\Helper;
use Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // public function createOrder(Request $request)
    // {
    //     // Validation rules
    //     $validatedData = $request->validate([
    //         'items.*.collection' => 'required|string',
    //         'items.*.product_id' => 'required|integer',
    //         // 'items.*.product_name' => 'required|string|max:255', // Add this validation
    //         'items.*.price' => 'required|numeric|min:1',
    //         'paid_amount' => 'required|numeric|min:1',
    //         'payment_mode' => 'required|string',
    //         'items.*.measurements.*' => 'nullable|string',
    //         // 'order_number' => 'required|numeric|unique:orders,order_number|min:1',
    //         'business_type' =>'nullable|integer',
    //         'customer_id' => 'nullable|integer',
    //         'company_name' => 'nullable|string',
    //         'employee_rank' => 'nullable',
            
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //         'phone' => 'required|string',
    //         'whatsapp_no' => 'required|string',
    //         'dob' => 'required|date',
    //         'billing_address' => 'required|string',
    //         'billing_city' => 'required|string',
    //         'billing_state' => 'required|string',
    //         'billing_country' => 'required|string',
    //         'billing_pin' => 'nullable|string',
    //         'billing_landmark' => 'nullable|string',
    //         'is_billing_shipping_same' => 'required|boolean',
    //         'shipping_address' => 'nullable|string',
    //         'shipping_city' => 'nullable|string',
    //         'shipping_state' => 'nullable|string',
    //         'shipping_country' => 'nullable|string',
    //         'shipping_pin' => 'nullable|string', 
    //         'shipping_landmark' => 'nullable|string',
    //         'email' => 'required|email',
    //     ]);
    //     if ($request->input('is_billing_shipping_same') == 0) {
    //         $request->validate([
    //             'shipping_address' => 'required|string',
    //             'shipping_landmark' => 'nullable|string|max:255',
    //             'shipping_city' => 'required|string|max:255',
    //             'shipping_state' => 'required|string|max:255',
    //             'shipping_country' => 'required|string|max:255',
    //             'shipping_pin' => 'nullable|string|max:10',
    //         ]);
    //     }

    //     try {
    //         // Calculate total amount
    //         $total_amount = array_sum(array_column($validatedData['items'], 'price'));
    //         if ($validatedData['paid_amount'] > $total_amount) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'The paid amount cannot exceed the total billing amount.'
    //             ]);
    //         }

    //         $remaining_amount = $total_amount - $validatedData['paid_amount'];

    //         // Retrieve or create the user
    //         // $user = User::firstOrCreate(
    //         //     ['id' => $validatedData['customer_id']],
    //         //     [
    //         //         'name' => $validatedData['name'],
    //         //         'email' => $validatedData['email'],
    //         //         'phone' => $validatedData['phone'],
    //         //         'dob' => $validatedData['dob'],
    //         //         'whatsapp_no' => $validatedData['whatsapp_no'],
                    
    //         //         'user_type' => 1, // Customer
    //         //     ]
    //         // );

    //           $user = User::find($validatedData['customer_id']);
    //     if (!$user) {
    //         $user = User::create([
    //             'name' => $validatedData['name'],
    //             'company_name' => $validatedData['company_name'],
    //             'employee_rank' => $validatedData['employee_rank'],
    //             'email' => $validatedData['email'],
    //             'dob' => $validatedData['dob'],
    //             'phone' => $validatedData['phone'],
    //             'whatsapp_no' => $validatedData['whatsapp_no'],
    //             'user_type' => 1, // Customer
    //         ]);
    //     }

    //     // Update or create billing address
    //     $billingAddress = $user->address()->where('address_type', 1)->first();
    //     if ($billingAddress) {
    //         $billingAddress->update([
    //             'state' => $validatedData['billing_state'],
    //             'city' => $validatedData['billing_city'],
    //             'address' => $validatedData['billing_address'],
    //             'landmark' => $validatedData['billing_landmark'],
    //             'country' => $validatedData['billing_country'],
    //             'zip_code' => $validatedData['billing_pin'],
    //         ]);
    //     } else {
    //         $user->address()->create([
    //             'address_type' => 1, // Billing address
    //             'state' => $validatedData['billing_state'],
    //             'city' => $validatedData['billing_city'],
    //             'address' => $validatedData['billing_address'],
    //             'landmark' => $validatedData['billing_landmark'],
    //             'country' => $validatedData['billing_country'],
    //             'zip_code' => $validatedData['billing_pin'],
    //         ]);
    //     }

    //     // Update or create shipping address if different from billing
    //     if (!$validatedData['is_billing_shipping_same']) {
    //         $shippingAddress = $user->address()->where('address_type', 2)->first();
    //         if ($shippingAddress) {
    //             $shippingAddress->update([
    //                 'state' => $validatedData['shipping_state'],
    //                 'city' => $validatedData['shipping_city'],
    //                 'address' => $validatedData['shipping_address'],
    //                 'landmark' => $validatedData['shipping_landmark'],
    //                 'country' => $validatedData['shipping_country'],
    //                 'zip_code' => $validatedData['shipping_pin'],
    //             ]);
    //         } else {
    //             $user->address()->create([
    //                 'address_type' => 2, // Shipping address
    //                 'state' => $validatedData['shipping_state'],
    //                 'city' => $validatedData['shipping_city'],
    //                 'address' => $validatedData['shipping_address'],
    //                 'landmark' => $validatedData['shipping_landmark'],
    //                 'country' => $validatedData['shipping_country'],
    //                 'zip_code' => $validatedData['shipping_pin'],
    //             ]);
    //         }
    //     } else {
    //         // Update shipping address to match billing if the same
    //         $shippingAddress = $user->address()->where('address_type', 2)->first();
    //         if ($shippingAddress) {
    //             $shippingAddress->update([
    //                 'state' => $validatedData['billing_state'],
    //                 'city' => $validatedData['billing_city'],
    //                 'address' => $validatedData['billing_address'],
    //                 'landmark' => $validatedData['billing_landmark'],
    //                 'country' => $validatedData['billing_country'],
    //                 'zip_code' => $validatedData['billing_pin'],
    //             ]);
    //         } else {
    //             $user->address()->create([
    //                 'address_type' => 2, // Shipping address
    //                 'state' => $validatedData['billing_state'],
    //                 'city' => $validatedData['billing_city'],
    //                 'address' => $validatedData['billing_address'],
    //                 'landmark' => $validatedData['billing_landmark'],
    //                 'country' => $validatedData['billing_country'],
    //                 'zip_code' => $validatedData['billing_pin'],
    //             ]);
    //         }
    //     }
    //         // dd($user);
    //         $bill_book = Helper::generateInvoiceBill();
    //         // dd($bill_book['number']);
    //         $order_number = $bill_book['number'];
    //         // dd
    //         // dd($order_number);

    //         // if (!empty($order_number)) {
    //         //     $order_number = $order_number;
    //         // } else {
    //         //     $invoiceData = Helper::generateInvoiceBill();
    //         //     $order_number =  $invoiceData['number'];
    //         // }
    //         // Create the order
    //         $order = Order::create([
    //             'order_number' => $order_number,
    //             'customer_id' => $user->id,
    //             'created_by' =>auth()->id(),
    //             'business_type' => $validatedData['business_type'],
    //             'customer_name' => $validatedData['name'],
    //             'customer_email' => $validatedData['email'],
    //             'billing_address' => $validatedData['billing_address'] . ', ' . $validatedData['billing_city'] . ', ' . $validatedData['billing_state'] . ', ' . $validatedData['billing_country'] . ' - ' . $validatedData['billing_pin'],
    //             'shipping_address' => $validatedData['is_billing_shipping_same']
    //                 ? $validatedData['billing_address'] . ', ' . $validatedData['billing_city'] . ', ' . $validatedData['billing_state'] . ', ' . $validatedData['billing_country'] . ' - ' . $validatedData['billing_pin']
    //                 : $validatedData['shipping_address'] . ', ' . $validatedData['shipping_city'] . ', ' . $validatedData['shipping_state'] . ', ' . $validatedData['shipping_country'] . ' - ' . $validatedData['shipping_pin'],
    //             'total_amount' => $total_amount,
    //             'paid_amount' => $validatedData['paid_amount'],
    //             // $this->remaining_amount = $total_amount - $this->paid_amount;
    //             'remaining_amount' => $remaining_amount,
    //             'payment_mode' => $validatedData['payment_mode'],
    //             'last_payment_date' => now(),
    //         ]);

    //         // Create ledger entry
    //         $ledger = Ledger::create([
    //             'order_id' => $order->id,
    //             'user_id' => $user->id,
    //             'transaction_date' => now(),
    //             'transaction_type' => 'Debit',
    //             'payment_method' => $validatedData['payment_mode'],
    //             'paid_amount' => $validatedData['paid_amount'],
    //             // 'remaining_amount' => $remaining_amount,
    //             'remarks' => 'Initial payment for order #' . $order->order_number,
    //         ]);

    //         // Process order items
    //         foreach ($validatedData['items'] as $item) {

    //             $product =Product::find($item['product_id']);
    //             // dd($product);
    //             $orderItem = OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $item['product_id'],
    //                 'collection' => $item['collection'],
    //                 'product_name' => $product->name,
    //                 'price' => $item['price'],
    //             ]);

    //             // Process measurements
    //             if (!empty($item['measurements'])) {
    //                 foreach ($item['measurements'] as $measurement_name => $measurement_value) {
    //                     OrderMeasurement::create([
    //                         'order_item_id' => $orderItem->id,
    //                         'measurement_name' => $measurement_name,
    //                         'measurement_value' => $measurement_value,
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Return success response
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Order has been created successfully.',
    //             'user' => $user,
    //             'order_data' => $order,
    //             'ledger' => $ledger,
    //             'order_item' => $orderItem,
    //         ]);
    //     } catch (\Exception $e) {
    //         dd($e);
    //         Log::error('Error creating order: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while creating the order.',
    //         ]);
    //     }
    // }

    public function createOrder(Request $request)
    {
        // Validation rules
        $rules = [
            'items.*.collection' => 'required|string',
            'items.*.product_id' => 'required|integer',
            'items.*.price' => 'required|numeric|min:1',
            'paid_amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|string',
            'items.*.measurements.*' => 'nullable|string',
            'business_type' => 'nullable|integer',
            'customer_id' => 'nullable|integer',
            'company_name' => 'nullable|string',
            'employee_rank' => 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'whatsapp_no' => 'required|string',
            'dob' => 'required|date',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_country' => 'required|string',
            'billing_pin' => 'nullable|string',
            'billing_landmark' => 'nullable|string',
            'is_billing_shipping_same' => 'required|boolean',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_state' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'shipping_pin' => 'nullable|string',
            'shipping_landmark' => 'nullable|string',
        ];

        // If shipping address is different from billing, apply additional rules
        if ($request->input('is_billing_shipping_same') == 0) {
            $rules = array_merge($rules, [
                'shipping_address' => 'required|string',
                'shipping_landmark' => 'nullable|string|max:255',
                'shipping_city' => 'required|string|max:255',
                'shipping_state' => 'required|string|max:255',
                'shipping_country' => 'required|string|max:255',
                'shipping_pin' => 'nullable|string|max:10',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            // Calculate total amount and validate paid amount
            $total_amount = array_sum(array_column($request->items, 'price'));
            if ($request->paid_amount > $total_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The paid amount cannot exceed the total billing amount.',
                ]);
            }
            $remaining_amount = $total_amount - $request->paid_amount;

            // Retrieve or create user
            $user = User::find($request->customer_id);
            if (!$user) {
                $user = User::create([
                    'name' => $request->name,
                    'company_name' => $request->company_name,
                    'employee_rank' => $request->employee_rank,
                    'email' => $request->email,
                    'dob' => $request->dob,
                    'phone' => $request->phone,
                    'whatsapp_no' => $request->whatsapp_no,
                    'user_type' => 1, // Customer
                ]);
            }

            // Handle billing address update or create
            $user->address()->updateOrCreate(
                ['address_type' => 1], // Billing address
                [
                    'state' => $request->billing_state,
                    'city' => $request->billing_city,
                    'address' => $request->billing_address,
                    'landmark' => $request->billing_landmark,
                    'country' => $request->billing_country,
                    'zip_code' => $request->billing_pin,
                ]
            );

            // Handle shipping address update or create
            if (!$request->is_billing_shipping_same) {
                $user->address()->updateOrCreate(
                    ['address_type' => 2], // Shipping address
                    [
                        'state' => $request->shipping_state,
                        'city' => $request->shipping_city,
                        'address' => $request->shipping_address,
                        'landmark' => $request->shipping_landmark,
                        'country' => $request->shipping_country,
                        'zip_code' => $request->shipping_pin,
                    ]
                );
            } else {
                // Update shipping address to match billing if the same
                $user->address()->updateOrCreate(
                    ['address_type' => 2], // Shipping address
                    [
                        'state' => $request->billing_state,
                        'city' => $request->billing_city,
                        'address' => $request->billing_address,
                        'landmark' => $request->billing_landmark,
                        'country' => $request->billing_country,
                        'zip_code' => $request->billing_pin,
                    ]
                );
            }

            // Generate invoice number
            $bill_book = Helper::generateInvoiceBill();
            $order_number = $bill_book['number'];

            // Create order
            $order = Order::create([
                'order_number' => $order_number,
                'customer_id' => $user->id,
                'created_by' => auth()->id(),
                'business_type' => $request->business_type,
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'billing_address' => $request->billing_address . ', ' . $request->billing_city . ', ' . $request->billing_state . ', ' . $request->billing_country . ' - ' . $request->billing_pin,
                'shipping_address' => $request->is_billing_shipping_same
                    ? $request->billing_address . ', ' . $request->billing_city . ', ' . $request->billing_state . ', ' . $request->billing_country . ' - ' . $request->billing_pin
                    : $request->shipping_address . ', ' . $request->shipping_city . ', ' . $request->shipping_state . ', ' . $request->shipping_country . ' - ' . $request->shipping_pin,
                'total_amount' => $total_amount,
                'paid_amount' => $request->paid_amount,
                'remaining_amount' => $remaining_amount,
                'payment_mode' => $request->payment_mode,
                'last_payment_date' => now(),
            ]);

            // Create ledger entry
            $ledger=Ledger::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'transaction_date' => now(),
                'transaction_type' => 'Debit',
                'payment_method' => $request->payment_mode,
                'paid_amount' => $request->paid_amount,
                'remarks' => 'Initial payment for order #' . $order->order_number,
            ]);

            // Process order items and measurements
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'collection' => $item['collection'],
                    'product_name' => $product->name,
                    'price' => $item['price'],
                ]);

                if (!empty($item['measurements'])) {
                    foreach ($item['measurements'] as $measurement_name => $measurement_value) {
                        OrderMeasurement::create([
                            'order_item_id' => $orderItem->id,
                            'measurement_name' => $measurement_name,
                            'measurement_value' => $measurement_value,
                        ]);
                    }
                }
            }

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Order has been created successfully.',
                'user' => $user,
                'order_data' => $order,
                'ledger' => $ledger,
                'order_item' => $orderItem,
            ]);
        } catch (\Exception $e) {
            // dd($e);
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the order.',
            ]);
        }
    }

}
