<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{

    public function run() {
		
        DB::table('roles')->insert([
            'name' => 'superuser',
            'permissions' => json_encode([
				'can_see_everything' => true,
			]),
        ]);
		
        DB::table('roles')->insert([
            'name' => 'manager',
            'permissions' => json_encode([
				'users' => [
					'view' => true,
					'add' => true,
					'promote' => true,
				],
				
				'groups' => [
					'create' => true,
				],
				
				'buckets' => [
					'create' => true,
				],
			]),
        ]);
		
        DB::table('roles')->insert([
            'name' => 'default',
            'permissions' => json_encode([
				'buckets' => [
					'create' => true,
				],
			]),
        ]);
    }
}
