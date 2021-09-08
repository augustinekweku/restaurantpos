<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\Table;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Creditor_order;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            $user = Auth::user();
            
            
                //insert order details
            $order = Order::create([
                'table_id' => $request->table_id,
                'table_name' => $request->table_name,
                'order_total' => $request->order_total,
                'order_number' => $request->order_number,
                'invoice_number' => $request->invoice_number,
                'order_type' => $request->order_type,
                'status' => 2,
                'user_id' => $user->id
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
                Table::where('id', $request->table_id)->update([
                    'status' => 1,
                ]);
                //$updateTable->save();
                DB::commit();

    }

    public function createCreditorOrder(Request $request)
    {
            $user = Auth::user();
            
                //insert order details
            $order = Creditor_order::create([
                'company_id' => $request->company_id,
                'order_total' => $request->order_total,
                'order_number' => $request->order_number,
                'invoice_number' => $request->invoice_number,
                'status' => 2,
                'user_id' => $user->id,
                'notes' => $request->notes
            ]);
            $order->save();
            $order_details = $request->order_details;
            $orderDetails = [];
            foreach($order_details as $od => $x){
                array_push($orderDetails, ['item_id' => $x['item_id'], 'item_name' => $x['item_name'],
                'creditor_order_id' => $order->id, 'price' =>$x['price'], 'quantity'=> $x['quantity'], 'amount' => $x['amount']]);
                //echo json_encode($x['item_name']);

            }
                OrderDetail::insert($orderDetails);
                DB::commit();

    }

    public function getRequestedOrders(Request $request)
    {
         $orders = Order::where('status','=', 2)->orWhere('ready', 0)->with('orderDetails')->paginate(10);
         return response()->json($orders);

        //$getOrders = DB::table('Orders')->where('status', '=', 2)-with('orderDetails')->get();
        //return $getOrders;
    }
    public function getLatestRequestedOrder(Request $request)
    {
         $order = Order::where('status','=', 2)->orWhere('ready', 0)->with('orderDetails')->orderBy('id', 'desc')->first();
         return response()->json($order);

        //$getOrders = DB::table('Orders')->where('status', '=', 2)-with('orderDetails')->get();
        //return $getOrders;
    }


    public function getRequestedCreditorOrders(Request $request)
    {
         $orders = Creditor_order::where('status','=', 2)->with('orderDetails', 'company')->paginate(10);
         return response()->json($orders);

        //$getOrders = DB::table('Orders')->where('status', '=', 2)-with('orderDetails')->get();
        //return $getOrders;
    }

    public function creditorOrderConfirmedByCook($order_id)
    {
        return Creditor_order::where('id', $order_id)->update([
            'ready' => 1,
            'status' => 3
        ]);
    }

    public function getReadyOrders(Request $request)
    {
         $orders = Order::where(['status' => 3, 'take_away_cleared' => 0])->with('orderDetails')->paginate(10);
         return response()->json($orders);

        //$getOrders = DB::table('Orders')->where('status', '=', 2)-with('orderDetails')->get();
        //return $getOrders;
    }

    public function getCreditorReadyOrders(Request $request)
    {
         $orders = Creditor_order::where('status', 3)->with('orderDetails', 'company')->paginate(10);
         return response()->json($orders);

        //$getOrders = DB::table('Orders')->where('status', '=', 2)-with('orderDetails')->get();
        //return $getOrders;
    }


    public function orderConfirmedByCook($order_id, $order_type)
    {
        if ($order_type == 'takeaway') {
            return Order::where('id', $order_id)->update([
                'ready' => 1,
                'status' => 3,
            ]);          
        }
        if ($order_type == 'table') {
            return Order::where('id', $order_id)->update([
                'ready' => 1,
                'status' => 3
            ]);          
        }
    }

    public function clearTakeAwayOrder($order_id)
    {
        return Order::where('id', $order_id)->update([
            'ready' => 1,
            'take_away_cleared' => 1,
            'status' => 1,
        ]);
    }

    public function checkoutOrder(Request $request)
    {
        Order::where('id', $request->id)->update([
            'status' => $request->status,
            'balance' => $request->balance,
            'paid' => $request->paid,
        ]);
        return Table::where('id', $request->table_id)->update([
            'status' => 3
        ]);
    }

    public function checkoutTakeAwayOrder(Request $request)
    {
                $user = Auth::user();

                //insert order details
                DB::beginTransaction();
                try {
                $order = Order::create([

                    'order_total' => $request->total,
                    'paid' => $request->paid,
                    'balance' => $request->balance,
                    'order_number' => $request->order_number,
                    'invoice_number' => $request->invoice,
                    'order_type' => $request->order_type,
                    'order_type' => $request->order_type,
                    'order_number' => $request->order_number,
                    'status' => 1,
                    'user_id' => $user->id,
                ]);
                $order->save();
                $order_details = $request->order_details;
                $orderDetails = [];
                foreach($order_details as $od => $x){
                    array_push($orderDetails, ['item_id' => $x['item_id'], 'item_name' => $x['item_name'],
                    'order_id' => $order->id, 'price' =>$x['price'], 'quantity'=> $x['quantity'], 'amount' => $x['amount']]);
                    //echo json_encode($x['item_name']);
                    $getItemQtyLeft = DB::table('Items')->where('id', '=', $x['item_id'])->get('qty_left');
                    $old_qty = $getItemQtyLeft[0]->qty_left;
                    $new_qty_left = $old_qty - $x['quantity'];
                    Item::where('id', $x['item_id'])->update([
                        'qty_left' => $new_qty_left
                    ]);
                }
                OrderDetail::insert($orderDetails);
                    //$updateTable->save();
                    DB::commit();

                } catch  (Exception $e) {
                    DB::rollback();
                    return $e;
                }

    }

    public function checkoutCreditorOrder(Request $request)
    {
        return Creditor_order::where('id', $request->id)->update([
            'status' => $request->status,
            'balance' => $request->balance,
            'paid' => $request->paid,
            'payment_type' => $request->payment_type,
        ]);
    }






}
