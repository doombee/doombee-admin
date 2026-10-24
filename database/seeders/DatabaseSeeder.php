<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Claudio Ferraro',
            'email' => 'info@doombee.com',
            'password' => bcrypt('Clsmdfbg0'),
        ]);

        $user->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Claudio Ferraro',
            'email' => 'claudio.ferraro@swiss-commerce.ch',
            'password' => bcrypt('Clsmdfbg0'),
        ]);

        $user->assignRole('merchant');
    }
}
