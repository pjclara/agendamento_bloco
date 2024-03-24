<?php

namespace App\Filament\Resources\FaturacaoResource\Pages;

use App\Filament\Resources\FaturacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaturacao extends EditRecord
{
    protected static string $resource = FaturacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
