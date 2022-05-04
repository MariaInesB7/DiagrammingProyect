<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Documento;
use App\Models\Participa;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $user1 = new User();
        $user1->name = 'Lisa Martinez';
        $user1->email= 'lisa@gmail.com';
        $user1->password = bcrypt('12345');
       
        $user1->save();

        $user2 = new User();
        $user2->name = 'Yuliana Barrios';
        $user2->email= 'yuliana@gmail.com';
        $user2->password = bcrypt('12345');
        
        $user2->save();

        $documento1= new Documento();
      
        $documento1->nombre='diagrama 1';
     
        $documento1->fecha='2021-01-01';
       // $documento1->hora=date('H:i');
        $documento1->estado='En proceso';
        $documento1->link='Edvin Añez Llanos';
        $documento1->save();

        $documento2= new Documento();
        $documento2->nombre='diagrama c4';
        $documento2->fecha='2021-04-01';
      //  $documento2->hora=date('H:i');
        $documento2->estado='En proceso';
        $documento2->link='Edvin Añez Llanos';
        $documento2->save();

        $participa=new Participa();
        $participa->usuarioId=1;
        $participa->documentoId=1;
        $participa->save();

        /* $participa=new Participa();
        $participa->usuarioId=1;
        $participa->documentoId=2;
        $participa->save(); */
    }
}
