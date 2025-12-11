<?php

namespace App\Http\Controllers;

use App\Models\Vartotojas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'vardas' => ['required'],
            'pavarde' => ['required'],
            'el_pastas' => ['required', Rule::unique('vartotojas', 'el_pastas')],
            'slaptazodis' => ['required', 'max:20', 'min:8'],
            'referral_code' => ['nullable', 'exists:vartotojas,referral_code'],
        ], [
            'vardas.required' => 'Vardo laukas yra privalomas.',
            'pavarde.required' => 'Pavardės laukas yra privalomas.',
            'el_pastas.required' => 'El. pašto laukas yra privalomas.',
            'el_pastas.unique' => 'Šis el. paštas jau užregistruotas.',
            'slaptazodis.required' => 'Slaptažodžio laukas yra privalomas.',
            'slaptazodis.min' => 'Slaptažodis turi būti bent 8 simbolių ilgio.',
            'slaptazodis.max' => 'Slaptažodis negali būti ilgesnis nei 20 simbolių.',
        ]);

        $incomingFields['slaptazodis'] = bcrypt($incomingFields['slaptazodis']);

        $referralCode = $incomingFields['referral_code'];
        $generatedCode = strtoupper(bin2hex(random_bytes(4)));
        $incomingFields['referral_code'] = $generatedCode;

        try {
            $user = Vartotojas::create($incomingFields);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        if (! empty($referralCode)) {
            $referrer = Vartotojas::where('referral_code', $referralCode)->first();

            if ($referrer) {
                Nuolaida::create([
                    'rusis' => 'pakvietimas',
                    'procentas' => 5,
                    'galiojimo_pabaiga' => Carbon::now()->addMonth()->format('Y-m-d'),
                    'turi_vartotojas_id' => $referrer->id,
                ]);
            }
        }

        Auth::login($user);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginemail' => ['required'],
            'loginpassword' => ['required'],
        ]);

        if (Auth::attempt(['el_pastas' => $incomingFields['loginemail'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();

            $user = Vartotojas::find(Auth::user()->id);

            $lastIncidentDate = $user->paskutinio_incidento_data;
            $years = 0;

            if ($lastIncidentDate) {
                $years = Carbon::parse($lastIncidentDate)->diffInYears(Carbon::now());
            }

            switch ($years) {
                case 0:
                    break;
                case 1:
                    Nuolaida::create([
                        'rusis' => 'lojalumas',
                        'procentas' => 2,
                        'galiojimo_pabaiga' => Carbon::now()->addYear()->format('Y-m-d'),
                        'turi_vartotojas_id' => $user->id,
                    ]);
                    break;
                case 2:
                    Nuolaida::create([
                        'rusis' => 'lojalumas',
                        'procentas' => 5,
                        'galiojimo_pabaiga' => Carbon::now()->addYear()->format('Y-m-d'),
                        'turi_vartotojas_id' => $user->id,
                    ]);
                    break;
                case 3:
                    Nuolaida::create([
                        'rusis' => 'lojalumas',
                        'procentas' => 10,
                        'galiojimo_pabaiga' => Carbon::now()->addYear()->format('Y-m-d'),
                        'turi_vartotojas_id' => $user->id,
                    ]);
                    break;
                default:
                    Nuolaida::create([
                        'rusis' => 'lojalumas',
                        'procentas' => 10,
                        'galiojimo_pabaiga' => Carbon::now()->addYear()->format('Y-m-d'),
                        'turi_vartotojas_id' => $user->id,
                    ]);
                    break;
            }

            return redirect('/');
        }

        return back()->withErrors([
            'loginemail' => 'Neteisingas el. paštas arba slaptažodis.',
        ])->onlyInput('loginemail');

    }

    public function reportAccident()
    {
        $user = Vartotojas::find(Auth::user()->id);

        $user->paskutinio_incidento_data = Carbon::now()->format('Y-m-d');

        $user->save();

        Nuolaida::where('turi_vartotojas_id', $user->id)
            ->where('rusis', 'lojalumas')
            ->delete();

        return redirect('customer/dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
