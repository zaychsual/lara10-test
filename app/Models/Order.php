<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'status', 'no_inv', 'total_amount', 'customer_id'
    ];

    /**
     * detail
     *
     * @return void
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
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
            $model->updated_at = Carbon::now();
        });
    }
}
