<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function getSalesData(Request $request)
{
    // Получаем даты из запроса (если они есть)
    $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
    $endDate = $request->input('end_date', Carbon::now()->toDateString());

    // Получаем данные о количестве покупок и сумме закупок за выбранный период
    $salesData = DB::table('purchases')
        ->leftJoin('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
        ->select(
            DB::raw('DATE(purchases.purchase_date) as date'),
            DB::raw('COUNT(DISTINCT purchases.id) as total_purchases'), // Количество покупок за день
            DB::raw('COALESCE(SUM(purchase_items.quantity * purchase_items.price), 0) as total_amount') // Сумма закупок за день
        )
        ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return response()->json($salesData);
}
}