<?php

namespace App\Support\School;

use App\Enums\Employees\EmployeeType;

class EmployeeData
{
    public static function fromForm(array $form, EmployeeType $type): array
    {
        $data = collect($form)
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();

        $data['type'] = $type->value;
        $data['name'] = trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? ''));
        $data['contact_number'] = $data['phone'] ?? null;
        $data['province'] = null;
        $data['district'] = null;
        $data['village'] = null;
        $data['salary'] = $data['base_salary'] ?? 0;

        if (($data['job_title'] ?? null) === '__custom') {
            $data['job_title'] = $data['custom_job_title'] ?: 'staff';
        }

        if ($type === EmployeeType::Teacher) {
            $data['job_title'] = ($data['job_title'] ?? null) ?: 'teacher';
            $data['department'] = ($data['department'] ?? null) ?: 'education';
        }

        return $data;
    }
}
