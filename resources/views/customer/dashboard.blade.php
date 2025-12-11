<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klientų portalas</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8 space-y-8">

    <header class="flex justify-between items-center p-4 bg-white-500 text-black">
        <div>
            <a href="{{ route('customer.dashboard') }}" class="text-4xl font-semibold hover:underline">
                Draudimas.lt
            </a>
        </div>

        <div class="flex items-center space-x-4">
            <a href="{{route('customer.offers.index')}}"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Mano pasiūlymai
            </a>

            <a href="{{route('customer.contracts.index')}}"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Mano sutartys
            </a>

            <a href="{{route('customer.incidents.index')}}"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Mano įvykiai
            </a>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Atsijungti
                </button>
            </form>
        </div>

    </header>
    <!-- Insurance Policies -->
    <div class="space-y-6 px-60">
        <h2 class="text-2xl font-medium">Draudimo Polisai:</h2>
        <div class="grid grid-cols-1 gap-6">
            @foreach($insurancePolicies as $insurancePolicy)
            <div
                class="border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition bg-white flex flex-col justify-between">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $insurancePolicy->pavadinimas }}</h3>
                    <p class="text-gray-600 mb-2">{{ $insurancePolicy->apibudinimas }}</p>
                    @if(!empty($insurancePolicy->salygos['apima']))
                    <p class="text-sm text-gray-500">Privalumai: {{ implode(', ', $insurancePolicy->salygos['apima']) }}
                    </p>
                    @endif
                </div>
                <form action="{{ route('customer.policies.packages', $insurancePolicy->id) }}" method="GET">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition w-full">Pasirinkti</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Lease Calculator -->
    <div class="border border-gray-200 rounded-lg p-6 shadow-sm space-y-4">
        <h2 class="text-2xl font-medium">Lizingo skaičiuoklė:</h2>
        <form id="lease-calculation-form" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-medium" for="category">Pasirinkite kategoriją:</label>
                <select name="category" id="category"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                    <option value="--">--</option>
                    <option value="car">Automobilis</option>
                    <option value="machinery">Technika</option>
                    <option value="electronics">Elektronika</option>
                    <option value="furniture">Baldai</option>
                    <option value="housing">Būstas</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium" for="initial_cost">Įveskite pradinę kainą:</label>
                <input type="number" name="initial_cost" id="initial_cost"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block mb-1 font-medium" for="market_value">Įveskite rinkos kainą:</label>
                <input type="number" name="market_value" id="market_value"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block mb-1 font-medium" for="remaining_lease">Įveskite lizingo likutį:</label>
                <input type="number" name="remaining_lease" id="remaining_lease"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block mb-1 font-medium" for="insurance_type">Koks draudimo tipas?</label>
                <select name="insurance_type" id="insurance_type"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                    <option value="--">--</option>
                    <option value="full">Pilnas</option>
                    <option value="replacement">Pakeitimas</option>
                    <option value="damage">Žala/Remontas</option>
                </select>
            </div>

            <button type="submit" id="calculate-button"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Apskaičiuoti</button>
        </form>

        <div id="calculationResult" class="hidden mt-4 p-4 border border-gray-200 rounded bg-gray-50">
            <h3 class="font-medium text-lg mb-2">Rezultatai</h3>
            <p><strong>Mėnesinė įmoka:</strong> €<span id="baseCost">0</span></p>
            <p><strong>Metinė įmoka:</strong> €<span id="annualCost">0</span></p>
            @if($years >= 1)
            <p><strong>Mėnesinė įmoka (Su nuolaida):</strong> €<span id="discountedBase">0</span></p>
            @endif
        </div>

        <div id="errorResult" class="hidden mt-4 text-red-600"></div>
    </div>

    <script>
        document.getElementById('lease-calculation-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;

            const calculationData = {
                category: form.querySelector('#category').value,
                initial_cost: form.querySelector('#initial_cost').value,
                market_value: form.querySelector('#market_value').value,
                remaining_lease: form.querySelector('#remaining_lease').value,
                insurance_type: form.querySelector('#insurance_type').value,
                years_since_accident: JSON.stringify({{floor($years)}})
            }

            const resultDiv = document.getElementById('calculationResult');
            const errorDiv = document.getElementById('errorResult');

            fetch('/calculate-lease', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(calculationData)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status == 1 || data.final_cost !== undefined) {
                        document.getElementById('baseCost').textContent = data.baseCost.toFixed(2);
                        document.getElementById('annualCost').textContent = data.annualCost.toFixed(2);
                        if ({{$years}} >= 1) {
                            document.getElementById('discountedBase').textContent = data.discountedBase.toFixed(2);
                        }

                        resultDiv.classList.remove('hidden');
                        errorDiv.classList.add('hidden');
                    } else if (data.errors) {
                        let errorMessage = '';
                        for (let key in data.errors) {
                            errorMessage += data.errors[key].join('<br>') + '<br>';
                        }
                        errorDiv.innerHTML = errorMessage;
                        errorDiv.classList.remove('hidden');
                    } else {
                        alert('Calculation failed');
                    }
                });
        });
    </script>

</body>

</html>