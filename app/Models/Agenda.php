<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agenda extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected  $guarded = [];

    public function utente()
    {
        return $this->belongsTo(Utente::class);
    }

    public function intervencaos()
    {
        return $this->belongsToMany(Intervencao::class);
    }

    public function intervencao()
    {
        return $this->belongsToMany(Intervencao::class)->first();
    }

    public function patologias()
    {
        return $this->belongsToMany(Patologia::class);
    }

    public function cirurgiao()
    {
        return $this->belongsTo(User::class);
    }

    public function ajudante()
    {
        return $this->belongsTo(User::class);
    }

    public function anestesista()
    {
        return $this->belongsTo(User::class);
    }

    public function dataFormatada()
    {
        return date('d/m/Y', strtotime($this->data));
    }

    public function horaFormatada()
    {
        return date('H:i', strtotime($this->hora));
    }

    public function subSistema()
    {
        return $this->belongsTo(SubSistema::class);
    }

    public function estadoAgendamento()
    {
        return $this->belongsTo(EstadoAgendamento::class);
    }

    public function faturacao()
    {
        // has one by user
        return $this->hasOne(Faturacao::class)->where('user_id', auth()->id());
    }

}
