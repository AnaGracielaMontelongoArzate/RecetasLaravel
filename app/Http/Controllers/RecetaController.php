<?php

namespace App\Http\Controllers;

use App\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Symfony\Contracts\Service\Attribute\Required;
//use Intervention\Image\Image;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        return view("recetas.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::table('categoria_receta')->get()->pluck("nombre","id")->dd();
        $categorias=DB::table('categoria_receta')->get()->pluck("nombre","id");
        return view("recetas.create")->with("categorias",$categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request["imagen"]->store("upload-recetas","public"));
        
        $data=request()->validate([
            "titulo" => "required|min:6",
            "categoria" => "required",
            "preparacion" => "required",
            "ingredientes" => "required",
            "imagen" => "required|image"
        ]);
        $ruta_imagen=$request["imagen"]->store("upload-recetas","public");
        //dd($request->all());
        //$img = Image::make(public_path($ruta_imagen))->fit(200, 200);
        $img=Image::make(public_path("storage/{$ruta_imagen}"))->fit(1200,500);
        $img->save();
        
        DB::table('recetas')->insert([
            "titulo" => $data["titulo"],
            "preparacion" => $data["preparacion"],
            "ingredientes" => $data["ingredientes"],
            "imagen" => $ruta_imagen,
            "user_id" => Auth::user()->id,
            "categoria_id"=>$data["categoria"]
        ]);
        return redirect()->action("RecetaController@index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //
    }
}
