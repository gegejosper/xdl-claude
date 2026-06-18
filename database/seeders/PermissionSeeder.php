<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $permissions = collect([
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',

            'view_payment_methods',
            'create_payment_methods',
            'edit_payment_methods',
            'delete_payment_methods',

            'view_products',
            'create_product',
            'edit_product',
            'modify_product',
            
            'create_orders',
            'view_orders',
            'cancel_orders',
            'update_orders',
            'view_transactions',

            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'delete_expenses',

            'view_general_settings',
            'create_general_settings',
            'edit_general_settings',
            'view_binding_devices',
            'edit_binding_devices',
    
            'save_expense_record',
            'view_expense_lists',
            'view_expense_report',
            'delete_record_expenses', // superadmin, admin
            'view_admin_dashboard',
            'view_activity_logs',
            
            
        ])->unique();
        // 2. create permissions
        $permissions->each(function ($permission_name) {
            Permission::firstOrCreate(['name' => $permission_name]);
        });
       // get superadmin role
        $superadmin_role = Role::where('name', 'admin')->first();

        // attach ALL permissions to superadmin
        if ($superadmin_role) {
            $superadmin_role->syncPermissions(Permission::all());
        }
    }
}
