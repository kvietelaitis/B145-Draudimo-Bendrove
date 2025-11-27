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
            <h1 class="text-4xl font-semibold">Darbuotojų portalas</h1>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                Atsijungti
            </button>
        </form>

    </header>
</body>

</html>