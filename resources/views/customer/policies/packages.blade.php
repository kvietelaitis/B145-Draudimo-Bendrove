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
    <header class="flex justify-between items-center p-4 bg-white-500 text-black">
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

    <h1 class="text-2xl font-semibold mt-4">Paketo pasirinkimas: {{ $policy->pavadinimas }}</h1>
    <p class="text-gray-600 mb-6">{{ $policy->apibudinimas ?? '' }}</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($packages as $paketas)
        <div class="border rounded p-4">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-lg">{{ $paketas->pavadinimas }}</h2>

                <p class="font-semibold mt-2">Kaina: {{ number_format($paketas->total_price ?? 0, 2) }} €</p>
            </div>

            <p class="text-sm text-gray-600 mb-2">{{ $paketas->aprasymas }}</p>

            @if($paketas->paslaugos->count())
            <ul class="text-sm mb-4">
                @foreach($paketas->paslaugos as $paslauga)
                <li>&#8226; {{ $paslauga->pavadinimas ?? $paslauga->apibudinimas }} — {{ number_format($paslauga->kaina
                    ?? 0, 2) }} €
                </li>
                @endforeach
            </ul>
            @endif

            <form action="/customer/choose-package" method="POST">
                @csrf
                <input type="hidden" name="paketas_id" value="{{ $paketas->id }}">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Pasirinkti paketą
                </button>
            </form>
        </div>
        @endforeach
    </div>
</body>

</html>