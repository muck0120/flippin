<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * ログイン処理。成功時にアクセストークンを返却。
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('user_mail', $request->user_mail)->first();
        $equalsPassword = $user ?
            Hash::check($request->user_password, $user->user_password) : false;
        if ($equalsPassword) {
            if (!$user->user_api_token) {
                $user->user_api_token = Str::random(80);
                $user->save();
            }
            return response()->json([
                'token' => $user->user_api_token,
                'user' => $user
            ], 200);
        }
        return redirect('/unauthorized');
    }

    /**
     * ログアウト処理。アクセストークンを削除。
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $user = Auth::user();
        $user->user_api_token = null;
        $user->save();
        return response()->json(['message' => 'logout.'], 200);
    }

    /**
     * ログイン中のユーザーを取得。
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        return response()->json(Auth::user(), 200);
    }

    /**
     * 新規ユーザー作成。
     *
     * @param \App\Http\Requests\UserRequest
     * @return \Illuminate\Http\Response
     */
    public function createUser(UserRequest $request)
    {
        $user = new User();
        $user->fill([
            'user_name' => $request->user_name,
            'user_mail' => $request->user_mail,
            'user_password' => Hash::make($request->user_password),
            'user_api_token' => Str::random(80)
        ])->save();
        return response()->json([
            'token' => $user->user_api_token,
            'user' => $user
        ], 200);
    }

    /**
     * ログイン中のユーザーを更新。
     *
     * @param \App\Http\Requests\UserRequest
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UserRequest $request)
    {
        $user = Auth::user();
        if (is_null($request->user_password) || $request->user_password === '') {
            User::find($user->user_id)->fill([
                'user_name' => $request->user_name,
                'user_mail' => $request->user_mail
            ])->save();
        } else {
            User::find($user->user_id)->fill([
                'user_name' => $request->user_name,
                'user_mail' => $request->user_mail,
                'user_password' => Hash::make($request->user_password)
            ])->save();
        }
        $user = User::find($user->user_id);
        return response()->json(['user' => $user], 200);
    }
}
