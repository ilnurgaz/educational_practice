<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::all();

        $query = Purchase::with('supplier');

        // Фильтр по поставщику
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Фильтр по диапазону дат
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('purchase_date', [$request->date_from, $request->date_to]);
        }

        $purchases = $query->paginate(20);

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function selectSupplier()
    {
        $suppliers = Supplier::all();
        return view('purchases.select_supplier', compact('suppliers'));
    }

    // Обработка выбора поставщика и редирект
    public function chooseSupplier(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        return redirect()->route('purchases.createWithSupplier', $request->supplier_id);
    }

    // Отображение формы создания закупки с выбранным поставщиком
    public function createPurchase($supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);
        $parts = $supplier->parts()->withPivot('price')->get();

        return view('purchases.create_purchase', compact('supplier', 'parts'));
    }

    public function store(Request $request)
{
    // Валидация данных
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'parts' => 'required|array',
        'parts.*.quantity' => 'nullable|integer|min:1',
    ]);

    // Фильтруем запчасти с количеством больше 0
    $parts = $request->input('parts', []);
    $filteredParts = array_filter($parts, function ($data) {
        return isset($data['quantity']) && $data['quantity'] > 0;
    });

    // Проверяем, выбрана ли хотя бы одна запчасть
    if (empty($filteredParts)) {
        return back()->withErrors(['Не выбрано ни одной запчасти с количеством больше 0'])->withInput();
    }

    $purchase = Purchase::create([
        'supplier_id' => $request->supplier_id,
        'purchase_date' => now(), // Используем правильное имя поля
    ]);

    // Привязываем запчасти к закупке
    foreach ($filteredParts as $supplierPartId => $data) {
        $purchase->parts()->attach($supplierPartId, [
            'quantity' => $data['quantity'],
            'price' => $data['price'] ?? 0, // Убедись, что цена передается
        ]);
    }

    // Перенаправление с уведомлением об успешном создании
    return redirect()->route('purchases.index')->with('success', 'Закупка успешно создана');
}

public function edit($id)
{
    $purchase = Purchase::with('parts')->findOrFail($id); // Загружаем запчасти вместе с закупкой
    $statuses = ['Ожидает', 'В процессе', 'Завершено']; // Примерные статусы
    return view('purchases.edit', compact('purchase', 'statuses'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|integer',
    ]);

    $purchase = Purchase::findOrFail($id);
    $oldStatus = $purchase->status;

    $purchase->status = $request->status;

    // Обновляем дату при смене статуса на "Завершена"
    if ($oldStatus != $request->status && $request->status == 2) { // Предполагаем, что 2 — это "Завершена"
        $purchase->updated_at = Carbon::now();
    }

    $purchase->save();

    return redirect()->route('purchases.edit', $purchase->id)->with('success', 'Статус закупки обновлён');
}
    
}