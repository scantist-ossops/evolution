<?php namespace EvolutionCMS\Installer\Update;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        $affected = DB::table('permissions')->where('key', 'web_access_permissions')->update([
            'name'     => 'Manage document and user groups',
            'key'      => 'manage_groups',
            'lang_key' => 'manage_groups'
        ], ['timestamps' => false]);
        if ($affected) {
            DB::table('permissions')->where('key', 'access_permissions')->update([
                'name'     => 'Manager access permissions',
                'lang_key' => 'manager_access_permissions'
            ], ['timestamps' => false]);
            DB::table('role_permissions')->where('permission', 'web_access_permissions')->update([
                'permission' => 'manage_groups',
            ]);
            $insertArray = [
                [
                    'name'     => 'Manage document permissions', 'key' => 'manage_document_permissions',
                    'lang_key' => 'manage_document_permissions', 'disabled' => 0, 'group_id' => 11
                ],
                [
                    'name'     => 'Manage module permissions', 'key' => 'manage_module_permissions',
                    'lang_key' => 'manage_module_permissions', 'disabled' => 0, 'group_id' => 11
                ],
                [
                    'name'     => 'Manage TV permissions', 'key' => 'manage_tv_permissions',
                    'lang_key' => 'manage_tv_permissions', 'disabled' => 0, 'group_id' => 11
                ],

            ];
            DB::table('permissions')->insert($insertArray);
            $insertArray = [
                ['permission' => 'manage_document_permissions', 'role_id' => 1],
                ['permission' => 'manage_module_permissions', 'role_id' => 1],
                ['permission' => 'manage_tv_permissions', 'role_id' => 1],
                ['permission' => 'manage_document_permissions', 'role_id' => 2],
                ['permission' => 'manage_document_permissions', 'role_id' => 3],
            ];
            DB::table('role_permissions')->insert($insertArray);
        }
    }
}
