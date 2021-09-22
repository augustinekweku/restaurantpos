<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Creditor_order;
use App\Models\Items_inventory;
use App\Models\Creditor_order_details;

class ReportsController extends Controller
{
    public function getClearedOrderItems()
    {
        $data = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->get();
        $total_quantity = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->sum('quantity');
        $total_amount = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->sum('amount');
        
        return response()->json(['total_amount' => $total_amount,
                                'data' => $data,
                                'total_quantity' => $total_quantity,
                                ]);
    }
    //function for adding one day
    public function addOneDay($date){
         return date('Y-m-d', strtotime("+1 day", strtotime($date)));
    }
    //get the date range for the salesof  a particular item
    public function getDateRangeForItem($fromDate, $toDate, $item_id) 
    {
        
        //return $fromDate;
        $newEndDate = date('Y-m-d', strtotime("+1 day", strtotime($toDate)));

        $getDateData = OrderDetail::whereHas('order', function ($query) use($fromDate, $newEndDate) {
            $query->whereBetween('created_at', [$fromDate, $newEndDate])->where('status', 'cleared');
        })->with('item', 'order')->where('item_id', '=', $item_id)->get();

        $total_amount = OrderDetail::whereHas('order', function ($query) use($fromDate, $newEndDate) {
            $query->whereBetween('created_at', [$fromDate, $newEndDate])->where('status', 'cleared');
        })->with('item', 'order')->where('item_id', '=', $item_id)->sum('amount');

        $total_quantity = OrderDetail::whereHas('order', function ($query) use($fromDate, $newEndDate) {
            $query->whereBetween('created_at', [$fromDate, $newEndDate])->where('status', 'cleared');
        })->with('item', 'order')->where('item_id', '=', $item_id)->sum('quantity');

        return response()->json(['total_amount' => $total_amount,
                                'getDateData' => $getDateData,
                                'total_quantity' => $total_quantity,
                                ]);
    
    }
//get the date range for the salesof  a particular creditor item
    public function getDateRangeForCreditorItem($fromDate, $toDate, $item_id) 
    {
        
        //return $fromDate;
        //making a query based on the relationship 'creditor_order'
        $getDateData = Creditor_order_details::whereHas('creditor_order', function ($query) use($fromDate, $toDate) {
            $query->whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', 1);
        })->with('item', 'creditor_order')->where('item_id', '=', $item_id)->get();

        $total_amount = Creditor_order_details::whereHas('creditor_order', function ($query) use($fromDate, $toDate) {
            $query->whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', 1);
        })->with('item', 'creditor_order')->where('item_id', '=', $item_id)->sum('amount');

        $total_quantity = Creditor_order_details::whereHas('creditor_order', function ($query) use($fromDate, $toDate) {
            $query->whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', 1);
        })->with('item', 'creditor_order')->where('item_id', '=', $item_id)->sum('quantity');

        return response()->json(['total_amount' => $total_amount,
                                'getDateData' => $getDateData,
                                'total_quantity' => $total_quantity,
                                ]);
    
    }

    //get items for report
    public function getItemsForReport()
    {
        $data = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->get();
        $total_quantity = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->sum('quantity');
        $total_amount = OrderDetail::with('item', 'order')->sum('amount');
        
        return response()->json(['total_amount' => $total_amount,
                                'data' => $data,
                                'total_quantity' => $total_quantity,
                                ]);
    }
    //get items for credit report
    public function getItemsForCreditorReport()
    {
        $data = Creditor_order_details::whereHas('creditor_order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'creditor_order')->get();

        $total_amount = Creditor_order_details::whereHas('creditor_order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'creditor_order')->sum('amount');
        
        $total_quantity = Creditor_order_details::whereHas('creditor_order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'creditor_order')->sum('quantity');
        
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
        $data = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->where('item_id', '=', $item_id)->get();

        $total_amount = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->where('item_id', '=', $item_id)->sum('amount');
        
        $total_quantity = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'order')->where('item_id', '=', $item_id)->sum('quantity');
        
        return response()->json(['total_amount' => $total_amount,
        'data' => $data,
        'total_quantity' => $total_quantity,
        ]);
        
    }
    
    //get all date ranges for the salesof  a particular credit item
    
    public function fetchCreditorItem($item_id) 
    {
        
        //return $fromDate;
        $data = Creditor_order_details::whereHas('creditor_order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'creditor_order')->where('item_id', '=', $item_id)->get();
        
        $total_amount = Creditor_order_details::whereHas('creditor_order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'creditor_order')->where('item_id', '=', $item_id)->sum('amount');
        
        $total_quantity = Creditor_order_details::whereHas('creditor_order', function ($query) {
            $query->where('status', 'cleared');
        })->with('item', 'creditor_order')->where('item_id', '=', $item_id)->sum('quantity');

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
        $order = Creditor_order::where('status','=', 1)->with('user','creditor_order_details', 'company')->orderBy('id', 'desc')->get();
        $total = Creditor_order::where('status', '=', 1)->sum('order_total');

        return response()->json(['reports' =>$order, 'total' => $total]);
    }


    public function getDateRange($fromDate, $toDate) 
    {

        $getDateData = Order::whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', '=', 1)->with('user', 'orderDetails')->get();
        $total = Order::whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', '=', 1)->sum('order_total');
        return response()->json(['total' => $total,
                                'getDateData' => $getDateData]);
    
    }

    public function getClearedCreditorDateRange(Request $request, $fromDate, $toDate) 
    {   
        $date = new DateTime($fromDate);
        //return $fromDate;

        $getDateData = Creditor_order::whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', '=', 1)->with('user', 'creditor_order_details', 'company')->get();
        $total = Creditor_order::whereBetween('created_at', [$fromDate, $this->addOneDay($toDate)])->where('status', '=', 1)->sum('order_total');
        return response()->json(['total' => $total,
                                'getDateData' => $getDateData]);
    
    }
    public function getInventoryRecords()
    {
        return Items_inventory::with('user', 'item')->orderBy('id', 'desc')->get();
    }
}
