<?php

namespace App\Filament\Resources\IntervencaoResource\Pages;

use App\Filament\Resources\IntervencaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIntervencaos extends ListRecords
{
    protected static string $resource = IntervencaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
