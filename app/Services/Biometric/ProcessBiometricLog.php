<?php

namespace App\Services\Biometric;

use App\Enums\Biometric\AttendanceStatus;
use App\Enums\Biometric\BiometricLogType;
use App\Models\AttendanceSummary;
use App\Models\BiometricLog;
use App\Models\Employee;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProcessBiometricLog
{
    public function handle(BiometricLog $log): ?AttendanceSummary
    {
        $person = $log->person ?: $this->personForUid((int) $log->biometric_uid);

        if (! $person) {
            return null;
        }

        $checkTime = $log->check_time ?: $log->timestamp;
        $type = $log->log_type instanceof BiometricLogType ? $log->log_type->value : (string) $log->log_type;

        return DB::transaction(function () use ($log, $person, $checkTime, $type): AttendanceSummary {
            $log->forceFill([
                'person_id' => $person->getKey(),
                'person_type' => $person::class,
                'check_time' => $checkTime,
                'check_type' => $type,
                'synced_at' => now(),
            ])->save();

            $date = $checkTime->toDateString();
            $time = $checkTime->format('H:i:s');

            $summary = AttendanceSummary::firstOrNew([
                'person_id' => $person->getKey(),
                'person_type' => $person::class,
                'date' => $date,
            ]);

            $summary->status = $summary->status ?: AttendanceStatus::Present;

            if ($type === BiometricLogType::CheckIn->value) {
                $summary->check_in = $summary->check_in
                    ? min($summary->check_in, $time)
                    : $time;
            }

            if ($type === BiometricLogType::CheckOut->value) {
                $summary->check_out = $summary->check_out
                    ? max($summary->check_out, $time)
                    : $time;
            }

            $summary->save();

            return $summary;
        });
    }

    private function personForUid(int $uid): ?Model
    {
        return Student::query()->where('biometric_uid', $uid)->first()
            ?: Employee::query()->where('biometric_uid', $uid)->first();
    }
}
