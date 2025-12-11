<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Klientų prašymai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8">

    <header class="flex justify-between items-center p-4 bg-white-500 text-black">
        <div>
            <a href="{{ route('worker.dashboard') }}" class="text-4xl font-semibold hover:underline">
                Darbuotojų Portalas
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

    <main class="max-w-6xl mx-auto mt-8">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <div class="space-y-4">
                <h1 class="text-2xl font-medium pb-5">Vartotojų prašymai</h1>

                @forelse ($requests as $request)
                <div
                    class="border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div class="flex-1">
                        <p><strong>Klientas:</strong> {{ $request->vartotojas->vardas }} {{
                            $request->vartotojas->pavarde }}</p>
                        <p><strong>Polisas:</strong> {{ $request->paketas->draudimoPolisas->pavadinimas }}</p>
                        <p><strong>Data:</strong> {{ $request->data }}</p>
                        <p><strong>Būkle:</strong> {{ $request->bukle }}</p>
                    </div>

                    <div class="flex flex-col space-y-2 sm:self-center items-center">
                        <form action="{{ route('worker.request.edit', $request->id) }}" method="GET">
                            <button type="submit"
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                Koreguoti
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-600">Nėra jokių įvykių.</p>
                @endforelse

                {{ $requests->links() }}
            </div>
        </div>
    </main>

</body>

</html>