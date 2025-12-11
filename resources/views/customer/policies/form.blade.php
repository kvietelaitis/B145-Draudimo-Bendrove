<!-- filepath: /home/stud/projektas/B145-Draudimo-Bendrove/resources/views/customer/policies/form.blade.php -->
<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Užpildyti informaciją</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans min-h-screen p-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Draudimo informacija</h1>
        <p class="mb-6 text-gray-600">
            Pasirinktas paketas: <span class="font-semibold">{{ $paketas->pavadinimas }}</span>
        </p>

        <form action="{{ '/customer/policies/submit' }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="paketas_id" value="{{ $paketas->id }}">

            @php
            $fields = $paketas->draudimoPolisas->form_fields ?? [
            ['name' => 'papildoma_info', 'label' => 'Papildoma informacija', 'type' => 'textarea', 'required' => true]
            ];
            @endphp

            @foreach($fields as $field)
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>

                @if($field['type'] === 'textarea')
                <textarea name="{{ $field['name'] }}" {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                @else
                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" {{ ($field['required'] ?? false)
                    ? 'required' : '' }} class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                @endif
            </div>
            @endforeach

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Pateikti prašymą
                </button>
            </div>
        </form>
    </div>
</body>

</html>