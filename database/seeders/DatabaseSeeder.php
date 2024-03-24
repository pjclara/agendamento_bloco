<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Funcao;
use App\Models\SubSistema;
use Illuminate\Database\Seeder;
use App\Models\EstadoAgendamento;
use App\Models\Intervencao;
use App\Models\Patologia;
use App\Models\Utente;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Paulo Clara',
            'abrv' => 'PC',
            'email' => 'pjclara@gmail.com',
            'password' => bcrypt('pc2406451'),
        ]);

        SubSistema::factory()->create([
            'nome' => 'ADSE',
        ]);

        SubSistema::factory()->create([
            'nome' => 'SAMS',
        ]);

        SubSistema::factory()->create([
            'nome' => 'ADM',
        ]);

        SubSistema::factory()->create([
            'nome' => 'SAMS Quadros',
        ]);

        SubSistema::factory()->create([
            'nome' => 'Privado',
        ]);

        $utente = Utente::factory()->create([
            'nome' => 'Maria joao',
            'numero_processo' => '123456789',
            'sexo' => 'F',
        ]);

        $utente->subSistemas()->attach([1, 2]);

        Patologia::factory()->create([
            'nome' => 'Hernia inguinal',
        ]);

        Intervencao::factory()->create([
            'nome' => 'Hernioplastia inguinal',
        ]);

        $estadoAgendamento = [
            ['nome' => 'Orçamento pedido', 'cor' => '#FFD700'],
            ['nome' => 'Orçamento aceite', 'cor' => '#008000'],
            ['nome' => 'Agendamento realizado', 'cor' => '#FF0000'],
            ['nome' => 'Adiado', 'cor' => '#FFA500'],
            ['nome' => 'Não Realizado', 'cor' => '#000000'],
            ['nome' => 'Operado', 'cor' => '#000FFF'],
        ];

        foreach ($estadoAgendamento as $estado) {
            EstadoAgendamento::factory()->create($estado);
        }

    }
}
