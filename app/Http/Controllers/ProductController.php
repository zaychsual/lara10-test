<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $name = request()->q;
        $qry = Product::when($name, function($qry) use($name) {
            $qry->where('title', 'like', '%'.$name.'%');
        });

        $data = $qry->latest()->paginate(5);

        return new ProductResource(true, 'List Product', $data);
    }

    public function show($id)
    {
        $qry = Product::findOrFail($id);

        return new ProductResource(true, 'Detail Product', $qry);
    }
}
