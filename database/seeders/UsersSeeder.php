<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nome' => 'Marcos Henrique',
            'email' => 'marcos.campos@gmail.com',
            'senha' => bcrypt('123456'),
        ]);
    }
}
