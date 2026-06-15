<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeType;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SchoolLookupSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::firstOrCreate(
            ['name' => '1405'],
            [
                'starts_at' => '2026-03-21',
                'ends_at' => '2027-03-20',
                'is_active' => true,
            ]
        );

        foreach (range(1, 12) as $grade) {
            $class = AcademicClass::firstOrCreate(
                ['name' => 'صنف '.$grade],
                [
                    'grade_level' => $grade,
                    'is_active' => true,
                ]
            );

            foreach (['الف', 'ب'] as $sectionName) {
                Section::firstOrCreate(
                    ['class_id' => $class->id, 'name' => $sectionName],
                    [
                        'code' => $grade.'-'.$sectionName,
                        'capacity' => 35,
                        'is_active' => true,
                    ]
                );
            }
        }

        foreach (['قرآن کریم', 'دری', 'پشتو', 'ریاضی', 'ساینس', 'انگلیسی', 'تاریخ', 'جغرافیه'] as $subject) {
            Subject::firstOrCreate(['name' => $subject], ['is_active' => true]);
        }

        foreach (['فیس ماهانه', 'فیس ترانسپورت', 'فیس امتحان'] as $feeType) {
            FeeType::firstOrCreate(['name' => $feeType]);
        }
    }
}
