<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kainos Koregavimas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-3xl w-full mx-auto p-6 bg-white shadow rounded">

        <form id="price-edit-form" action="{{ '/worker/requests/make-offer' }}" method="POST"
            onsubmit="return confirm('Ar tikrai priimti šį prašymą?');">
            @csrf

            <input type="hidden" name="prasymas_id" value="{{ $prasymas->id }}" />

            <h1 class="text-2xl font-semibold mb-4">Koreguoti prašymą #{{ $prasymas->id }}</h1>

            <div class="mb-4">
                <h2 class="font-medium">Klientas</h2>
                <p class="text-sm text-gray-700">{{ $prasymas->vartotojas->vardas ?? '-' }}
                    {{ $prasymas->vartotojas->pavarde ?? '' }}</p>
            </div>

            <div class="mb-4">
                <h2 class="font-medium">Paketo informacija</h2>
                <p class="text-sm"><strong>Polisas:</strong>
                    {{ $prasymas->paketas->draudimoPolisas->pavadinimas ?? '-' }}</p>
                <p class="text-sm"><strong>Paketo pavadinimas:</strong>
                    {{ $prasymas->paketas->pavadinimas ?? '-' }}</p>
            </div>

            @if(!empty($prasymas->objekto_duomenys))
            <div class="mb-4 border-t pt-4">
                <h2 class="font-medium mb-2">Objekto duomenys</h2>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    @foreach($prasymas->objekto_duomenys as $key => $value)
                    <div class="text-gray-600 capitalize">{{ str_replace('_', ' ', $key) }}:</div>
                    <div class="font-medium">{{ $value }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-4">
                <h2 class="font-medium">Kainų suvestinė</h2>

                <div class="mt-3 grid grid-cols-2 gap-4 items-center">
                    <div class="col-span-2 space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <label class="text-sm text-gray-700">Bazė:</label>
                            <div class="flex items-center space-x-3">
                                <input type="number" step="0.01" name="base_price" id="base_price"
                                    value="{{ number_format($basePrice, 2, '.', '') }}"
                                    class="w-40 border rounded px-2 py-1 text-right" required />
                                <span class="text-sm text-gray-600">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2 font-semibold mt-2">Paslaugos</div>

                    <div class="col-span-2 space-y-2">
                        @if($services->isNotEmpty())
                        @foreach($services as $i => $s)
                        <div class="flex justify-between items-center text-sm">
                            <div class="text-gray-800">{{ $s['name'] }}</div>

                            <div class="flex items-center space-x-3">
                                <input type="hidden" name="services[{{ $i }}][id]" value="{{ $s['id'] ?? '' }}" />
                                <input type="number" step="0.01" name="services[{{ $i }}][price]"
                                    class="w-40 border rounded px-2 py-1 text-right service-price"
                                    value="{{ number_format($s['price'], 2, '.', '') }}" required />
                                <span class="text-sm text-gray-600">€</span>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-sm text-gray-600">Nėra paslaugų</div>
                        @endif
                    </div>

                    <div class="text-sm font-medium text-gray-800">Paslaugų suma:</div>
                    <div class="text-sm text-right text-gray-800" id="services-total">{{
                        number_format($servicesTotal,2)
                        }} €</div>

                    <div class="text-sm font-semibold">Tarifas (iš viso):</div>
                    <div class="text-right font-semibold" id="subtotal">{{ number_format($totalPrice,2) }} €</div>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="font-medium">Taikoma nuolaida</h3>

                <div class="mt-3 flex items-center justify-between">
                    <select id="selected_discount_id" name="selected_discount_id"
                        class="border rounded px-3 py-2 w-72 bg-white">
                        <option value="">(Nėra)</option>
                        @foreach(($discounts ?? collect()) as $d)
                        <option value="{{ $d->id }}" data-percent="{{ $d->procentas }}" {{ (isset($appliedDiscount) &&
                            $appliedDiscount['id']==$d->id) ? 'selected' : ''
                            }}>
                            {{ $d->rusis ?? 'Nuolaida' }} ({{ $d->procentas }}%)
                        </option>
                        @endforeach
                    </select>

                    <div class="text-sm text-gray-800" id="selected-discount-amount">
                        @if(isset($appliedDiscount) && $appliedDiscount['amount'])
                        -{{ number_format($appliedDiscount['amount'], 2) }} €
                        @else
                        0.00 €
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>Galutinė kaina:</span>
                    <span id="final-total">{{ number_format(($finalTotal ?? $totalPrice),2) }} €</span>
                </div>
            </div>

            <div class="flex space-x-3 justify-end mt-6">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Priimti ir patvirtinti
                </button>

                <a href="{{ route('worker.requests.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 text-gray-800">
                    Atšaukti
                </a>
            </div>

        </form>

    </div>
</body>

</html>

<script>
    function recalculateTotals() {
        const baseEl = document.getElementById('base_price');
        const base = baseEl ? parseFloat(baseEl.value || 0) : 0;

        const serviceEls = document.querySelectorAll('.service-price');
        let servicesTotal = 0;
        serviceEls.forEach(e => servicesTotal += parseFloat(e.value || 0) || 0);

        // update services total element
        const servicesTotalEl = document.getElementById('services-total');
        if (servicesTotalEl) servicesTotalEl.textContent = servicesTotal.toFixed(2) + ' €';

        const subtotal = (base + servicesTotal);
        const subtotalEl = document.getElementById('subtotal');
        if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2) + ' €';

        // discount percent from selected option
        const sel = document.getElementById('selected_discount_id');
        const percent = sel ? Number(sel.selectedOptions[0].dataset.percent || 0) : 0;

        const discountAmount = Math.round((subtotal * (percent / 100)) * 100) / 100;
        const selectedDiscountAmountEl = document.getElementById('selected-discount-amount');
        if (selectedDiscountAmountEl) selectedDiscountAmountEl.textContent = (percent ? ('-' + discountAmount.toFixed(2) + ' €') : '-0.00 €');

        const finalTotal = Math.max(0, subtotal - discountAmount);
        const finalEl = document.getElementById('final-total');
        if (finalEl) finalEl.textContent = finalTotal.toFixed(2) + ' €';
    }

    document.addEventListener('input', function(e){
        if (e.target.matches('.service-price') || e.target.id === 'base_price' || e.target.id === 'selected_discount_id') {
            recalculateTotals();
        }
    });

    // initial calc when DOM ready
    document.addEventListener('DOMContentLoaded', recalculateTotals);
</script>