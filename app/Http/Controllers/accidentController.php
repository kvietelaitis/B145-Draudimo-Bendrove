<?php

namespace App\Http\Controllers;

use App\Models\IvykioNuotrauka;
use App\Models\IvykioTipas;
use App\Models\Ivykis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class accidentController extends Controller
{
    public function create()
    {
        $types = IvykioTipas::all();
        return view('accident.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'ivykio_data' => 'required|date',
            'pranesimo_data' => 'required|date',
            'bukle' => 'required|in:ismoketa,atmesta,tiriamas,pateiktas,patvirtintas',
            'apibudinimas' => 'required|string|max:255',
            'tipas_id' => 'required|exists:ivykio_tipas,id',
            'nuotraukos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validate['vartotojas_id'] = Auth::user()->id();
            $accident = Ivykis::create($validate);

            if ($request->hasFile('nuotraukos')) {
                foreach ($request->file('nuotraukos') as $image) {
                    $originalName = $image->getClientOriginalName();
                    $path = $image->store('ivykiai', 'public');

                    IvykioNuotrauka::create([
                        'ivykis_id' => $accident->id,
                        'failo_pavadinimas' => $originalName,
                        'failo_vieta' => $path,
                    ]);
                }
            }

            DB::commit();
            return redirect('accident.index')->with('success', 'Įvykis deklaruotas');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Klaida saugant duomenis: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $ivykis = Ivykis::with('nuotraukos', 'tipas', 'vartotojas')->findOrFail($id);
        return view('ivykis.show', compact('ivykis'));
    }

    public function deleteImage($id)
    {
        $nuotrauka = IvykioNuotrauka::findOrFail($id);

        Storage::disk('public')->delete($nuotrauka->failo_vieta);

        $nuotrauka->delete();

        return back()->with('success', 'Nuotrauka ištrinta.');
    }
}
