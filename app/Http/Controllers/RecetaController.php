<?php

namespace App\Http\Controllers;

use App\CategoriaReceta;
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
        $this->middleware("auth",["except"=>"show"]);
    }

    public function index()
    {

        // auth()->user()->recetas->dd();
        // $recetas = auth()->user()->recetas->paginate(2);

        $usuario = auth()->user();

        // Recetas con paginación
        $recetas = Receta::where('user_id', $usuario->id)->paginate(10);

        return view('recetas.index')->with('recetas', $recetas)->with('usuario', $usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::table('categoria_recetas')->get()->pluck("nombre","id")->dd();

        //Obtener la categoria sin modelo.
        //$categorias=DB::table('categoria_recetas')->get()->pluck("nombre","id");

        //Obtener la categoria con modelo.
        $categorias=CategoriaReceta::all(["id","nombre"]);

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

        //Almacenar sin modelo con la clase DB.
        /*
        DB::table('recetas')->insert([
            "titulo" => $data["titulo"],
            "preparacion" => $data["preparacion"],
            "ingredientes" => $data["ingredientes"],
            "imagen" => $ruta_imagen,
            "user_id" => Auth::user()->id,
            "categoria_id"=>$data["categoria"]
        ]);
        return redirect()->action("RecetaController@index");
        */


        //Almacenar con modelo.
        
        auth()->user()->recetas()->create([
            "titulo" => $data["titulo"],
            "preparacion" => $data["preparacion"],
            "ingredientes" => $data["ingredientes"],
            "imagen" => $ruta_imagen,
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
        //Métodos para obtener receta.
        //$receta=Receta::find($receta);
        //$receta=Receta::findOrFail($receta);
        //return $receta;
        // Obtener si el usuario actual le gusta la receta y esta autenticado
        $like = ( auth()->user() ) ?  auth()->user()->meGusta->contains($receta->id) : false; 

        // Pasa la cantidad de likes a la vista
        $likes = $receta->likes->count();

        return view('recetas.show', compact('receta', 'like', 'likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        // Ejecutamos el Policy
        $this->authorize("view",$receta);

        $categorias=CategoriaReceta::all(["id","nombre"]);
        return view("recetas.edit", compact("categorias","receta"));
        
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
        //Revisa la politica
        $this->authorize("update",$receta);
        //return $receta;
        $data=request()->validate([
            "titulo" => "required|min:6",
            "categoria" => "required",
            "preparacion" => "required",
            "ingredientes" => "required"
        ]);
        //Validación
        $receta->titulo=$data["titulo"];
        $receta->categoria_id=$data["categoria"];
        $receta->preparacion=$data["preparacion"];
        $receta->ingredientes=$data["ingredientes"];
        if(request("imagen")){
            //Optiene la ruta de la imagen.
            $ruta_imagen=$request["imagen"]->store("upload-recetas","public");
            //Resize de la imagen
            $img=Image::make(public_path("storage/{$ruta_imagen}"))->fit(1200,500);
            $img->save();
            //Asignamos al objeto.
            $receta->imagen=$ruta_imagen;

        }
        $receta->save();

        //Redireccionamiento
        return redirect()->action("RecetaController@index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //Revisar la policy
        $this->authorize('delete',$receta);
        //Eliminar la receta
        $receta->delete();
        return redirect()->action('RecetaController@index');
    }
}
