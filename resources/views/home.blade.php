<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisijungimas</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-1 gap-8">

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-center">Prisijungimas</h2>

                @if($errors->any())
                <div class="mb-4 text-red-600">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <input name="loginemail" type="email" placeholder="El. paštas"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300">
                    <input name="loginpassword" type="password" placeholder="Slaptažodis"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-300">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Prisijungti</button>
                </form>
                <a href="{{ route('register')}}">Neturite paskyros? Prisiregistruokite</a>
            </div>

        </div>

    </div>

</body>

</html>