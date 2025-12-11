<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Klientų įvykiai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8 space-y-8">
    <header class="sticky top-0 z-50 flex justify-between items-center p-4 bg-white text-black">
        <div>
            <a href="{{ route('worker.dashboard') }}" class="text-4xl font-semibold hover:underline">
                Darbuotojų portalas
            </a>
        </div>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                Atsijungti
            </button>
        </form>
    </header>
    <main>
        <div class="max-w-2xl mx-auto bg-white border border-gray-300 rounded-xl shadow-lg p-8 space-y-8">
            <div class="space-y-6">
                <h1 class="text-2xl font-medium pb-5">Vartotojų įvykiai</h1>

                @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('worker.incidents.index') }}" method="GET"
                    class="flex flex-col sm:flex-row items-stretch gap-2">
                    <input type="text" name="name" value="{{ request('name') }}"
                        placeholder="Ieškoti pagal vardą ar pavardę"
                        class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300 flex-1">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Filtruoti
                    </button>
                    @if(request('name'))
                    <a href="{{ route('worker.incidents.index') }}"
                        class="text-sm text-gray-600 underline flex items-center px-2">
                        Išvalyti
                    </a>
                    @endif
                </form>

                <div class="space-y-4">
                    @forelse ($incidents as $incident)
                    <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                        <p><strong>Klientas:</strong> {{ $incident->vartotojas->vardas }} {{
                            $incident->vartotojas->pavarde }}
                        </p>
                        <p><strong>Tipas:</strong> {{ $incident->tipas->pavadinimas }}</p>
                        <p><strong>Data:</strong> {{ $incident->ivykio_data }}</p>
                        <p><strong>Aprašymas:</strong> {{ $incident->apibudinimas }}</p>
                        <p><strong>Būkle:</strong> {{ $incident->bukle }}</p>

                        @if ($incident->nuotraukos->count())
                        <div class="mt-2">
                            <strong>Nuotraukos:</strong>
                            <div class="flex gap-2">
                                @foreach ($incident->nuotraukos as $photo)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($photo->failo_vieta) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="text-sm text-gray-600 hover:underline">{{
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
        </div>
    </main>
</body>

</html>