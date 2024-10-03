<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'login' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Проверка, если валидация не прошла
        if ($validator->fails()) {
            // Проверка на уникальность пользователя по логину или email
            $errors = $validator->errors();
            if ($errors->has('login')) {
                return response()->json(['message' => 'Пользователь с таким логином уже существует'], 409);
            }
            if ($errors->has('email')) {
                return response()->json(['message' => 'Пользователь с таким email уже существует'], 409);
            }
        }

        // Создание нового пользователя
        $user = User::create([
            'login' => $request->login,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }


    public function login(Request $request) {
        $credentials = $request->only('login', 'email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('token-name')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    // Обновить пользователя
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $oldEmail = $user->email;

        $user->update($request->only('name', 'email'));

        if ($request->email !== $oldEmail) {
            $user->email_verified_at = now();
            $user->save();
        }


        return response()->json(['message' => 'User updated']);
    }

    // Удалить пользователя
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
