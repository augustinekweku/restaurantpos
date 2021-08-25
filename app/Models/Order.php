<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'table_name',
        'order_total',
        'paid',
        'balance',
        'order_number',
        'invoice_number',
        'status',
    ];
}
