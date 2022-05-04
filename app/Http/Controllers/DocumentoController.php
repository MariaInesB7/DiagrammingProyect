<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$users = User::where('usuarioId',auth()->user()->id)->first();   
      
        $documentos=Documento::where('usuarioId',auth()->user()->id)->paginate(4);

        return view('documento.index',compact('documentos',$documentos));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        return view('documento.create');
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
        date_default_timezone_set("America/La_Paz");
        $documento=Documento::create([
            'nombre'=>request('nombre'),
            'fecha' => date('Y/m/d'),
           
            'estado' => request('estado'),
            'archivo' => request('archivo'),
            
            'usuarioId'=>auth()->user()->id,
        ]);
        $documento->save();
        return redirect()->route('documentos.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
        //
        return view('documento.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {
        //
        return view('documento.create',compact('documento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Documento $documento)
    {
        
        $documento->archivo=$request->archivo;
        $documento->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
        //
        $documento->delete();
        return redirect()->route('documentos.index');
    }
}
