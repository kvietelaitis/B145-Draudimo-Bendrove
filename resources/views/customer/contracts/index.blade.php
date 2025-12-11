<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mano sutartys</title>
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
        <h1 class="text-2xl font-medium pb-5">Mano sutartys</h1>

        @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <div class="space-y-4">
                @forelse ($contracts as $contract)
                <div
                    class="border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div class="flex-1">
                        <p><strong>Polisas:</strong> {{ $contract->paketas->draudimoPolisas->pavadinimas}}</p>
                        <p><strong>Pradžios data:</strong> {{ $contract->isigaliojimo_data }}</p>
                        <p><strong>Galiojimo pabaigos data:</strong> {{ $contract->galiojimo_pabaigos_data }}</p>
                        <p><strong>Būklė:</strong> {{ $contract->bukle }}</p>

                    </div>
                    @if ($contract->bukle !== 'atsaukta')
                    <div class="flex flex-col space-y-2 sm:self-center items-center">
                        <form action="/customer/contracts/cancel" method="POST">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                            <button type="submit"
                                class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                Atšaukti
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-gray-600">Nėra jokių sutarčių.</p>
                @endforelse
            </div>

            {{ $contracts->links() }}
        </div>
    </div>
</body>

</html>