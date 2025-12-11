<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paketo pasirinkimas</title>
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
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Atsijungti
                </button>
            </form>
        </div>
    </header>

    <div class="max-w-6xl mx-auto bg-white border border-gray-300 rounded-xl shadow-lg p-8 space-y-8">

        <h1 class="text-2xl font-semibold mt-4">Paketo pasirinkimas: {{ $policy->pavadinimas }}</h1>
        <p class="text-gray-600 mb-6">{{ $policy->apibudinimas ?? '' }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($packages as $paketas)
            <div class="border rounded-lg p-6 bg-white shadow-sm flex flex-col h-full">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                    <h2 class="font-semibold text-lg mb-2 sm:mb-0">{{ $paketas->pavadinimas }}</h2>
                    <p class="font-semibold mt-2">Kaina: {{ number_format($paketas->total_price ?? 0, 2) }} €</p>
                </div>

                <p class="text-sm text-gray-600 mb-2">{{ $paketas->aprasymas }}</p>

                @if($paketas->paslaugos->count())
                <div class="mb-4">
                    <h3 class="font-medium text-gray-700 mb-1">Į paketą įeina:</h3>
                    <ul class="text-sm list-disc list-inside text-gray-800">
                        @foreach($paketas->paslaugos as $paslauga)
                        <li>
                            <span class="font-medium">{{ $paslauga->pavadinimas ?? $paslauga->apibudinimas }}</span>
                            <span class="text-gray-500">— {{ number_format($paslauga->kaina ?? 0, 2) }} €</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-auto pt-2">
                    <form action="{{ route('customer.packages.form', $paketas->id) }}" method="GET">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                            Pasirinkti paketą
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>