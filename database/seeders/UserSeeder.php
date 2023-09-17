<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
          [
            'name' => 'Fernando',
            'lastname' => 'Sanchez',
            'username' => 'fernandosan',
            'phone' => '96911724',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'status' => 'active',
            'password' => bcrypt('Miguel123'),
          ],
          [
            'name' => 'Orlando',
            'lastname' => 'Euceda',
            'username' => 'oeuceda',
            'phone' => '96911725',
            'email' => 'oeuceda@test.com',
            'role' => 'tecnico',
            'status' => 'active',
            'password' => bcrypt('Oeuceda123'),
          ],
          [
            'name' => 'Dilcia',
            'lastname' => 'Soto',
            'username' => 'dsoto',
            'phone' => '96911726',
            'email' => 'dsoto@test.com',
            'role' => 'secretaria',
            'status' => 'active',
            'password' => bcrypt('Dsoto123'),
          ]
        ]);
    }
}
