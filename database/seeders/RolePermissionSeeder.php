<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        $permissions = [
            'read_students',
            'create_students',
            'update_students',
            'delete_students',
            'read_guardians',
            'create_guardians',
            'update_guardians',
            'delete_guardians',
            'read_classes',
            'create_classes',
            'update_classes',
            'delete_classes',
            'read_subjects',
            'create_subjects',
            'update_subjects',
            'delete_subjects',
            'read_teachers',
            'create_teachers',
            'update_teachers',
            'delete_teachers',
            'read_staff',
            'create_staff',
            'update_staff',
            'delete_staff',
            'read_marks',
            'create_marks',
            'update_marks',
            'delete_marks',
            'read_timetables',
            'create_timetables',
            'update_timetables',
            'delete_timetables',
            'read_payroll',
            'create_payroll',
            'update_payroll',
            'delete_payroll',
            'read_expenses',
            'create_expenses',
            'update_expenses',
            'delete_expenses',
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'manage_settings',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'roles.manage',
            'students.view',
            'students.create',
            'students.update',
            'students.delete',
            'students.transfer',
            'students.results.manage',
            'guardians.view',
            'guardians.manage',
            'teachers.manage',
            'staff.manage',
            'classes.manage',
            'subjects.manage',
            'timetables.manage',
            'attendance.students.manage',
            'attendance.teachers.manage',
            'attendance.staff.manage',
            'transport.manage',
            'fees.manage',
            'payroll.teachers.manage',
            'payroll.staff.manage',
            'expenses.manage',
            'reports.students.view',
            'reports.attendance.view',
            'reports.payroll.view',
            'reports.expenses.view',
            'announcements.manage',
            'announcements.absence.manage',
            'announcements.fee.manage',
            'parents.manage',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, $guard);
        }

        $roles = [
            'Super Admin',
            'Admin',
            'Principal',
            'Accountant',
            'Teacher',
            'Staff',
            'Viewer',
            'super-admin',
            'deputy',
            'accountant',
            'teacher',
            'registrar',
            'parent',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role, $guard);
        }

        Role::findByName('Super Admin', $guard)->givePermissionTo(Permission::all());
        Role::findByName('super-admin', $guard)->givePermissionTo(Permission::all());

        Role::findByName('Admin', $guard)->givePermissionTo(Permission::all());

        Role::findByName('Principal', $guard)->givePermissionTo([
            'read_students',
            'create_students',
            'update_students',
            'read_guardians',
            'create_guardians',
            'update_guardians',
            'read_classes',
            'create_classes',
            'update_classes',
            'read_subjects',
            'create_subjects',
            'update_subjects',
            'read_teachers',
            'create_teachers',
            'update_teachers',
            'read_staff',
            'create_staff',
            'update_staff',
            'read_marks',
            'create_marks',
            'update_marks',
            'read_timetables',
            'create_timetables',
            'update_timetables',
        ]);

        Role::findByName('Accountant', $guard)->givePermissionTo([
            'read_teachers',
            'read_staff',
            'read_payroll',
            'create_payroll',
            'update_payroll',
            'read_expenses',
            'create_expenses',
            'update_expenses',
        ]);

        Role::findByName('Teacher', $guard)->givePermissionTo([
            'read_students',
            'read_marks',
            'create_marks',
            'update_marks',
            'read_timetables',
        ]);

        Role::findByName('Staff', $guard)->givePermissionTo([
            'read_students',
            'read_guardians',
        ]);

        Role::findByName('Viewer', $guard)->givePermissionTo([
            'read_students',
            'read_guardians',
            'read_classes',
            'read_subjects',
            'read_teachers',
            'read_staff',
            'read_marks',
            'read_timetables',
            'read_payroll',
            'read_expenses',
        ]);

        Role::findByName('deputy', $guard)->givePermissionTo([
            'students.view',
            'students.create',
            'students.update',
            'students.transfer',
            'students.results.manage',
            'guardians.view',
            'guardians.manage',
            'teachers.manage',
            'staff.manage',
            'classes.manage',
            'subjects.manage',
            'timetables.manage',
            'attendance.students.manage',
            'attendance.teachers.manage',
            'attendance.staff.manage',
            'transport.manage',
            'reports.students.view',
            'reports.attendance.view',
            'announcements.manage',
            'parents.manage',
        ]);

        Role::findByName('registrar', $guard)->givePermissionTo([
            'students.view',
            'students.create',
            'students.update',
            'students.transfer',
            'students.results.manage',
            'guardians.view',
            'guardians.manage',
            'classes.manage',
            'subjects.manage',
            'parents.manage',
        ]);

        Role::findByName('teacher', $guard)->givePermissionTo([
            'students.view',
            'guardians.view',
            'students.results.manage',
            'attendance.students.manage',
            'reports.students.view',
        ]);

        Role::findByName('accountant', $guard)->givePermissionTo([
            'students.view',
            'guardians.view',
            'fees.manage',
            'payroll.teachers.manage',
            'payroll.staff.manage',
            'expenses.manage',
            'reports.payroll.view',
            'reports.expenses.view',
            'announcements.fee.manage',
        ]);

        Role::findByName('parent', $guard)->givePermissionTo([
            'students.view',
            'reports.students.view',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
