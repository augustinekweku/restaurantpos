<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function item()
    {
        return $this->hasOne(Item::class);
    }
}
