<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // 成功訊息顏色
            session()->flash('alert-info', 'success');
            return redirect('boardlist')->with('result', '登入成功');
        }
        return view('login.index');
    }

    // 檢查登入是否成功
    public function loginCheck(Request $request)
    {
        $account = $request->input('account');
        $password = $request->input('password');
        $validatedData = $request->validate([
            'account' => 'required|max:100',
            'password' => 'required',
        ]);
        // 驗證登入
        if (!Auth::attempt(['account' => $account, 'password' => $password])) {
            $result['error'] = true;
            $result['message'] = '帳號或密碼有誤';
            return response()->json($result, 403);
        }
        $result['error'] = false;
        $result['message'] = '登入成功';
        return response()->json($result, 200);
    }

    public function register()
    {
        if (Auth::check()) {
            // 成功訊息顏色
            session()->flash('alert-info', 'success');
            return redirect('boardlist')->with('result', '註冊並登入成功');
        }
        return view('login.register');
    }

     // 檢查註冊是否成功
     public function registerCheck(Request $request)
     {
        $account = $request->input('account');
        $password = $request->input('password');
        $validatedData = $request->validate([
            'account' => 'required|unique:users|max:100',
            'password' => 'required',
        ]);
        // 密碼加密
        $hash_password = bcrypt($password);
        // 寫入資料庫
        $insert_result = User::insert(
            ['account' => $account, 'password' => $hash_password]
        );
        if (!$insert_result) {
            $result['error'] = true;
            $result['message'] = '註冊失敗';
            return response()->json($result, 403);
        }
        // 驗證登入
        if (!Auth::attempt(['account' => $account, 'password' => $password])) {
            $result['error'] = true;
            $result['message'] = '登入失敗';
            return response()->json($result, 403);
        }
        $result['error'] = false;
        $result['message'] = '註冊並登入成功';
        return response()->json($result, 200);
     }

     public function logout()
     {
        Auth::logout();
        // 回傳成功訊息
        session()->flash('alert-info', 'success');
        return redirect('login')->with('result', '登出成功');
     }

}
