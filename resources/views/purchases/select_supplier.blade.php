<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор поставщика</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 p-5">
    <div class="container mx-auto bg-white shadow p-4 rounded-lg">
        <h1 class="text-3xl font-bold mb-5">Выберите поставщика</h1>

        @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('purchases.chooseSupplier') }}" method="POST" class='sm:max-w-{500px}'>
            @csrf
            <div class="mb-4">
                <label for="supplier_id" class="block mb-2">Поставщик:</label>
                <select id="supplier_id" name="supplier_id" class="w-full p-2 border rounded" required>
                    <option value="">Выберите поставщика</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-300">
                Далее
            </button>
        </form>

    </div>
</body>

</html>
