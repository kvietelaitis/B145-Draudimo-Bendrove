<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administracijos portalas</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8">

    <header class="flex justify-between items-center p-4 bg-white-500 text-black mb-10">
        <div>
            <h1 class="text-4xl font-semibold">Administracijos portalas</h1>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                Atsijungti
            </button>
        </form>

    </header>

    <div class="max-w-6xl mx-auto bg-white border border-gray-300 rounded-xl shadow-lg p-8 space-y-8">
        <div class="border border-gray-200 rounded-lg p-6 mb-8 shadow-sm">
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif
            <h2 class="text-xl font-medium mb-4">Sukurti naujo darbuotojo paskyrą:</h2>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="register-worker" action="/register-worker" method="POST" class="space-y-4">
                @csrf
                <input name="worker_name" type="text" placeholder="Name"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-300">
                <input name="worker_lastname" type="text" placeholder="Lastname"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-300">
                <select name="role" id="role"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="darbuotojas" id="worker">Darbuotojas</option>
                    <option value="administratorius" id="admin">Administratorius</option>
                </select>
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Registruoti</button>
            </form>
        </div>

        <div class="border border-gray-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-xl font-medium mb-4">Darbuotojai:</h2>

            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left border-b">ID</th>
                        <th class="p-3 text-left border-b">Name</th>
                        <th class="p-3 text-left border-b">Email</th>
                        <th class="p-3 text-left border-b">Role</th>
                        <th class="p-3 text-left border-b">Created</th>
                        <th class="p-3 text-left border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border-b user-id">{{ $user->id }}</td>
                        <td class="p-3 border-b user-name">{{ $user->vardas }} {{ $user->pavarde }}</td>
                        <td class="p-3 border-b user-email">{{ $user->el_pastas }}</td>
                        <td class="p-3 border-b user-role">{{ ucfirst($user->role) }}</td>
                        <td class="p-3 border-b user-created-at">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="p-3 border-b">
                            <div class="flex space-x-4">
                                <form action="/remove-worker" method="POST"
                                    onsubmit="return confirm('Ar tikrai norite pašalinti šį darbuotoją?');">
                                    @csrf
                                    <input type="hidden" name='worker_id' value="{{ $user->id }}">
                                    <button
                                        class="delete-button bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                        Pašalinti
                                    </button>
                                </form>
                                <form action="/block-worker" method="POST">
                                    @csrf
                                    <input type="hidden" name="worker_id" value="{{ $user->id }}">
                                    @if ($user->uzblokuotas == 0)
                                    <button
                                        class="block-button bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                        Užblokuoti
                                    </button>
                                    @else
                                    <button
                                        class="block-button bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                        Atblokuoti
                                    </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>