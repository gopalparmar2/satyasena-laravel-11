<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $existRole = Role::where('name', 'super-admin')->first();

        if (!$existRole) {
            $role = Role::create([
                'name'          => Str::slug('Super Admin', "-"),
                'display_name'  => 'Super Admin',
                'guard_name'    => 'web',
                'status'        => 1,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => null
            ]);
        } else {
            $role = $existRole;
        }

        if (!is_null($role)) {
            $existSuperAdmin = User::role('super-admin')->first();

            if (!$existSuperAdmin) {
                $user = User::create([
                    'name' => 'Super Admin',
                    'email' => 'super@gmail.com',
                    'mobile_number' => 9876543210,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    'password' => bcrypt('12345678'),
                    'status' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => null,
                ]);
            } else {
                $user = $existSuperAdmin;
            }

            $permissionsArr = [
                'User List', 'User Add', 'User Edit', 'User Delete',
                'Caste List', 'Caste Add', 'Caste Edit', 'Caste Delete',
                'Role List', 'Role Add', 'Role Edit', 'Role Delete',
                'Permission List', 'Permission Add', 'Permission Edit', 'Permission Delete',
                'State List', 'State Add', 'State Edit', 'State Delete',
                'District List', 'District Add', 'District Edit', 'District Delete',
                'Assembly Constituency List', 'Assembly Constituency Add', 'Assembly Constituency Edit', 'Assembly Constituency Delete',
                'Village List', 'Village Add', 'Village Edit', 'Village Delete',
                'Profession List', 'Profession Add', 'Profession Edit', 'Profession Delete',
                'Religion List', 'Religion Add', 'Religion Edit', 'Religion Delete',
                'Category List', 'Category Add', 'Category Edit', 'Category Delete',
                'Booth List', 'Booth Add', 'Booth Edit', 'Booth Delete',
                'Education List', 'Education Add', 'Education Edit', 'Education Delete',
                'Mandal List', 'Mandal Add', 'Mandal Edit', 'Mandal Delete',
                'Pincode List', 'Pincode Add', 'Pincode Edit', 'Pincode Delete',
                'Relationship List', 'Relationship Add', 'Relationship Edit', 'Relationship Delete',
                'Zila List', 'Zila Add', 'Zila Edit', 'Zila Delete',
                'Blood Group List', 'Blood Group Add', 'Blood Group Edit', 'Blood Group Delete',
            ];

            if (!empty($permissionsArr)) {
                foreach ($permissionsArr as $pname) {
                    $existPermission = Permission::where('name', Str::slug($pname, "-"))->first();

                    if (!$existPermission) {
                        $permission = Permission::create([
                            'name'          => Str::slug($pname, "-"),
                            'display_name'  => $pname,
                            'guard_name'    => 'web',
                            'status'        => 1,
                            'created_at'    => date("Y-m-d H:i:s"),
                            'updated_at'    => null
                        ]);

                        if (!$role->hasPermissionTo(Str::slug($pname, "-"))) {
                            $role->givePermissionTo($permission);
                        }
                    }

                }
            }

            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        }
    }
}
