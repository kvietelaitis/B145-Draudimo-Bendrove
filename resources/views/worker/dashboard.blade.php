<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Darbuotojų portalas</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8">
    <header class="flex justify-between items-center p-4 bg-white-500 text-black mb-10">
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

    <main class="max-w-6xl mx-auto mt-8">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <div class="space-y-6 px-60">
                <h2 class="text-2xl font-medium">Placeholder nes bbzn</h2>

                <div class="grid grid-cols-1 gap-6">
                    <div
                        class="border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition bg-white flex flex-col justify-between">
                        <div class="mb-4">
                            <h3 class="text-xl font-semibold mb-2">Klientų įvykiai</h3>
                        </div>
                        <form action="{{ route('worker.incidents.index') }}" method="GET">
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition w-full">
                                Eiti
                            </button>
                        </form>
                    </div>

                    <div
                        class="border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition bg-white flex flex-col justify-between">
                        <div class="mb-4">
                            <h3 class="text-xl font-semibold mb-2">Prašymai</h3>
                        </div>
                        <form action="{{ route('worker.requests.index') }}" method="GET">
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition w-full">
                                Eiti
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </main>
</body>

</html>