<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\LogOrder;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendOrder2Cust;
use App\Exports\OrderExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use App\Rules\ArrayAtLeastOneRequired;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $no_inv = request()->q;
        $qry = Order::where('customer_id', auth()->guard('api_customer')->user()->id);
        $qry->when($no_inv, function($qry) use($no_inv) {
            $qry->where('no_inv', 'like', '%'.$no_inv.'%');
        });

        $data = $qry->latest()->paginate(5);

        return new OrderResource(true, 'List Order', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'total_amount' => 'required|numeric',
                'details' => ['required', new ArrayAtLeastOneRequired],
                'details.product_id.*' => 'required',
                'details.qty.*' => 'required|numeric',
                'details.total_price.*' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $main = Order::create([
                'no_inv' => generateNoInv(),
                'total_amount' => $request->total_amount,
                'status' => 'process'
            ]);

            foreach ($request->details as $key => $value) {
                $product = Product::findOrFail($value['product_id']);
                $dt = OrderDetail::create([
                    'order_id' => $main->id,
                    'product_id' => $value['product_id'],
                    'qty' => $value['qty'],
                    'price' => $product->price,
                    'total_price' => $product->price*$value['qty'],
                ]);

                $log = LogOrder::create([
                    'order_id' => $main->id,
                    'order_detail_id' => $dt->id,
                    'product_id' => $value['product_id'],
                    'qty' => $value['qty'],
                    'price' => $product->price,
                    'total_price' => $product->price*$value['qty'],
                    'status' => $main->status,
                ]);

                $product->stock = $product->stock-$value['qty'];
                $product->update();
            }

            DB::commit();
            $data = Order::with('details.product', 'customer')->where('id', $main->id)->first();
            Mail::to($data->customer->email)->send(new SendOrder2Cust($data));
            return new OrderResource(true, 'Order Saved', $data);
        } catch (\Throwable $th) {
            DB::rollback();
            return new OrderResource(false, 'Cart Failed', $th->getMessage());
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Order::with('details.product')->where('id', $id)->first();

        return new OrderResource(true, 'Order Detail', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'total_amount' => 'required|numeric',
                'details' => ['required', new ArrayAtLeastOneRequired],
                'details.product_id.*' => 'required',
                'details.qty.*' => 'required|numeric',
                'details.total_price.*' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $main = Order::findOrFail($id);
            $main->total_amount = $request->total_amount;
            $main->status = $request->status;
            $main->update();

            $details = OrderDetail::where('order_id', $id)->get();
            foreach($details as $keys => $dt) {
                $pds = Product::findOrFail($dt->product_id);
                $pds->stock = $pds->stock+$dt->qty;
                $pds->update();
            }

            OrderDetail::where('order_id', $id)->delete();

            foreach ($request->details as $key => $value) {
                $product = Product::findOrFail($value['product_id']);
                $dt = OrderDetail::create([
                    'order_id' => $main->id,
                    'product_id' => $value['product_id'],
                    'qty' => $value['qty'],
                    'price' => $product->price,
                    'total_price' => $product->price*$value['qty'],
                ]);

                $log = LogOrder::create([
                    'order_id' => $main->id,
                    'order_detail_id' => $dt->id,
                    'product_id' => $value['product_id'],
                    'qty' => $value['qty'],
                    'price' => $product->price,
                    'total_price' => $product->price*$value['qty'],
                    'status' => $main->status,
                ]);

                $product->stock = $main->status == 'process' ? $product->stock-$value['qty'] : $product->stock;
                $product->update();
            }

            DB::commit();
            $data = Order::with('details.product')->where('id', $main->id)->first();

            return new OrderResource(true, 'Order Updated', $data);
        } catch (\Throwable $th) {
            DB::rollback();
            return new OrderResource(false, 'Order Failed', $th->getMessage());
            throw $th;
        }
    }

    public function approval($hash)
    {
        $hash = json_decode(base64_decode($hash));
        if( !empty($hash ) ) {
            $id = $hash->id;
            $app  = Order::find($id);
            if ($app->status == 'process') {
                $app->status = $hash->status;
                $app->update();

                $details = OrderDetail::where('order_id', $id)->get();
                foreach($details as $keys => $dt) {
                    $log = LogOrder::create([
                        'order_id' => $app->id,
                        'order_detail_id' => $dt->id,
                        'product_id' => $dt->product_id,
                        'qty' => $dt->qty,
                        'price' => $dt->price,
                        'total_price' => $dt->price*$dt->qty,
                        'status' => $app->status,
                    ]);
                }

                return view('success-approval');
            } else {
                if( $app->status == 'approved' ) {
                    $pesan = 'You Already Approved This Order';
                    return view('already-approval', compact('pesan'));
                } else {
                    $pesan = 'You Already Cancel This order';
                    return view('already-approval', compact('pesan'));
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dt = OrderDetail::where('order_id', $id)->delete();
        $del = Order::where('id', $id)->delete();

        return new OrderResource(true, 'Order Deleted', null);
    }

    public function export(Request $request)
    {
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;
        $status    = $request->status;

        $data = OrderDetail::whereHas('order',function($query) {
                $query->where('customer_id', auth()->guard('api_customer')->user()->id);
            });

        if( $startDate != '' && $endDate !='' ) {
            $data = $data->whereDate('created_at','>=',$startDate)
                ->whereDate('created_at','<=',$endDate);
        }

        if( $status ) {
            $data = $data->whereHas('order',function($query) use ($status) {
                $query->where('status', $status);
            });
        }

        $data = $data->get();

        $fileName = 'ORDER_'.date('Y-m-d').'_'.date('H:i').'.xlsx';

        return Excel::download(new OrderExport($data), $fileName);
    }
}
