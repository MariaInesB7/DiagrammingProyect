<?php

namespace App\Http\Controllers;

use App\Models\Participa;
use App\Models\User;
use App\Models\Documento;
use Illuminate\Http\Request;

class ParticipaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
            $participas = Participa::where('usuarioID',auth()->user()->id);
            
            return view('participa.index',compact('participas',$participas));
        

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Participa  $participa
     * @return \Illuminate\Http\Response
     */
    public function show(Participa $participa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Participa  $participa
     * @return \Illuminate\Http\Response
     */
    public function edit(Participa $participa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Participa  $participa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Participa $participa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Participa  $participa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Participa $participa)
    {
        //
    }
}
