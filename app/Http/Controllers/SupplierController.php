<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        $query = Supplier::query();

        // Фильтрация по названию
        if ($request->has('search') && $request->input('search') !== '') {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Фильтрация по запчастям
        if ($request->has('part_id') && $request->input('part_id') !== '') {
            $query->whereHas('supplierParts', function ($q) use ($request) {
                $q->where('part_id', $request->input('part_id'));
            });
        }

        // Пагинация
        $suppliers = $query->paginate(20);

        // Получаем список всех запчастей для фильтра
        $parts = \App\Models\Part::all();

        return view('suppliers.index', compact('suppliers', 'parts'));
    }

    public function store(Request $request)
    {
        try {
            // Кастомные сообщения для валидации
            $validated = $request->validate([
                'name' => 'required|unique:suppliers,name|max:255',
                'address' => 'required|max:255',
                'phone' => 'nullable|max:15',
            ], [
                'name.unique' => 'Такой поставщик уже существует', // Кастомное сообщение для уникальности
            ]);

            // Создание нового поставщика
            Supplier::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);

            // Сообщение об успешном добавлении
            return redirect()->route('suppliers.index')->with('success', 'Поставщик добавлен!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Сообщение об ошибке, если поставщик уже существует
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }


    public function edit($id)
{
    $supplier = Supplier::with('supplierParts')->findOrFail($id);
    $parts = \App\Models\Part::all();
    return view('suppliers.edit', compact('supplier', 'parts'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
        'address' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'parts' => 'array',
        'parts.*.price' => 'nullable|numeric|min:0',
    ], [
        'name.unique' => 'Поставщик с таким названием уже существует.',
    ]);

    $supplier = Supplier::findOrFail($id);

    $supplier->update([
        'name' => $request->name,
        'address' => $request->address,
        'phone' => $request->phone,
    ]);

    $syncData = [];
    foreach ($request->parts ?? [] as $partId => $data) {
        if (isset($data['selected'])) {
            $syncData[$partId] = ['price' => $data['price'] ?? 0];
        }
    }

    $supplier->parts()->sync($syncData);

    return redirect()->route('suppliers.edit', $supplier->id)->with('success', 'Поставщик успешно обновлен');
}


    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Поставщик удалён!');
    }


    
}