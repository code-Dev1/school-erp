<?php

namespace App\Services\Finance;

use App\Models\FeeAlert;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use Illuminate\Support\Facades\DB;

class RecordFeePayment
{
    public function create(array $data): FeePayment
    {
        return DB::transaction(function () use ($data): FeePayment {
            $feeStructure = FeeStructure::query()->findOrFail($data['fee_structure_id']);
            $months = max(1, (int) ($data['months_count'] ?? 1));
            $amount = (float) ($data['amount'] ?? 0) ?: ((float) $feeStructure->amount * $months);
            $paid = (float) ($data['amount_paid'] ?? $data['paid_amount'] ?? 0);
            $discount = (float) ($data['discount_amount'] ?? 0);
            $remaining = max(0, $amount - $discount - $paid);

            $payment = FeePayment::create([
                'student_id' => $data['student_id'],
                'fee_structure_id' => $feeStructure->id,
                'academic_year_id' => $data['academic_year_id'] ?? $feeStructure->academic_year_id,
                'amount' => $amount,
                'amount_paid' => $paid,
                'paid_amount' => $paid,
                'discount_amount' => $discount,
                'remaining_amount' => $remaining,
                'due_date' => $data['due_date'] ?? null,
                'payment_date' => $data['payment_date'],
                'covers_from' => $data['covers_from'] ?? null,
                'covers_to' => $data['covers_to'] ?? null,
                'months_count' => $months,
                'status' => $data['status'],
                'receipt_number' => $data['receipt_number'],
                'recorded_by' => $data['recorded_by'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            if ($remaining > 0 && ! empty($data['due_date'])) {
                FeeAlert::create([
                    'student_id' => $payment->student_id,
                    'fee_structure_id' => $payment->fee_structure_id,
                    'fee_payment_id' => $payment->id,
                    'due_amount' => $remaining,
                    'due_date' => $data['due_date'],
                    'status' => 'open',
                    'message' => $data['note'] ?? null,
                ]);
            }

            return $payment;
        });
    }
}
