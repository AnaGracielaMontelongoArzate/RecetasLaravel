<?php

namespace App\Http\Controllers;

use App\Perfil;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {
        return view("perfiles.show", compact("perfil"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {
        return view("perfiles.edit", compact("perfil"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {
        //Validacion de los datos.
        $data = request()->validate([
            "nombre" => "required",
            "url" => "required",
            "biografia" => "required"
        ]);
        //Si se sube imagen.
        if($request["imagen"]){
            //Obtener la ruta de la imagen.
            $ruta_imagen=$request["imagen"]->store("upload-perfiles","public");
            //Resize de la imagen.
            $img=Image::make(public_path("storage/{$ruta_imagen}"))->fit(600,600);
            $img->save();
            //Arreglo de la imagem
            $array_imagen = ["imagen" => $ruta_imagen];
        }

        //Asignar nombre e url.
        auth()->user()->url = $data["url"];
        auth()->user()->name = $data["nombre"];

        //Eliminar nombre e url.
        unset($data["url"]);
        unset($data["nombre"]);

        //Asignar biografia e imagen.
        auth()->user()->perfil()->update( array_merge(
            $data,
            $array_imagen ?? []
        ));
        //Guargar los datos.
        //Redireccionar.
        return redirect()->action("RecetaController@index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}
