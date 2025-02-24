<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование Закупки</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 p-5">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-5">Закупка</h1>
        @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4 fade-out">
            {{ session('success') }}
        </div>
        @endif
        <div class="bg-white p-5 rounded shadow">
            <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" class="bg-white rounded">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="status" class="block mb-1 font-medium">Статус закупки</label>
                    <select name="status" id="status" class="p-2 border rounded w-full">
                        @foreach(\App\Models\Purchase::getStatusList() as $key => $status)
                        <option value="{{ $key }}" {{ $purchase->status == $key ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Сохранить изменения</button>
            </form>
            <div class="mb-4 mt-5">
                <label class="block mb-1 font-medium">Поставщик:</label>
                @if($purchase->supplier)
                <p class="p-2 border rounded bg-gray-100 w-auto">
                    {{ $purchase->supplier->name }} <br>
                </p>
                @else
                <p class="p-2 border rounded bg-gray-100 w-auto text-red-500">Поставщик не указан</p>
                @endif
            </div>
            <div class="mb-4 mt-5">
                <label class="block mb-1 font-medium">Дата создания закупки:</label>
                <p class="p-2 border rounded bg-gray-100 w-auto">{{ $purchase->created_at->format('d.m.Y') }}</p>
            </div>
            <div class="overflow-y-auto bg-white rounded-lg">
                <div class="mb-5">
                    <h2 class="text-2xl font-bold mb-3 mt-3">Список запчастей</h2>

                    @if($purchase->parts->isNotEmpty())
                    <div class="overflow-x-auto bg-white shadow rounded">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left">Название запчасти</th>
                                    <th class="px-4 py-2 text-left">Артикул</th>
                                    <th class="px-4 py-2 text-left">Количество</th>
                                    <th class="px-4 py-2 text-left">Цена</th>
                                    <th class="px-4 py-2 text-left">Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalCost = 0; @endphp
                                @foreach($purchase->parts as $part)
                                @php
                                $sum = $part->pivot->quantity * $part->pivot->price;
                                $totalCost += $sum;
                                @endphp
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $part->name }}</td>
                                    <td class="px-4 py-2">{{ $part->article }}</td>
                                    <td class="px-4 py-2">{{ $part->pivot->quantity }}</td>
                                    <td class="px-4 py-2">{{ number_format($part->pivot->price, 2) }} ₽</td>
                                    <td class="px-4 py-2">{{ number_format($sum, 2) }} ₽</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100 font-bold">
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-right">Общая стоимость:</td>
                                    <td class="px-4 py-2">{{ number_format($totalCost, 2) }} ₽</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <p class="text-red-500">Запчасти не найдены для этой закупки.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
