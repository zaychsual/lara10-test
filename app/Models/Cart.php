<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cart extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'customer_id', 'qty', 'price', 'weight'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * customer
     *
     * @return void
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user              = auth()->guard('api_customer')->user();
            $model->customer_id = $user->id;
            $model->created_at = Carbon::now();
            $model->updated_at = Carbon::now();
        });
        static::updating(function ($model) {
            $user              = auth()->guard('api_customer')->user();
            $model->updated_at = Carbon::now();
        });
    }
}
