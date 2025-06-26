<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
  // ✅ Create new post
  public function store(Request $request)
  {
    $request->validate([
      'user_id' => 'required|exists:users,id',
      'content' => 'required|string',
      'image' => 'nullable|image|max:2048',
    ]);

    $imageUrl = null;
    if ($request->hasFile('image')) {
      $storedPath = $request->file('image')->store('posts', 'public');
      $imageUrl = url('storage/posts/' . basename($storedPath));
    }

    $post = Post::create([
      'user_id' => $request->user_id,
      'content' => $request->content,
      'image_url' => $imageUrl ? url('storage/' . $imageUrl) : null,
    ]);

    return response()->json(['message' => 'Post created!', 'data' => $post], 201);
  }

  // ✅ Get all posts
  public function getAll()
  {
    $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate(10);

    return response()->json([
      'data' => $posts,
    ], 200);
  }


  // ✅ Get post by ID
  public function getById($id)
  {
    $post = Post::with('user')->find($id);

    if (!$post) {
      return response()->json(['message' => 'Post not found'], 404);
    }

    return response()->json(['data' => $post], 200);
  }
}
