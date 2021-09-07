<?php

namespace App\Http\Controllers;

use App\Models\Creditor_order;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function getClearedOrders()
    {
        $order = Order::where('status','=', 1)->with('user', 'orderDetails')->orderBy('id', 'desc')->get();
        return response()->json($order);
    }

    public function getClearedCreditorOrders()
    {
        $order = Creditor_order::where('status','=', 1)->with('user','orderDetails', 'company')->orderBy('id', 'desc')->get();
        return response()->json($order);
    }

    public function getDateRange(Request $request, $fromDate, $toDate) 
    {
        //return $fromDate;
        $getDateData = Order::whereBetween('created_at', [$fromDate, $toDate])->where('status', '=', 1)->with('user', 'orderDetails', 'company')->get();
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
}
