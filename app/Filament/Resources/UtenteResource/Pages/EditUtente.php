<?php

namespace App\Filament\Resources\UtenteResource\Pages;

use App\Filament\Resources\UtenteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUtente extends EditRecord
{
    protected static string $resource = UtenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
