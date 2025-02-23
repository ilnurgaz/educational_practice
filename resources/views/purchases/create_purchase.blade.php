<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Добавить закупку</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-5">Добавить закупку для {{ $supplier->name }}</h1>

        @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('purchases.store') }}" onsubmit="return filterEmptyParts()">
            @csrf
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

            <h2 class="text-xl font-bold mb-2">Запчасти:</h2>
            <div class="space-y-2 mb-4">
            @foreach($supplier->parts as $part)
            <div class="flex justify-between items-center mb-2">
                <span>{{ $part->name }} (Цена: {{ $part->pivot->price }})</span>
                <div class="flex items-center space-x-2">
                    <input type="number" name="parts[{{ $part->id }}][quantity]" min="0" placeholder="Количество"
                        class="p-2 border rounded w-24">
                    <!-- Скрытое поле для передачи цены -->
                    <input type="hidden" name="parts[{{ $part->id }}][price]" value="{{ $part->pivot->price }}">
                </div>
            </div>
            @endforeach
            </div>

            <button type="submit"
                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded transition duration-300">
                Создать закупку
            </button>
        </form>

       
    </div>
    <script>
            function filterEmptyParts() {
    let hasValidPart = false;

    document.querySelectorAll('input[name*="[quantity]"]').forEach(input => {
        if (!input.value || input.value <= 0) {
            input.remove();
        } else {
            hasValidPart = true; // Найдена хотя бы одна валидная запчасть
        }
    });

    if (!hasValidPart) {
        alert('Выберите хотя бы одну запчасть с количеством больше 0.');
        return false; // Остановить отправку формы
    }

    return true; // Продолжить отправку формы
}

        </script>
</body>

</html>
