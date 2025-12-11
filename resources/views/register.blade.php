<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registracija</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-8">

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-center">Registracija</h2>

                @if($errors->any())
                <div class="mb-4 text-red-600">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="/register-user" method="POST" class="space-y-4">
                    @csrf
                    <input name="vardas" type="text" placeholder="Vardas"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300">
                    <input name="pavarde" type="text" placeholder="Pavardė"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300">
                    <input name="el_pastas" type="email" placeholder="El. paštas"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300">
                    <input name="slaptazodis" type="password" placeholder="Slaptažodis"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300">
                    <button type="submit"
                        class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Registruotis</button>
                </form>
                <br>
                Turite paskyrą?<a href="/"> <u>Prisijunkite</u></a>
            </div>
        </div>
    </div>
</body>

</html>