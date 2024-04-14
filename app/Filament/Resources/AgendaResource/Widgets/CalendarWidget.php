<?php

namespace App\Filament\Resources\AgendaResource\Widgets;

use App\Filament\Resources\AgendaResource;
use App\Models\Agenda;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\DeleteAction;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Agenda::class;

    public function fetchEvents(array $fetchInfo): array
    {
        return Agenda::query()
            ->get()
            ->map(
                fn (Agenda $event) => EventData::make()
                    ->id($event->id)
                    ->title($event->intervencaos()->first()->abrv)
                    ->start($event->data)
                    ->end($event->data)
                    ->allDay(true)
            )
            ->toArray();
    }

    public function getFormSchema(): array
    {
        return AgendaResource::formFields();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'data' => $arguments['start'] ?? null,
                            'cirurgiao_id' => $arguments['cirurgiao_id'] ?? auth()->id(),
                        ]);
                    }
                )
        ];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->mountUsing(
                    function (Agenda $record, Form $form, array $arguments) {
                        $form->fill([
                            'utente_id' => $record->utente_id,
                            'cirurgiao_id' => $record->cirurgiao_id,
                            'data' => $arguments['event']['start'] ?? $record->data,
                            'sub_sistema_id' => $record->sub_sistema_id,
                            'intervencao_id' => $record->intervencao_id,
                            'observacoes' => $record->observacoes,
                        ]);
                    }
                )
                ->successNotification(
                    Notification::make()
                        ->body('Agendamento atualizado com sucesso!')
                        ->title('Successo!')
                        ->success() 

                ),
            DeleteAction::make(),
        ];
    }
}
