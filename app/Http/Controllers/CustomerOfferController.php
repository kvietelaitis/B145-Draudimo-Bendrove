<?php

namespace App\Http\Controllers;

use App\Models\Pasiulymas;
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
}
