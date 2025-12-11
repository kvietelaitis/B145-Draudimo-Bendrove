<!-- filepath: /home/stud/projektas/B145-Draudimo-Bendrove/resources/views/customer/offers/details.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pasiūlymo detalės</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8">

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

    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-8 space-y-6">
        <h1 class="text-2xl font-bold mb-4">Pasiūlymo detalės</h1>

        <div class="mb-4">
            <h2 class="text-lg font-semibold mb-2">Pasiūlymo informacija</h2>
            <p><strong>Data:</strong> {{ $offer->sukurimo_data }}</p>
            <p><strong>Koreguota kaina:</strong> {{ number_format($offer->koreguota_kaina, 2) }} €</p>
            <p><strong>Būsena:</strong> {{ ucfirst($offer->bukle) }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold mb-2">Paketo informacija</h2>
            <p><strong>Polisas:</strong> {{ $offer->paketas->draudimoPolisas->pavadinimas ?? '-' }}</p>
            <p><strong>Paketo pavadinimas:</strong> {{ $offer->paketas->pavadinimas ?? '-' }}</p>
            <p><strong>Aprašymas:</strong> {{ $offer->paketas->aprasymas ?? '-' }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold mb-2">Paslaugos</h2>
            @if($offer->paketas->paslaugos->count())
            <ul class="list-disc list-inside">
                @foreach($offer->paketas->paslaugos as $paslauga)
                <li>
                    {{ $paslauga->pavadinimas ?? $paslauga->apibudinimas }} —
                    {{ number_format($paslauga->kaina ?? 0, 2) }} €
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-600">Nėra paslaugų</p>
            @endif
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold mb-2">Kliento informacija</h2>
            <p><strong>Vardas:</strong> {{ $offer->vartotojas->vardas ?? '-' }}</p>
            <p><strong>Pavardė:</strong> {{ $offer->vartotojas->pavarde ?? '-' }}</p>
            <p><strong>El. paštas:</strong> {{ $offer->vartotojas->el_pastas ?? '-' }}</p>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('customer.offers.index') }}"
                class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 text-gray-800">
                Grįžti į pasiūlymus
            </a>
            <form action="/customer/offers/accept-offer" method="POST">
                @csrf
                <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    Priimti pasiūlymą
                </button>
            </form>
        </div>
    </div>
</body>

</html>