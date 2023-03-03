<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'user_id', 'description', 'weight', 'price', 'stock', 'discount'
    ];

    /**
     * image
     *
     * @return Attribute
     */
    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => asset('/storage/products/' . $value),
    //     );
    // }

    /**
     * reviewAvgRating
     *
     * @return Attribute
     */
    public function reviewAvgRating(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? substr($value, 0, 3) : 0,
        );
    }
}
