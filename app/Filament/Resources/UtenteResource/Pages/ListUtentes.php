<?php

namespace App\Filament\Resources\UtenteResource\Pages;

use App\Filament\Resources\UtenteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUtentes extends ListRecords
{
    protected static string $resource = UtenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
