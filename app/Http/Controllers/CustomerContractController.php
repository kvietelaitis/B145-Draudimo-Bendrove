<?php

namespace App\Http\Controllers;

use App\Models\Sutartis;
use Illuminate\Support\Facades\Auth;

class CustomerContractController extends Controller
{
    public function index()
    {
        $contracts = Sutartis::where('pasiraso_id', Auth::user()->id)
            ->with(['paketas', 'paketas.draudimoPolisas'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.contracts.index', compact('contracts'));
    }
}
