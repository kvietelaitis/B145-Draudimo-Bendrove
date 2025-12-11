<?php

namespace App\Http\Controllers;

use App\Models\Vartotojas;
use Illuminate\Http\Request;
use Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = Vartotojas::where('role', 'darbuotojas');

        return redirect('/admin/dashboard');
    }

    public function createWorker(Request $request)
    {
        $validated = $request->validate([
            'worker_name' => 'required',
            'worker_lastname' => 'required',
            'role' => 'required',
        ]);

        $email = strtolower($validated['worker_name'].'.'.$validated['worker_lastname'].'@draudimas.lt');

        $originalEmail = $email;
        $counter = 1;
        while (Vartotojas::where('el_pastas', $email)->exists()) {
            $email = str_replace('@draudimas.lt', $counter.'@draudimas.lt', $originalEmail);
            $counter++;
        }

        $password = Str::random(12);

        Vartotojas::create([
            'vardas' => $validated['worker_name'],
            'pavarde' => $validated['worker_lastname'],
            'el_pastas' => $email,
            'slaptazodis' => bcrypt($password),
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Darbuotojo slaptažodis: '.$password);
    }

    public function removeWorker(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => ['required', 'integer', 'exists:vartotojas,id'],
        ]);

        Vartotojas::where('id', $validated['worker_id'])->delete();

        return back();
    }

    public function blockWorker(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => ['required', 'integer', 'exists:vartotojas,id'],
        ]);

        $worker = Vartotojas::where('id', $validated['worker_id'])->first();

        if ($worker->uzblokuotas == false) {
            $worker->uzblokuotas = true;
        } else {
            $worker->uzblokuotas = false;
        }

        $worker->save();

        return back();
    }
}
