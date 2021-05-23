<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Models\User;

        $admin->username = 'administrator';
        $admin->name = 'Site Administrator';
        $admin->email = 'admin@larashop.test';
        $admin->roles = json_encode(['ADMIN']);
        $admin->password = Hash::make('larashop');
        $admin->avatar = 'tidak-ada-gambar.png';
        $admin->address = 'Malang, Jawa Timur';

        $admin->save();
        $this->command->info('User Admin berhasil ditambah !');
    }
}
