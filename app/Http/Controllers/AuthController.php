<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        // Verify reCAPTCHA with Google API
        $recaptchaSecret = env('NOCAPTCHA_SECRET');
        $response = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$request->input('g-recaptcha-response')}");
        $responseKeys = json_decode($response, true);

        if (!$responseKeys || !isset($responseKeys['success']) || !$responseKeys['success']) {
            return back()->withErrors(['email' => 'reCAPTCHA verification failed. Please try again.'])->onlyInput('email');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
        }

        // Generate 6-digit OTP (10 minutes expiry)
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        session(['auth_email' => $user->email]);

        return redirect()->route('otp.verify')->with('success', 'Your OTP Code is: ' . $otp);
    }

    public function showOtpForm()
    {
        if (!session('auth_email')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = session('auth_email');
        $user = User::where('email', $email)->first();

        if (!$user || $user->otp !== $request->otp || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.']);
        }

        // Clear OTP and authenticate user
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        Auth::login($user);
        session()->forget('auth_email');

        return redirect()->route('products.index')->with('success', 'Login successful with OTP!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('products.index')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}