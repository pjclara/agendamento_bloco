<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervencao extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nome'];

    public function utentes()
    {
        return $this->belongsToMany(Utente::class);
    }

    public function patologias()
    {
        return $this->belongsToMany(Patologia::class);
    }
    
}
