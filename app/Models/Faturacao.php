<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faturacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id',
        'user_id',
        'valor',
        'data',
        'observacoes',
    ];


    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
