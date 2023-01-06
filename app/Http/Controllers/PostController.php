<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Transformers\PostTransformer;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function add(Request $request)
    {
        //  $request->bearerToken() : untuk mengambil Bearer Token dari Authorization header
        $this->validate($request, [
            'content' => 'required'
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $response = fractal()
            ->item($post)
            ->transformWith(new PostTransformer())
            ->toArray();

        return response()->json([$response, 'message' => 'Added new post successfully'], 201);
    }

    public function update(Request $request, Post $post, $id)
    {
        //  $request->bearerToken() : untuk mengambil Bearer Token dari Authorization header
        // return $request->bearerToken();

        // $id : menangkap ID yang di kirimkan lewat URI
        // misalnya http://localhost:8000/api/post/2
        // return $post->findOrFail($id);

        // ketika saat Request Content kosong maka akan memakai Post Content yang sudah ada atau nilainya sama dengan yang ada di tabel

        // ambil ID dari user yang sedang login
        $user = Auth::id();
        // ambil User_ID dari model Post untuk mencocokan apakah User_ID sama dengan ID yang sedang login
        $post = $post->find($id)->user_id;

        // jika ID dari User dan User_ID dari Post tidak sama
        if ($user !== $post) {
            return response()->json(['message' => 'You not access to updated'], 403);
        }

        $this->validate($request, [
            'content' => 'required',
        ]);

        $post = Post::find($id);
        $post->content = $request->content;
        $post->save();

        $response =  fractal()
            ->item($post)
            ->transformWith(new PostTransformer())
            ->toArray();

        return response()->json([$response, 'message' => 'Post uptudated'], 201);
    }

    public function delete(Post $post, $id)
    {
        // ambil ID dari user yang sedang login
        $user = Auth::id();
        // ambil User_ID dari model Post untuk mencocokan apakah User_ID sama dengan ID yang sedang login
        $post = $post->find($id)->user_id;

        if ($user !== $post) {
            return response()->json(['message' => 'You not access to deleted'], 403);
        }

        Post::find($id)->delete();
        return response()->json(['message' => 'Post has been deleted'], 201);
    }
}
