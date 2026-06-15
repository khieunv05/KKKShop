<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function viewLogin(){
        return view('auth.login');
    }
    public function viewRegister(){
        return view('auth.register');
    }
    public function register(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            
        ],[
            'name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'role' => 'user',
        ]);
        Auth::login($user);
        return $user->role === 'admin' 
            ? redirect()->route('admin.products.index') 
            : redirect("/");
    }
    public function login(Request $request){
        $validate = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],[
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
            return $user->role === 'admin' 
                ? redirect()->route('admin.products.index') 
                : redirect("/");
        }
        return back()->withErrors([
            'email' => 'Sai tài khoản hoặc mật khẩu',
        ]);
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/");
    }
    public function editProfile(Request $request){
        $user = User::findOrFail(Auth::id());
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->city = $request->input('city');
        $user->save();
        return redirect()->route('profile.edit')->with('success', 'Cập nhật thông tin thành công');
    }

    public function editPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = User::findOrFail(Auth::id());
        if(!Hash::check($request->input('current_password'),$user->password)){
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return redirect()->route('profile.password.edit')->with('success', 'Cập nhật mật khẩu thành công');
    }
}
