<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $disk = \Storage::disk('seeders');
        $items = \GuzzleHttp\json_decode(
            $disk->get('users.json'),
            true
        );

        $data = \Cache::get('setup_app');
        if (!empty($data)) {
            $users = \GuzzleHttp\json_decode(
                $data,
                true
            );
            \Cache::forget('setup_app');
        } else {
            $users = [
                'admin' => $items[0],
                'company' => $items[1],
            ];
        }

        $users['admin']['password'] = bcrypt($users['admin']['password']);
        $users['company']['password'] = bcrypt($users['company']['password']);

        $admin = \App\User::create($users['admin']);
        $company = \App\User::create($users['company']);

        $role = config('laravel-permission.models.role');
        $superAdminRole = $role::where('name', 'SUPER-ADMIN')->firstOrFail();
        $companyRole = $role::where('name', 'COMPANY')->firstOrFail();

        $admin->assignRole($superAdminRole);
        $company->assignRole($companyRole);
    }
}
