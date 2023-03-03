<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function index()
    {
        $productName = request()->q;
        $qry = Cart::with('product','customer')
            ->where('customer_id', auth()->guard('api_customer')->user()->id)
            ;

        $qry->when($productName, function($qry) use($productName) {
            $qry->whereHas('product', function($qry) use ($productName) {
                return $qry->where('title', 'like', '%'.$productName.'%');
            });
        });

        $data = $qry->latest()->paginate(5);

        return new CartResource(true, 'List Cart', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'qty' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $product = Product::findOrFail($request->product_id);
            $save = Cart::create([
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'price' => $product->price*$request->qty,
                'weight' => $product->weight
            ]);
            // $product->stock = $product->stock
            DB::commit();
            $data = Cart::with('product','customer')->where('id', $save->id)->first();

            return new CartResource(true, 'Cart Saved', $data);
        } catch (\Throwable $th) {
            DB::rollback();
            return new CartResource(false, 'Cart Failed', null);
            throw $th;
        }
    }

    public function show($id)
    {
        $qry = Cart::with('product','customer')->findOrFail($id);

        return new CartResource(true, 'Cart Detail', $data);
    }

    public function update(Request $request,$id)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'qty' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $product = Product::findOrFail($request->product_id);

            $up = Cart::findOrFail($id);
            $up->product_id = $request->product_id;
            $up->qty = $request->qty;
            $up->price = $product->price*$request->qty;
            $up->weight = $product->weight;
            $up->update();

            // $product->stock = $product->stock
            DB::commit();
            $data = Cart::with('product','customer')->where('id', $id)->first();

            return new CartResource(true, 'Cart Updated', $data);
        } catch (\Throwable $th) {
            DB::rollback();
            return new CartResource(false, $th, null);
            throw $th;
        }
    }

    public function destroy($id)
    {
        $del = Cart::where('id', $id)->delete();

        return new CartResource(true, 'Cart Deleted', null);
    }
}
