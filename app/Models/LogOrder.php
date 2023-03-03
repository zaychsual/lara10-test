<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid as Generator;
use Carbon\Carbon;

class LogOrder extends Model
{
    use HasFactory;

    public $incrementing = true;
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string'
    ];

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'order_id',
        'order_detail_id',
        'product_id',
        'qty',
        'price',
        'total_price',
        'status',
        'customer_id',

    ];

    /**
     * customer
     *
     * @return void
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * customer
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * customer
     *
     * @return void
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = auth()->guard('api_customer')->user();
            $model->id = Generator::uuid4()->toString();
            $model->customer_id = $user->id;
            $model->created_at = Carbon::now();
            $model->updated_at = Carbon::now();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }
}
