<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'order_number', 'status', 'customer_id', 'supplier_id'];
    protected $appends = ['total_price', 'total_quantity'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getTotalPriceAttribute()
    {
        return DB::table('order_details')
            ->select(DB::raw('SUM(price * quantity)'))
            ->where('order_id', $this->id)
            ->value('SUM(price * quantity)');
    }

    public function getTotalQuantityAttribute()
    {
        return DB::table('order_details')
            ->select(DB::raw('SUM(quantity)'))
            ->where('order_id', $this->id)
            ->value('SUM(quantity)');
    }
}
