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

    <header class="sticky top-0 z-50 flex justify-between items-center p-4 bg-white text-black">
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

    <div class="max-w-6xl mx-auto bg-white border border-gray-300 rounded-xl shadow-lg p-8 space-y-8">
        <div class="border border-gray-200 rounded-lg p-6 shadow-sm space-y-4">
            <div class="space-y-6">
                <h2 class="text-2xl font-medium">Draudimo Polisai:</h2>
                <div class="flex space-x-6 overflow-x-auto pb-2">
                    @foreach($insurancePolicies as $insurancePolicy)
                    <div
                        class="w-80 h-64 flex-shrink-0 border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition bg-white flex flex-col justify-between items-center">
                        <div class="mb-4 w-full">
                            <h3 class="text-lg font-semibold mb-2">{{ $insurancePolicy->pavadinimas }}</h3>
                            <p class="text-gray-600 mb-2">{{ $insurancePolicy->apibudinimas }}</p>
                            @if(!empty($insurancePolicy->salygos['apima']))
                            <p class="text-sm text-gray-500">Privalumai: {{ implode(', ',
                                $insurancePolicy->salygos['apima']) }}
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
        </div>

        <div class="border border-gray-200 rounded-lg p-6 shadow-sm space-y-4">
            <h2 class="text-2xl font-medium">Lizingo skaičiuoklė:</h2>
            <form action="/calculate-lease" method="POST" id="lease-calculation-form" class="space-y-4">
                @csrf

                <input type="hidden" name="years_since_accident" value="{{ floor($years) }}">

                <div>
                    <label class="block mb-1 font-medium" for="category">Pasirinkite kategoriją:</label>
                    <select name="category" id="category"
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                        <option value="--">--</option>
                        <option value="car" {{ old('category')=='car' ? 'selected' : '' }}>Automobilis</option>
                        <option value="machinery" {{ old('category')=='machinery' ? 'selected' : '' }}>Technika</option>
                        <option value="electronics" {{ old('category')=='electronics' ? 'selected' : '' }}>Elektronika
                        </option>
                        <option value="furniture" {{ old('category')=='furniture' ? 'selected' : '' }}>Baldai</option>
                        <option value="housing" {{ old('category')=='housing' ? 'selected' : '' }}>Būstas</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium" for="initial_cost">Įveskite pradinę kainą:</label>
                    <input type="number" name="initial_cost" id="initial_cost" value="{{ old('initial_cost') }}"
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="block mb-1 font-medium" for="market_value">Įveskite rinkos kainą:</label>
                    <input type="number" name="market_value" id="market_value" value="{{ old('market_value') }}"
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="block mb-1 font-medium" for="remaining_lease">Įveskite lizingo likutį:</label>
                    <input type="number" name="remaining_lease" id="remaining_lease"
                        value="{{ old('remaining_lease') }}"
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="block mb-1 font-medium" for="insurance_type">Koks draudimo tipas?</label>
                    <select name="insurance_type" id="insurance_type"
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
                        <option value="--">--</option>
                        <option value="full" {{ old('insurance_type')=='full' ? 'selected' : '' }}>Pilnas</option>
                        <option value="replacement" {{ old('insurance_type')=='replacement' ? 'selected' : '' }}>
                            Pakeitimas</option>
                        <option value="damage" {{ old('insurance_type')=='damage' ? 'selected' : '' }}>Žala/Remontas
                        </option>
                    </select>
                </div>

                <button type="submit" id="calculate-button"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Apskaičiuoti</button>
            </form>

            @if(session('calculation_results'))
            <div class="mt-4 p-4 border border-gray-200 rounded bg-gray-50">
                <h3 class="font-medium text-lg mb-2">Rezultatai</h3>
                <p><strong>Mėnesinė įmoka:</strong> €{{ session('calculation_results')['baseCost'] }}</p>
                <p><strong>Metinė įmoka:</strong> €{{ session('calculation_results')['annualCost'] }}</p>
                @if($years >= 1)
                <p><strong>Mėnesinė įmoka (Su nuolaida):</strong> €{{ session('calculation_results')['discountedBase']
                    }}</p>
                @endif
            </div>
            @endif

            <!-- Display Validation Errors -->
            @if ($errors->any())
            <div class="mt-4 text-red-600 p-4 border border-red-200 rounded bg-red-50">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div id="errorResult" class="hidden mt-4 text-red-600"></div>
    </div>

    <div
        class="border border-dashed border-blue-400 rounded-lg p-6 bg-blue-50 flex flex-col items-center justify-center mt-4">
        <span class="text-lg font-semibold text-blue-700 mb-2">Jūsų pakvietimo kodas:</span>
        <span class="text-2xl font-mono text-blue-900 tracking-widest">{{ $referralCode }}</span>
    </div>

</body>

</html>