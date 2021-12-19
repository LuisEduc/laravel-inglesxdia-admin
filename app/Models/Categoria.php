<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [ 'slug','orden','titulo','descripcion','nivel','icono' ];

    protected $hidden = ['created_at', 'updated_at', 'orden'];

    public function lessons(){
        return $this->hasMany(Lesson::class, 'id');
    }
}
