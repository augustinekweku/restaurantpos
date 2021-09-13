<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Creditor_order;
use App\Models\Items_inventory;

class ReportsController extends Controller
{
    public function getClearedOrderItems()
    {
        $data = OrderDetail::with('item', 'order')->orderBy('id', 'desc')->get();
        $total_amount = OrderDetail::with('item', 'order')->orderBy('id', 'desc')->sum('amount');
        $total_quantity = OrderDetail::with('item', 'order')->orderBy('id', 'desc')->sum('quantity');
        return response()->json(['total_amount' => $total_amount,
        'data' => $data,
        'total_quantity' => $total_quantity,
        ]);
    }
//get the date range for the salesof  a particular item
    public function getDateRangeForItem($fromDate, $toDate, $item_id) 
    {
        
        //return $fromDate;
        
        $getDateData = OrderDetail::whereHas('order', function ($query) use($fromDate, $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        })->with('item', 'order')->where('item_id', '=', $item_id)->get();

        $total_amount = OrderDetail::whereHas('order', function ($query) use($fromDate, $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        })->with('item', 'order')->where('item_id', '=', $item_id)->sum('amount');

        $total_quantity = OrderDetail::whereHas('order', function ($query) use($fromDate, $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        })->with('item', 'order')->where('item_id', '=', $item_id)->sum('quantity');

        return response()->json(['total_amount' => $total_amount,
                                'getDateData' => $getDateData,
                                'total_quantity' => $total_quantity,
                                ]);
    
    }
    //get items for report
    public function getItemsForReport()
    {
        $data = OrderDetail::with('item', 'order')->get();
        $total_quantity = OrderDetail::with('item', 'order')->sum('quantity');
        $total_amount = OrderDetail::with('item', 'order')->sum('amount');
        
        return response()->json(['total_amount' => $total_amount,
                                'data' => $data,
                                'total_quantity' => $total_quantity,
                                ]);
    }

    public function getAllItems()
    {
        return Item::orderBy('id', 'desc')->get();
    }
//get all date ranges for the salesof  a particular item
    public function fetchItem($item_id) 
    {
        
        //return $fromDate;
        
        $data = OrderDetail::with('item', 'order')->where('item_id', '=', $item_id)->get();

        $total_amount = OrderDetail::with('item', 'order')->where('item_id', '=', $item_id)->sum('amount');

        $total_quantity = OrderDetail::with('item', 'order')->where('item_id', '=', $item_id)->sum('quantity');

        return response()->json(['total_amount' => $total_amount,
                                'data' => $data,
                                'total_quantity' => $total_quantity,
                                ]);
    
    }
    public function  getClearedOrders()
    {
        $order = Order::where('status','=', 1)->with('user', 'orderDetails')->orderBy('id', 'desc')->get();
        $total = Order::where('status', '=', 1)->sum('order_total');

        return response()->json(['reports' =>$order, 'total' => $total]);
    }

    public function getClearedCreditorOrders()
    {
        $order = Creditor_order::where('status','=', 1)->with('user','orderDetails', 'company')->orderBy('id', 'desc')->get();
        return response()->json($order);
    }

    public function getDateRange($fromDate, $toDate) 
    {
        //return $fromDate;
        $getDateData = Order::whereBetween('created_at', [$fromDate, $toDate])->where('status', '=', 1)->with('user', 'orderDetails')->get();
        $total = Order::whereBetween('created_at', [$fromDate, $toDate])->where('status', '=', 1)->sum('order_total');
        return response()->json(['total' => $total,
                                'getDateData' => $getDateData]);
    
    }

    public function getClearedCreditorDateRange(Request $request, $fromDate, $toDate) 
    {
        //return $fromDate;
        $getDateData = Creditor_order::whereBetween('created_at', [$fromDate, $toDate])->where('status', '=', 1)->with('user', 'orderDetails', 'company')->get();
        $total = Creditor_order::whereBetween('created_at', [$fromDate, $toDate])->where('status', '=', 1)->sum('order_total');
        return response()->json(['total' => $total,
                                'getDateData' => $getDateData]);
    
    }
    public function getInventoryRecords()
    {
        return Items_inventory::with('user', 'item')->orderBy('id', 'desc')->get();
    }
}
