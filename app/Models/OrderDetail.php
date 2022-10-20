<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'price', 'quantity'];
    protected $appends = ['product_name'];

    public function getProductNameAttribute()
    {
        return Product::where('id', $this->product_id)->value('name');
    }
}
