<?php

namespace App\Transformers;

use App\Models\User;
use App\Transformers\PostTransformer;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
  // $availableIncludes : digunakan untuk mengikut sertakan sebuah model/table lain
  protected array $availableIncludes = [
    'posts'
  ];

  public function transform(User $user)
  {

    return [
      'name' => $user->name,
      'email' => $user->email,
      'password' => $user->password,
    ];
  }

  //////////////
  public function includePosts(User $user)
  {

    // $user->posts : Relationship (memanggil method yang ada di Model User)
    // orderBy('id', 'desc')->get() : mengurutkan Post User berdasarkan ID terbesar (desc)
    $posts = $user->posts()->orderBy('id', 'desc')->get();

    return $this->collection($posts, new PostTransformer());
  }
}
