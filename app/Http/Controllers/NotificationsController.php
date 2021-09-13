<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function getRequestedOrdersCount($old_count)
    {
        $ordersCount = Order::where('status','=', 2)->orWhere('ready', 0)->count();
        if ($old_count !== $ordersCount) {
            return $ordersCount;            
        }
        exit();
    }

    public function getReadyOrdersCount()
    {
        $ordersCount = Order::where(['status' => 3, 'take_away_cleared' => 0])->count();
        return $ordersCount;
    }
}
