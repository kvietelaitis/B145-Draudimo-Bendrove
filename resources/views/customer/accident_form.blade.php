<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registruoti įvykį</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans min-h-screen p-8">
    <h2 class="text-2xl font-medium pb-5">Registruoti įvykį:</h2>

    <div class="border border-gray-200 rounded-lg p-6 shadow-sm space-y-4">
        <form id="accident-registration-form" class="space-y-4">
            @csrf
            <div>
                <label class="block-mb-1 font-medium" for="insurance_contract">
                    Pasirinkitę sutartį
                </label>

            </div>
        </form>
    </div>
</body>