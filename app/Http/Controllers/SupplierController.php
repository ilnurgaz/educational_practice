<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        $query = Supplier::query();

        // Если есть запрос на поиск
        if ($request->has('search') && $request->input('search') !== '') {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Пагинация по 20 элементов, после применения фильтра
        $suppliers = $query->paginate(20);

        return view('suppliers.index', compact('suppliers'));
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


    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Поставщик удалён!');
    }


    
}