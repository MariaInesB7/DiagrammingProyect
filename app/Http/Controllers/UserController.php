<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = DB::table('users');
       
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // date_default_timezone_set("America/La_Paz");
       //return $request;
       $users=User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => password_hash($request['password'],PASSWORD_DEFAULT),
        //'password' =>$request['password'], no oculta contraseña
    ]);
    
 
    return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
    }
 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
   
    }
    
 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        
    }

    /* API FLUTTER METODS USERS*/
    // public function login_app() {
    //     $email="jsoliz064@gmail.com";
    //     $password="12345678";
    //     $user=User::where('email',$email)->get();
    //     if (password_verify($password,$user[0]['password'])){
    //         return json_encode($user[0]['id'],JSON_UNESCAPED_UNICODE);    
    //     }

      
}
    