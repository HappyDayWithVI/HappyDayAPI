<?php


use App\User; 
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        // $this->call('UsersTableSeeder');

        User::create([
            'name' => 'Haoua',
            'firstname' => 'Soualmia',
            'birthdate' => '1993-10-11',
            'mail' => 'haouasou@gmail.com',
            'token' => '',
            'subtoken' => '',
            'city' => 'Grenoble',
            'zipcode' => '38000',
            'lastupdate' => '',
            'username' => 'haoua',
            'password' => '0000'
        ]);

        User::create([
            'name' => 'Aymeric',
            'firstname' => 'Lu',
            'birthdate' => '2000-01-01',
            'mail' => 'aymeric.lu@laposte.net',
            'token' => '',
            'subtoken' => '',
            'city' => 'Lyon',
            'zipcode' => '69000',
            'lastupdate' => '',
            'username' => 'aymeric',
            'password' => '0000'
        ]);

    }
}
