<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil!',
            'data'    => $user,
            'token'   => $user->createToken('authToken')->accessToken
        ], 200);
    }

    /**
     * customer login
     *
     * @param  mixed $request
     * @return void
     */
    public function custLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cust = Customer::where('email', $request->email)->first();

        if (!$cust || !Hash::check($request->password, $cust->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil!',
            'data'    => $cust,
            'token'   => $cust->createToken('authToken')->accessToken
        ], 200);
    }

    /**
     * customer login
     *
     * @param  mixed $request
     * @return void
     */
    public function custRegister(Request $request)
    {
        //set validasi
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:customers',
            'password'  => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //create donatur
        $customer = Customer::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        //return JSON
        return response()->json([
            'success' => true,
            'message' => 'Register Berhasil!',
            'data'    => $customer,
            'token'   => $customer->createToken('authToken')->accessToken
        ], 201);
    }

    public function getCustomer()
    {
        return response()->json([
            'success' => true,
            'customer' => auth()->guard('api_customer')->user()
        ]);
    }
}
