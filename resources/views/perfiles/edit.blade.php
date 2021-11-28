@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css" integrity="sha512-CWdvnJD7uGtuypLLe5rLU3eUAkbzBR3Bm1SFPEaRfvXXI2v2H5Y0057EMTzNuGGRIznt8+128QIDQ8RqmHbAdg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('botones')
<a href="{{route("recetas.index")}}" class="btn btn-primary mr-2 text-white">Volver</a>
@endsection

@section('content')
    <h1 class="text-center">
        Editar mi perfil
    </h1>
    <div class="col-md-10 bg-white p-3">
        <form 
        action="{{route("perfiles.update",["perfil"=>$perfil->id])}}"
        method="POST"
        enctype="multipart/form-data">

            @csrf
            @method('put')

            {{-- Nombre del usuario --}}
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" 
                name="nombre" 
                class="form-control @error("nombre") is-invalid @enderror"
                id="nombre" 
                placeholder="Nombre de usuario"
                value="{{$perfil->usuario->name}}">

                @error('nombre')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            {{-- URL del usuario --}}
            <div class="form-group">
                <label for="url">Url</label>
                <input type="text" 
                name="url" 
                class="form-control @error("url") is-invalid @enderror"
                id="url" 
                placeholder="url de usuario"
                value="{{$perfil->usuario->url}}">

                @error('url')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            {{-- biografia del usuario --}}
            <div class="form-group">
                <label for="biografia">Tu biografía</label>
                <input 
                id="biografia" 
                type="hidden" 
                name="biografia"
                value="{{$perfil->biografia}}">
                <trix-editor 
                input="biografia"
                class="form-control @error("biografia") is-invalid @enderror"></trix-editor>

                @error('biografia')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="imagen">Tu imagen</label>
                <input
                id="imagen"
                type="file"
                class="form-control @error("imagen") is-invalid @enderror"
                name="imagen">
                @if($perfil->imagen)
                    <div class="mt-4">
                        <p>Imagen Actual:</p>
                        <img src="/storage/{{$perfil->imagen}}" style="width: 300px">
                    </div>
                    @error('imagen')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                @endif
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Actualizar perfil">
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js" integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@endsection