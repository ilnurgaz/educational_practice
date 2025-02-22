<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Supplier;
use App\Models\SupplierPart;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $parts = Part::query()
        ->when(request('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('article', 'like', '%' . $search . '%');
            });
        })
        ->when(request('supplier_id'), function ($query, $supplierId) {
            $query->whereHas('suppliers', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            });
        })
        ->paginate(20);

    $suppliers = Supplier::all(); // Получаем всех поставщиков

    return view('parts.index', compact('parts', 'suppliers'));
}


    /**
     * Store a newly created resource in storage.
     */


     public function store(Request $request)
     {
         try {
             // Валидация данных формы
             $validated = $request->validate([
                 'name' => 'required|string|max:255|unique:parts,name',
                 'article' => 'required|string|unique:parts,article',
             ], [
                 'name.unique' => 'Запчасть с таким названием уже существует.',
                 'article.unique' => 'Запчасть с таким артикулом уже существует.',
             ]);
     
             // Проверка только выбранных поставщиков
             $selectedSuppliers = collect($request->suppliers)->filter(function ($supplier) {
                 return isset($supplier['selected']) && $supplier['selected'];
             });
     
             if ($selectedSuppliers->isEmpty()) {
                 return redirect()->back()->withErrors(['suppliers' => 'Необходимо выбрать хотя бы одного поставщика.'])->withInput();
             }
     
             // Проверяем цену только у выбранных поставщиков
             foreach ($selectedSuppliers as $supplierId => $supplierData) {
                 if (!isset($supplierData['price']) || !is_numeric($supplierData['price']) || $supplierData['price'] <= 0) {
                     return redirect()->back()->withErrors([
                         "suppliers.$supplierId.price" => "Для выбранного поставщика требуется указать корректную цену больше 0."
                     ])->withInput();
                 }
             }
     
             // Создание новой запчасти
             $part = Part::create([
                 'name' => $validated['name'],
                 'article' => $validated['article'],
             ]);
     
             // Привязка поставщиков и цен
             foreach ($selectedSuppliers as $supplierId => $data) {
                 SupplierPart::create([
                     'supplier_id' => $supplierId,
                     'part_id' => $part->id,
                     'price' => $data['price'],
                 ]);
             }
     
             // Успешное завершение
             return redirect()->route('parts.index')->with('success', 'Запчасть и поставщики успешно добавлены!');
         } catch (\Illuminate\Validation\ValidationException $e) {
             return redirect()->back()->withErrors($e->errors())->withInput();
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Произошла ошибка при добавлении запчасти.')->withInput();
         }
     }


     public function edit($id)
     {
         $part = Part::findOrFail($id);
         $suppliers = Supplier::all();
         return view('parts.edit', compact('part', 'suppliers'));
     }
     
     public function update(Request $request, $id)
{
    // Валидация входных данных
    $request->validate([
        'name' => 'required|string|max:255',
        'article' => 'required|string|max:255',
        'suppliers' => 'array',
        'suppliers.*.price' => 'nullable|numeric|min:0',
    ]);

    // Находим запчасть по ID
    $part = Part::findOrFail($id);

    // Обновляем данные запчасти
    $part->update([
        'name' => $request->name,
        'article' => $request->article,
    ]);

    // Подготовка данных для связи с поставщиками
    $syncData = [];

    foreach ($request->suppliers ?? [] as $supplierId => $data) {
        if (isset($data['selected'])) {
            $syncData[$supplierId] = ['price' => $data['price'] ?? 0];
        }
    }

    // Сохраняем связь с поставщиками и их ценами
    $part->suppliers()->sync($syncData);

    // Перенаправляем с сообщением об успешном обновлении
    return redirect()->route('parts.index')->with('success', 'Запчасть успешно обновлена');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $part = Part::findOrFail($id);
        $part->delete();

        return redirect()->route('parts.index')->with('success', 'Запчасть успешно удалена.');
    }
}