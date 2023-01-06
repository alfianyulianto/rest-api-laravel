<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function users(User $user)
    {
        // all() : method Eloquent ORM untuk menampilkan semua data user
        $users = $user->all();

        // return fractal()
        //     ->collection($users)
        //     ->transformWith(new UserTransformer())
        //     ->toArray();

        return fractal($users, new UserTransformer())->toArray();
    }

    public function profile(User $user)
    {
        $user = $user->find(Auth::id());

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->includePosts()
            ->toArray();
    }

    // User yang memiliki credentials bisa membuka postinan  User lain
    public function profileById(User $user, $id)
    {
        $user = $user->find($id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->includePosts()
            ->toArray();
    }
}
