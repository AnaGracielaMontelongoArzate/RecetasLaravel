<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    /**
     * Get the user that owns the Receta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $fillable = [
        "titulo","preparacion", "ingredientes", "imagen", "categoria_id"
    ];
    public function categoria()
    {
        return $this->belongsTo(CategoriaReceta::class);
    }
    public function autor()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
