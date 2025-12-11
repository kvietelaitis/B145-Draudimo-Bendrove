<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mano pasiūlymai</title>
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

    <div class="space-y-6">
        <h1 class="text-2xl font-medium pb-5">Mano pasiūlymai</h1>

        @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <div class="space-y-4">
                @forelse ($offers as $offer)
                <div
                    class="border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div class="flex-1">
                        <p><strong>Tipas:</strong> {{ $offer->paketas->pavadinimas }}</p>
                        <p><strong>Aprašymas:</strong> {{ $offer->paketas->aprasymas }}</p>
                        <p><strong>Data:</strong> {{ $offer->sukurimo_data }}</p>
                        <p><strong>Koreguota kaina:</strong> {{ $offer->koreguota_kaina }}</p>
                    </div>

                    <div class="flex flex-col space-y-2 sm:self-center items-center">
                        <form action="{{ '/wip' }}" method="GET">
                            <button type="submit"
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                Detaliau
                            </button>
                        </form>
                    </div>
                    <div class="flex flex-col space-y-2 sm:self-center items-center">
                        <form action="{{ '/wip' }}" method="GET">
                            <button type="submit"
                                class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                Priimti
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-600">Nėra jokių įvykių.</p>
                @endforelse
            </div>

            {{ $offers->links() }}
        </div>
    </div>
</body>

</html>