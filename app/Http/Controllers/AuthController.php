<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * [POST]
     * /register
     */
    public function register(Request $request)
    {
        $data = $request->all();

        // return response()->json([
        //     'data' => $data
        // ]);

        $validator = Validator::make($data, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ]);

        try {
            //code...
            if ($validator->validate()) {
                $user = User::create([
                    'name' => $request->username,
                    'password' => Hash::make($request->password)
                ]);

                if ($user) {
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'data' => $user
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'success' => false,
                        'errors' => "Failed to register new user"
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'errors' => $th->getMessage()
            ]);
        }
    }
}
