<?php

namespace App\Services\Sales;

use App\Models\SaleItem;
use App\Models\StudentSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateStudentSale
{
    public function create(array $data, array $lines): StudentSale
    {
        return DB::transaction(function () use ($data, $lines): StudentSale {
            $subtotal = 0;
            $preparedLines = [];

            foreach ($lines as $line) {
                $quantity = max(1, (int) $line['quantity']);
                $item = SaleItem::query()->lockForUpdate()->findOrFail($line['sale_item_id']);

                if ($item->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'form.quantity' => 'Selected item does not have enough stock.',
                    ]);
                }

                $lineTotal = (float) $item->unit_price * $quantity;
                $subtotal += $lineTotal;

                $preparedLines[] = [$item, $quantity, (float) $item->unit_price, $lineTotal];
            }

            $discount = (float) ($data['discount_amount'] ?? 0);
            $total = max(0, $subtotal - $discount);
            $paid = (float) ($data['paid_amount'] ?? $total);
            $balance = max(0, $total - $paid);

            $sale = StudentSale::create([
                'student_id' => $data['student_id'] ?: null,
                'invoice_number' => $data['invoice_number'],
                'sold_at' => $data['sold_at'],
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'paid_amount' => $paid,
                'balance_amount' => $balance,
                'status' => $data['status'],
                'recorded_by' => $data['recorded_by'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            foreach ($preparedLines as [$item, $quantity, $unitPrice, $lineTotal]) {
                $sale->lines()->create([
                    'sale_item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ]);

                $item->decrement('stock_quantity', $quantity);
            }

            return $sale;
        });
    }
}
