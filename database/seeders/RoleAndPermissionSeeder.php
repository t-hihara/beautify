<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends BaseSeeder
{
    private const CHUNK_SIZE = 1000;
    private int $admins      = 0;
    private int $users       = 0;

    public function run(): void
    {
        $this->initialize();

        $assignItems     = [];
        $permissions     = $this->permissions();
        $roleGuards      = $this->roleGuards();
        $rolePermissions = $this->rolePermissions();

        foreach ($roleGuards as $role => $guard) {
            Role::findOrCreate($role, $guard);
        }

        foreach ($rolePermissions as $role => $permissions) {
            $guard = $roleGuards[$role];
            foreach ($permissions as $permission) {
                Permission::findOrCreate($permission, $guard);
            }
        }

        foreach ($rolePermissions as $role => $permissions) {
            $guard = $roleGuards[$role];
            $permissionModels = Permission::query()
                ->whereIn('name', $permissions)
                ->where('guard_name', $guard)
                ->get();
            $role = Role::findByName($role, $guard);
            $role->syncPermissions($permissionModels);
        }

        $this->assignRoles($assignItems);

        $this->insertData('model_has_roles', $assignItems);

        $this->finalize('RoleAndPermissions', [
            'permissions' => count($permissions),
            'roles'       => count($roleGuards),
            'admins'      => $this->admins,
            'users'       => $this->users,
        ]);
    }

    private function permissions(): array
    {
        return [
            'view.shops',
            'manage.shops',
            'export.shops',
            'view.exports',
            'manage.exports',
            'view.staffs',
            'manage.staffs',
            'view.reservations',
            'manage.reservations',
        ];
    }

    private function roleGuards(): array
    {
        return [
            'admin'       => 'admin',
            'staff_owner' => 'shop',
            'staff'       => 'shop',
            'user'        => 'user',
        ];
    }

    private function rolePermissions(): array
    {
        return [
            'admin' => $this->permissions(),
            'staff_owner' => [
                'view.exports',
                'manage.exports',
            ],
            'staff' => [
                //
            ],
            'user' => [
                'view.reservations',
                'manage.reservations',
            ],
        ];
    }

    private function assignRoles(&$items): void
    {
        $adminRole      = Role::findByName('admin', 'admin');
        $userRole       = Role::findByName('user', 'user');
        $modelType      = User::class;

        User::where('email', 'like', 'admin_%@test.com')
            ->chunkById(self::CHUNK_SIZE, function ($admins) use ($adminRole, $modelType, &$items) {
                foreach ($admins as $admin) {
                    $items[] = [
                        'role_id' => $adminRole->id,
                        'model_type' => $modelType,
                        'model_id' => $admin->id,
                    ];
                    $this->admins++;
                }
            });

        User::where('email', 'like', 'user_%@test.com')
            ->chunkById(self::CHUNK_SIZE, function ($users) use ($userRole, $modelType, &$items) {
                foreach ($users as $user) {
                    $items[] = [
                        'role_id' => $userRole->id,
                        'model_type' => $modelType,
                        'model_id' => $user->id,
                    ];
                    $this->users++;
                }
            });
    }

    private function sample()
    {
        // $adminRole = Role::findByName('admin', 'admin');
        // $userRole  = Role::findByName('user', 'user');

        // $admins = User::where('email', 'like', 'admin_%@test.com')->get();
        // $users  = User::where('email', 'like', 'user_%@test.com')->get();

        // foreach ($admins as $admin) {
        //     $admin->assignRole($adminRole);   // モデルを渡すので guard=admin のロールがそのまま使われる
        // }

        // foreach ($users as $user) {
        //     $user->assignRole($userRole);     // guard=user のロールが使われる
        // }
    }
}
