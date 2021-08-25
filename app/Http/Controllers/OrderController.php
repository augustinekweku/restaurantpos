<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function createOrderDetails(Request $request)
    {
                //validate request
                // $this->validate($request, [
                //     'item_id' => 'required',
                //     'item_name' => 'required',
                //     'order_id' => 'required',
                //     'price' => 'required',
                //     'quantity' => 'required',
                //     'amount' => 'required',
                // ]);
            
                //insert order details
            $order = Order::create([
                'table_id' => $request->table_id,
                'table_name' => $request->table_name,
                'order_total' => $request->order_total,
                'order_number' => $request->order_number,
                'invoice_number' => $request->invoice_number,
                'status' => 2,
            ]);
            $order->save();
            $order_details = $request->order_details;
            $orderDetails = [];
            foreach($order_details as $od => $x){
                array_push($orderDetails, ['item_id' => $x['item_id'], 'item_name' => $x['item_name'],
                'order_id' => $order->id, 'price' =>$x['price'], 'quantity'=> $x['quantity'], 'amount' => $x['amount']]);
                //echo json_encode($x['item_name']);
            }
                OrderDetail::insert($orderDetails);

                DB::commit();

    }
}
