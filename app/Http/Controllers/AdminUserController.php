<?php

namespace App\Http\Controllers;

use App\Models\Vartotojas;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = Vartotojas::where('role', 'darbuotojas');
        return redirect('/admin/dashboard');
    }

    public function createWorker(Request $request)
    {
        if (!$request->user() || $request->user()->role !== 'administratorius') {
            return response()->json([
                'status' => 0,
                'msg' => "Unauthorized",
            ], 403);
        }

        $validated = $request->validate([
            'worker_name' => 'required',
            'worker_lastname' => 'required',
            'role' => 'required'
        ]);

        $email = strtolower($validated['worker_name'] . '.' . $validated['worker_lastname'] . '@draudimas.lt');

        $originalEmail = $email;
        $counter = 1;
        while (Vartotojas::where('el_pastas', $email)->exists()) {
            $email = str_replace('@draudimas.lt', $counter . '@draudimas.lt', $originalEmail);
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

        return response()->json([
            'status' => 1,
            'msg' => "User created succesfully. One time password, save it.",
            'pass' => $password
        ]);
    }

    public function removeWorker(Request $request)
    {
        if (!$request->user() || $request->user()->role !== 'administratorius') {
            return response()->json([
                'status' => 0,
                'msg' => "Unauthorized",
            ], 403);
        }

        $data = $request->all();

        if ($data['id'] == 1) {
            $data = [
                'status' => '0',
                'msg' => 'Cannot delete Main Admin user'
            ];
        } else {
            $res = Vartotojas::where('id', $data['id'])->delete();

            if ($res) {
                $data = [
                    'status' => '1',
                    'msg' => 'success'
                ];
            } else {
                $data = [
                    'status' => '0',
                    'msg' => 'Failed to delete user'
                ];
            }
        }

        return response()->json($data);
    }

    public function blockWorker(Request $request)
    {
        if (!$request->user() || $request->user()->role !== 'administratorius') {
            return response()->json([
                'status' => 0,
                'msg' => "Unauthorized",
            ], 403);
        }

        $data = $request->all();

        if ($data['id'] == 1 || $data['id'] == $request->user()->id) {
            $data = [
                'status' => '0',
                'msg' => 'Negalima užblokuoti šios paskyros.'
            ];
        } else {
            $toBlock = Vartotojas::find($data['id'])->uzblokuotas == 0 ? 1 : 0;

            $res = Vartotojas::where('id', $data['id'])->update(['uzblokuotas' => $toBlock]);

            if ($res) {
                $data = [
                    'status' => '1',
                    'msg' => 'success'
                ];
            } else {
                $data = [
                    'status' => '0',
                    'msg' => 'Nepavyko užblokuoti paskyros.'
                ];
            }
        }

        return response()->json($data);
    }
}
