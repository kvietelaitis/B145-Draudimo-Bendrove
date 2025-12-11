<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mano įvykiai</title>
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
            <a href="{{route('customer.incidents.create')}}"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Registruoti įvykį
            </a>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Atsijungti
                </button>
            </form>
        </div>

    </header>

    <div class="space-y-6 px-60">
        <h1 class="text-2xl font-medium pb-5">Mano įvykiai</h1>

        @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-4">
            @forelse ($incidents as $incident)
            <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                <p><strong>Tipas:</strong> {{ $incident->tipas->pavadinimas }}</p>
                <p><strong>Data:</strong> {{ $incident->ivykio_data }}</p>
                <p><strong>Aprašymas:</strong> {{ $incident->apibudinimas }}</p>
                <p><strong>Būkle:</strong> {{ $incident->bukle }}</p>

                @if ($incident->nuotraukos->count())
                <div class="mt-2">
                    <strong>Nuotraukos:</strong>
                    <div class="flex gap-2">
                        @foreach ($incident->nuotraukos as $photo)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($photo->failo_vieta) }}" target="_blank"
                            rel="noopener noreferrer" class="text-sm text-gray-600 hover:underline">{{
                            $photo->failo_pavadinimas }}</a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @empty
            <p class="text-gray-600">Nėra jokių įvykių.</p>
            @endforelse
        </div>

        {{ $incidents->links() }}
    </div>
</body>

</html>