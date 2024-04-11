<?php

namespace App\Filament\Resources\FaturacaoResource\Pages;

use App\Filament\Resources\FaturacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaturacaos extends ListRecords
{
    protected static string $resource = FaturacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    

}
