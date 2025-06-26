<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
  /**
   * Simpan story baru
   */

  public function store(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'media_url' => 'required|url',
      'media_type' => 'required|in:image,video',
      'caption' => 'nullable|string',
    ]);

    $story = Story::create($validated);

    return response()->json($story, 201);
  }

  // public function store(Request $request)
  // {
  //   // Validasi input
  //   $validated = $request->validate([
  //     'media_url' => 'required|string|max:2048',
  //     'media_type' => 'required|in:image,video',
  //     'caption' => 'nullable|string|max:255',
  //   ]);

  //   // Simpan story
  //   $story = Story::create([
  //     'user_id' => Auth::id(),
  //     'media_url' => $validated['media_url'],
  //     'media_type' => $validated['media_type'],
  //     'caption' => $validated['caption'] ?? null,
  //   ]);

  //   return response()->json([
  //     'message' => 'Story berhasil dibuat.',
  //     'data' => $story,
  //   ], 201);
  // }

  /**
   * Ambil semua story (versi sederhana)
   */
  public function index()
  {
    $stories = Story::with('user')
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json($stories);
  }

  /**
   * Ambil satu story (versi sederhana)
   */
  public function show($id)
  {
    $story = Story::with('user')->find($id);

    if (!$story) {
      return response()->json(['message' => 'Story tidak ditemukan.'], 404);
    }

    return response()->json($story);
  }
}
