<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|max:255|unique:users',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validasi gagal',
        'errors' => $validator->errors(),
      ], 422);
    }
    try {
      DB::beginTransaction();

      $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      DB::commit();

      return response()->json([
        'message' => 'Registrasi berhasil',
        'user' => $user,
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'success' => false,
        'message' => 'Registrasi gagal',
        'errors' => $validator->errors(),
      ], 400);
    }
  }

  public function login(Request $request)
  {
    // Validasi input
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Perhatikan inputan',
        'errors' => $validator->errors(),
      ], 422);
    }

    try {
      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Email atau password salah',
        ], 401);
      }

      // Kirim response tanpa token
      return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'user' => $user,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat login',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
