<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            "name"=>"Anita",
            "email"=>"Anita@gmail.com",
            "password"=>Hash::make("123456789"),
            "url"=>"http://Hola.com",
        ]);
        $user->perfil()->create();

        $user2 = User::create([
            "name"=>"Anita2",
            "email"=>"Anita2@gmail.com",
            "password"=>Hash::make("123456789"),
            "url"=>"http://Hola.com",
        ]);
        $user2->perfil()->create();
    }
}
