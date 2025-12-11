<?php

namespace App\Http\Controllers;

use App\Models\Pasiulymas;
use App\Models\Sutartis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOfferController extends Controller
{
    public function index()
    {
        $offers = Pasiulymas::where('vartotojas_id', Auth::user()->id)
            ->with('paketas')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.offers.index', compact('offers'));
    }

    public function acceptOffer(Request $request)
    {
        $validated = $request->validate([
            'offer_id' => ['required'],
        ]);

        $offer = Pasiulymas::where('id', $validated['offer_id'])
            ->with('paketas')
            ->firstOrFail();

        $date = Carbon::now()->format('Y-m-d');
        $dateYearFromNow = Carbon::now()->addYear()->format('Y-m-d');

        Sutartis::create([
            'galutine_kaina' => $offer->koreguota_kaina,
            'isigaliojimo_data' => $date,
            'galiojimo_pabaigos_data' => $dateYearFromNow,
            'bukle' => 'aktyvi',
            'pasiraso_id' => Auth::user()->id,
            'sudaro_id' => $offer->darbuotojas_id,
            'paketas_id' => $offer->paketas->id,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Sutartis pasirašyta');
    }

    public function details($offerId)
    {
        $offer = Pasiulymas::where('id', $offerId)
            ->with('paketas', 'paketas.draudimoPolisas', 'paketas.paslaugos', 'vartotojas')
            ->firstOrFail();

        return view('customer.offers.details', compact('offer'));
    }
}
