<?php

namespace App\Http\Controllers;

use App\Models\CsInfo;
use App\Models\AdminInfo;
use App\Models\NewCustomer;
use Illuminate\Http\Request;
use App\Models\ParentAccount;
use App\Models\SalesLeadInfo;
use App\Models\TherapistInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginView()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    
        $user = $this->attemptLogin($credentials['username'], $credentials['password']);
    
        if ($user) {
            Auth::guard($user['guard'])->loginUsingId($user['user']->id);
            return redirect()->route("{$user['guard']}.dashboard");
        }
    
        // Return back with an error message if login fails
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    private function attemptLogin($username, $password)
    {
        $roles = [
            'admin' => AdminInfo::class,
            'parent' => ParentAccount::class,
            'therapist' => TherapistInfo::class,
            'cs' => CsInfo::class,
            'sales' => SalesLeadInfo::class,
            
        ];

        foreach ($roles as $guard => $model) {
            $user = $model::where('username', $username)->first();
            if ($user && Hash::check($password, $user->password)) {
                return ['guard' => $guard, 'user' => $user];
            }
        }

        return null;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function inquiryView()
    {
        return view ('/inquiry-form');
    }

    public function inquirySubmit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string', // Assuming it's optional
            'posscode' => 'nullable|string', // Assuming it's optional
            'city' => 'nullable|string', // Assuming it's optional
            'country' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $addCustomer = NewCustomer::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'address' => $validatedData['address'],
            'posscode' => $validatedData['posscode'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'remark' => $validatedData['remark'],
        ]);
        return redirect()->route('inquiry-form')->with('success', 'Thank You! Your inquiry has been submitted successfully.');
    }

}


