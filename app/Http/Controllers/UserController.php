<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vartotojas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'vardas' => ['required'],
            'pavarde' => ['required'],
            'el_pastas' => ['required', Rule::unique('vartotojas', 'el_pastas')],
            'slaptazodis' => ['required', 'max:20', 'min:8']
        ]);

        $incomingFields['slaptazodis'] = bcrypt($incomingFields['slaptazodis']);

        try {
            $user = Vartotojas::create($incomingFields);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        Auth::login($user);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginemail' => ['required'],
            'loginpassword' => ['required']
        ]);

        if (Auth::attempt(['el_pastas' => $incomingFields['loginemail'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
        }

        return redirect('/');
    }

    public function reportAccident() {
        $user = Vartotojas::find(Auth::user()->id);

        $user->paskutinio_incidento_data = Carbon::now()->format('Y-m-d');

        $user->save();

        return redirect('customer/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
