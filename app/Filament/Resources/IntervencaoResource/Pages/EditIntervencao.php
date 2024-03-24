<?php

namespace App\Filament\Resources\IntervencaoResource\Pages;

use App\Filament\Resources\IntervencaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntervencao extends EditRecord
{
    protected static string $resource = IntervencaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
