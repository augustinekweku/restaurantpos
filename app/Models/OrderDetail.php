<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_name',
        'order_id',
        'price',
        'quantity',
        'amount'
    ];
}
