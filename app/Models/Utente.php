<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Utente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nome', 'numero_processo', 'sexo'];

    public function patologias()
    {
        return $this->belongsToMany(Patologia::class);
    }

    public function intervencaos()
    {
        return $this->belongsToMany(Intervencao::class);
    }

    public function subSistemas()
    {
        return $this->belongsToMany(SubSistema::class);
    }


}
