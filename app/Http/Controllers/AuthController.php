<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('customer.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = trim($request->input('login') ?: $request->input('email', ''));
        $password = $request->input('password', '');

        if (empty($input) || empty($password)) {
            return back()->withErrors([
                'login' => 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني وكلمة المرور.',
            ])->onlyInput('login');
        }

        // Support username 'admin' alias or direct role match
        $user = null;
        $lowered = strtolower($input);
        if ($lowered === 'admin' || $lowered === 'إدارة' || $lowered === 'ادارة') {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $adm) {
                if (Hash::check($password, $adm->password)) {
                    $user = $adm;
                    break;
                }
            }
            if (!$user) {
                $user = $admins->first();
            }
        } elseif (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $lowered)->first();
        } else {
            $cleanPhone = preg_replace('/[^0-9]/', '', $input);
            $user = User::where('email', $input)
                ->orWhere('name', 'like', "%{$input}%")
                ->when(!empty($cleanPhone), function ($q) use ($cleanPhone) {
                    $q->orWhere('phone', 'like', "%{$cleanPhone}%");
                })
                ->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'login' => 'بيانات الدخول غير صحيحة. يرجى التأكد من اسم المستخدم أو البريد وكلمة المرور.',
        ])->onlyInput('login');
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }

        $referralCode = $request->query('ref', session('referred_by', $request->cookie('referred_by')));

        return view('auth.register', compact('referralCode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:150',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|min:8|confirmed',
            'referred_by' => 'nullable|string|max:30',
        ], [
            'name.required' => 'يرجى إدخال الاسم الكريم.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل لدينا بالفعل.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $referredBy = null;
        if (!empty($validated['referred_by'])) {
            $referrer = User::where('referral_code', strtoupper(trim($validated['referred_by'])))->first();
            if ($referrer) {
                $referredBy = $referrer->referral_code;
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'role' => 'customer',
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $referredBy,
            'wallet_balance' => 0,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')->with('success', 'أهلاً بك في متجر زاد نيكس! تم إنشاء حسابك بنجاح.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function orders()
    {
        return redirect()->route('customer.dashboard', ['tab' => 'orders']);
    }
}
