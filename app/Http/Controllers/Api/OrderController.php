<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderMeasurement;
use App\Models\Ledger;
use App\Helpers\Helper;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Validation rules
        $validatedData = $request->validate([
            'items.*.collection' => 'required|string',
            'items.*.product_id' => 'required|integer',
            'items.*.price' => 'required|numeric|min:1',
            'paid_amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|string',
            'items.*.measurements.*' => 'nullable|string',
            'order_number' => 'required|numeric|unique:orders,order_number|min:1',
            'customer_id' => 'nullable|integer',
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
            'is_billing_shipping_same' => 'required|boolean',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_state' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'shipping_pin' => 'nullable|string',
        ]);
        if ($request->input('is_billing_shipping_same') == 0) {
            $request->validate([
                'shipping_address' => 'required|string',
                'shipping_landmark' => 'nullable|string|max:255',
                'shipping_city' => 'required|string|max:255',
                'shipping_state' => 'required|string|max:255',
                'shipping_country' => 'required|string|max:255',
                'shipping_pin' => 'nullable|string|max:10',
            ]);
        }

        try {
            // Calculate total amount
            $total_amount = array_sum(array_column($validatedData['items'], 'price'));
            if ($validatedData['paid_amount'] > $total_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The paid amount cannot exceed the total billing amount.'
                ]);
            }

            $remaining_amount = $total_amount - $validatedData['paid_amount'];

            // Retrieve or create the user
            $user = User::firstOrCreate(
                ['id' => $validatedData['customer_id']],
                [
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'],
                    'user_type' => 1, // Customer
                ]
            );
            if (!empty($order_number)) {
                $order_number = $order_number;
            } else {
                $invoiceData = Helper::generateInvoiceBill();
                $order_number =  $invoiceData['number'];
            }
            // Create the order
            $order = Order::create([
                'order_number' => $validatedData['order_number'],
                'customer_id' => $user->id,
                'customer_name' => $validatedData['name'],
                'customer_email' => $validatedData['email'],
                'billing_address' => $validatedData['billing_address'] . ', ' . $validatedData['billing_city'] . ', ' . $validatedData['billing_state'] . ', ' . $validatedData['billing_country'] . ' - ' . $validatedData['billing_pin'],
                'shipping_address' => $validatedData['is_billing_shipping_same']
                    ? $validatedData['billing_address'] . ', ' . $validatedData['billing_city'] . ', ' . $validatedData['billing_state'] . ', ' . $validatedData['billing_country'] . ' - ' . $validatedData['billing_pin']
                    : $validatedData['shipping_address'] . ', ' . $validatedData['shipping_city'] . ', ' . $validatedData['shipping_state'] . ', ' . $validatedData['shipping_country'] . ' - ' . $validatedData['shipping_pin'],
                'total_amount' => $total_amount,
                'paid_amount' => $validatedData['paid_amount'],
                'remaining_amount' => $remaining_amount,
                'payment_mode' => $validatedData['payment_mode'],
                'last_payment_date' => now(),
            ]);

            // Create ledger entry
            Ledger::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'transaction_date' => now(),
                'transaction_type' => 'Debit',
                'payment_method' => $validatedData['payment_mode'],
                'paid_amount' => $validatedData['paid_amount'],
                'remarks' => 'Initial payment for order #' . $order->order_number,
            ]);

            // Process order items
            foreach ($validatedData['items'] as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'collection' => $item['collection'],
                    'price' => $item['price'],
                ]);

                // Process measurements
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
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            dd($e);
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the order.',
            ]);
        }
    }
}
