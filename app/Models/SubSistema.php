<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSistema extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function utentes()
    {
        return $this->belongsToMany(Utente::class);
    }

    
}
