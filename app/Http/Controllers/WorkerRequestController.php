<?php

namespace App\Http\Controllers;

use App\Models\Nuolaida;
use App\Models\Pasiulymas;
use App\Models\Prasymas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerRequestController extends Controller
{
    public function index()
    {
        $requests = Prasymas::with(['paketas', 'paketas.paslaugos', 'paketas.draudimoPolisas', 'vartotojas'])
            ->orderByDesc('data')
            ->paginate(10);

        return view('worker.requests.index', compact('requests'));
    }

    public function editForm($requestId)
    {
        $prasymas = Prasymas::where('id', $requestId)->with(['paketas', 'paketas.paslaugos', 'paketas.draudimoPolisas', 'vartotojas'])->firstOrFail();

        $basePrice = $prasymas->paketas->draudimoPolisas->bazine_kaina;

        $services = collect($prasymas->paketas->paslaugos ?? [])
            ->map(function ($pp) {
                return [
                    'id' => $pp->id,
                    'name' => $pp->pavadinimas ?? ($pp->paslauga->pavadinimas ?? 'Paslauga'),
                    'price' => $pp->kaina ?? ($pp->paslauga->kaina ?? 0),
                ];
            });

        $servicesTotal = $services->sum('price');

        $totalPrice = $basePrice + $servicesTotal;
        $totalDiscount = 0;

        $discountModels = Nuolaida::where('turi_vartotojas_id', $prasymas->vartotojas->id ?? 0)
            ->where(function ($q) {
                $q->whereNull('galiojimo_pabaiga')
                    ->orWhere('galiojimo_pabaiga', '>=', now());
            })
            ->whereNull('panaudojimo_laikas')
            ->get();

        $best = $discountModels->sortByDesc('procentas')->first();

        if ($best && ($best->procentas ?? 0) > 0) {
            $discountPercent = (float) $best->procentas;
            $discountAmount = round($totalPrice * ($discountPercent / 100.0), 2);
            $appliedDiscount = [
                'id' => $best->id,
                'name' => $best->rusis ?? 'Nuolaida',
                'percent' => $discountPercent,
                'amount' => $discountAmount,
                'model' => $best,
            ];
        } else {
            $discountPercent = 0;
            $discountAmount = 0.0;
            $appliedDiscount = null;
        }

        $finalTotal = max(0.0, round($totalPrice - $discountAmount, 2));

        return view('worker.requests.edit', compact(
            'prasymas',
            'basePrice',
            'services',
            'servicesTotal',
            'totalPrice',
            'appliedDiscount',
            'discountAmount',
            'discountPercent',
            'finalTotal',
        ))->with('discounts', $discountModels);
    }

    public function makeOffer(Request $request)
    {
        $data = $request->validate([
            'prasymas_id' => ['required', 'integer'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'array'],
            'services.*.id' => ['required', 'integer'],
            'services.*.price' => ['required', 'numeric', 'min:0'],
            'selected_discount_id' => ['nullable', 'integer', 'exists:nuolaida,id'],
        ]);

        $prasymas = Prasymas::where('id', $data['prasymas_id'])->with(['paketas', 'paketas.paslaugos', 'paketas.draudimoPolisas', 'vartotojas'])->firstOrFail();

        $basePrice = (float) $data['base_price'];

        $servicesTotal = 0;

        foreach ($data['services'] ?? [] as $s) {
            $price = (float) ($s['price'] ?? 0);
            $servicesTotal += (float) ($price);

            $serviceDetails[] = [
                'id' => $s['id'],
                'price' => $price,
            ];
        }

        $subtotal = round($basePrice + $servicesTotal, 2);

        $discountAmount = 0.0;
        $selectedDiscountId = null;

        if (! empty($data['selected_discount_id'])) {
            $selectedDiscount = Nuolaida::find($data['selected_discount_id']);
            if ($selectedDiscount) {
                $selectedDiscountId = $selectedDiscount->id;
                $discountAmount = round($subtotal * ($selectedDiscount->procentas / 100.0), 2);

                if ($selectedDiscount->rusis === 'pakvietimas') {
                    $selectedDiscount->panaudojimo_laikas = Carbon::now()->format('Y-m-d');
                    $selectedDiscount->save();
                }
            }
        }

        $finalTotal = max(0, round($subtotal - $discountAmount, 2));

        Pasiulymas::create([
            'koreguota_kaina' => $finalTotal,
            'bukle' => 'priimtas',
            'sukurimo_data' => now()->toDateString(),
            'vartotojas_id' => $prasymas->vartotojas->id,
            'darbuotojas_id' => Auth::user()->id,
            'paketas_id' => $prasymas->paketas->id,

            'kainos_detales' => json_encode([
                'base_price' => $basePrice,
                'services' => $serviceDetails,
                'discount_id' => $selectedDiscountId,
                'discount_amount' => $discountAmount,
                'subtotal' => $subtotal,
            ]),
        ]);

        return redirect()->route('worker.requests.index')->with('success', 'Pasiūlymas sėkmingai sukurtas');
    }
}
