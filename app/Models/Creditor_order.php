<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditor_order extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_name',
        'order_total',
        'order_number',
        'paid',
        'balance',
        'invoice_number',
        'status',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'creditor_order_id', 'id');
    }

}
