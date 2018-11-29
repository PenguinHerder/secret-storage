<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    public function run() {
		
        DB::table('users')->insert([
            'name' => 'Andrzej',
            'email' => 'a.skutela@yahoo.pl',
			'role_id' => App\Models\Role::where('name', 'superuser')->first()->id,
			'password' => Hash::make('28duEC_df/u83UFDF'),
        ]);
    }
}
