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
        <form action="/create-incidents" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-medium" for="incident_type">
                    Pasirinkite draudimo įvykio tipą:
                </label>

                <select name="incident_type" id="incident_type"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300" required>
                    <option value="">-- Tipas --</option>

                    @foreach ($incidentTypes as $incidentType)
                    <option value="{{ $incidentType->id }}">
                        {{ $incidentType->pavadinimas ?? 'Įvykio tipas Nr. ' . $incidentType->id }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium" for="insurance_contract">
                    Pasirinkitę sutartį:
                </label>

                <select name="insurance_contract" id="insurance_contract"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300" required>
                    <option value="">-- Sutartis --</option>

                    @foreach ($contracts as $contract)
                    <option value="{{ $contract->id }}">
                        {{ $contract->paketas->pavadinimas ?? 'Sutartis Nr. ' . $contract->id }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium" for="incident_date">
                    Įveskite įvykio datą:
                </label>
                <input type="date" name="incident_date" id="incident_date"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block mb-1 font-medium" for="incident_description">
                    Įvykio apibūdinimas:
                </label>
                <textarea name="incident_description" id="incident_description" rows="4"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-300"></textarea>
            </div>

            <div>
                <label for="photos" class="block mb-1 font-medium">
                    Nuotraukos:
                </label>
                <input type="file" name="photos[]" id=photos multiple accept="image/*"
                    class="border border-gray-300 rounded px-3 py-2 w-full">
                <small class="text-gray-500">Max 5MB per file, JPEG/PNG/GIF</small>
                @error('photos')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                @error('photos.*')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <button type="submit" id="submit-button"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Pateikti</button>
        </form>
    </div>
</body>