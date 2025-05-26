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
      'username' => ['required', 'string', 'max:255'],
      'email' => 'required|string|email|max:255|unique:users',
      'phone' => 'required|digits_between:10,20',
      'birthday' => 'required|date',
      'gender' => 'required|in:L,P',
      'password' => 'required|string|min:6|confirmed',
    ], [
      'gender.in' => 'Gender harus berupa L (Laki-laki) atau P (Perempuan).',
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
        'phone' => $request->phone,
        'birthday' => $request->birthday,
        'gender' => $request->gender,
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
        'error' => $e->getMessage(),
      ], 400);
    }
  }

  public function login(Request $request)
  {
    // Validasi input
    $validator = Validator::make($request->all(), [
      'login' => 'required|string',
      'password' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Perhatikan inputan',
        'errors' => $validator->errors(),
      ], 422);
    }

    try {
      $loginInput = $request->input('login');
      $user = User::where('email', $loginInput)
        ->orWhere('phone', $loginInput)
        ->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Email/Nomor telepon atau password salah',
        ], 401);
      }

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
