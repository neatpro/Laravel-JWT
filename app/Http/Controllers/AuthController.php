<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Http\Resources\User\UserResource;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  public function login()
  {
    $credentials = request(['username', 'password']);

    if (! $token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response([
      'status' => 'success'
    ])
    ->header('Authorization', $token);
  }

  public function user()
  {
    $user = auth()->user();
    return new UserResource($user);
  }

  public function logout()
  {
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
  }

  public function refresh()
  {
    return response([
      'status' => 'success'
    ]);
  }

  protected function respondWithToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ]);
  }

  public function update(Request $request)
  {
    $user = auth()->user();

    $user->username = $request->username;
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    if ($request->password)
      $user->password = bcrypt($request->password);

    try {
          $user->save();
        return "Ok";
    } catch (\Illuminate\Database\QueryException $exception){
        return response()->json(['error' => $exception->errorInfo[2]], 404);
    }
  }
}