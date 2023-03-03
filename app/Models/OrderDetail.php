<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
        'total_price',
        'deleted_at'
    ];

    /**
     * product
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * order
     *
     * @return void
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
