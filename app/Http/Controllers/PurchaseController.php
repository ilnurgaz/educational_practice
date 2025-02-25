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

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

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

    public function chooseSupplier(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        return redirect()->route('purchases.createWithSupplier', $request->supplier_id);
    }

    public function createPurchase($supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);
        $parts = $supplier->parts()->withPivot('price')->get();

        return view('purchases.create_purchase', compact('supplier', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'parts' => 'required|array',
            'parts.*.quantity' => 'nullable|integer|min:1',
        ]);

        $parts = $request->input('parts', []);
        $filteredParts = array_filter($parts, function ($data) {
            return isset($data['quantity']) && $data['quantity'] > 0;
        });

        if (empty($filteredParts)) {
            return back()->withErrors(['Не выбрано ни одной запчасти с количеством больше 0'])->withInput();
        }

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => now(), 
        ]);

        foreach ($filteredParts as $supplierPartId => $data) {
            $purchase->parts()->attach($supplierPartId, [
                'quantity' => $data['quantity'],
                'price' => $data['price'] ?? 0, 
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Закупка успешно создана');
    }

    public function edit($id)
    {
        $purchase = Purchase::with('parts')->findOrFail($id); 
        $statuses = ['Ожидает', 'В процессе', 'Завершено']; 
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

        if ($oldStatus != $request->status && $request->status == 2) { 
            $purchase->updated_at = Carbon::now();
        }

        $purchase->save();

        return redirect()->route('purchases.edit', $purchase->id)->with('success', 'Статус закупки обновлён');
    }
    
}