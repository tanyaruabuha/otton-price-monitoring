<?php

    use App\User;
    use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'racquet',
            'password' => bcrypt('sports99'),
        ]);
    }
}
