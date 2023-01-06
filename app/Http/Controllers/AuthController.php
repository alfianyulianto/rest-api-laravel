<?php

namespace App\Http\Controllers;

use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //  $request->bearerToken() : untuk mengambil Bearer Token dari Authorization header

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        // $request : berisi apapun yang kita inputkan
        // return $request;

        // User::create([]) : menambahkan data user berdasarkan request ke dalam model User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->addMeta(['token' => $token])
            ->toArray();
        return response()->json(
            [
                'user' => $response,
                'message' => 'Added user successfully'
            ],
            201
        );
    }

    public function login(Request $request, User $user)
    {
        //  $request->bearerToken() : untuk mengambil Bearer Token dari Authorization header

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // return Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Your credentials is wrong'], 401);
        }

        // Auth::id() : mengambil ID user yang Authentikasi saat ini
        // find() : menemukan user berdasarkan id
        $user = $user->find(Auth::id());

        // Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->addMeta(['token' => $token])
            ->toArray();

        return response()->json([$response, 'message' => 'You authenticated user'], 202);
    }

    public function logout(Request $request)
    {
        // return $request;

        // Cabut token yang digunakan untuk mengautentikasi permintaan saat ini..
        $result = $request->user()->currentAccessToken()->delete();
        return response()->json([$result, 'message' => 'You successfully logged out'], 200);
    }
}
