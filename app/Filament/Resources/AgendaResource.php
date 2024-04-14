<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agenda;
use App\Models\Utente;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Faturacao;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\EstadoAgendamento;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\AgendaResource\Pages;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Agendamentos';

    //protected static ?string $recordTitleAttribute = 'pageName';

    protected static ?string $modelLabel = 'Agendamento';

    protected static ?string $pluralModelLabel = 'Agendamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                static::formFields(),
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('utente.nome')
                    ->label('Utente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('intervencaos.abrv')
                    ->label('Intervenção')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cirurgiao.abrv')
                    ->label('Cirurgião')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ajudante.abrv')
                    ->label('Ajudante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subSistema.nome')
                    ->label('Sub Sistema'),
                Tables\Columns\TextColumn::make('observacoes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\SelectColumn::make('estado_agendamento_id')
                    ->options(EstadoAgendamento::pluck('nome', 'id')->toArray())
                    ->rules(['required'])
                    ->label('Estado')
                    ->sortable(),
                Tables\Columns\IconColumn::make('faturacao')
                    ->boolean()
                    ->label('Faturação')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->visible(false),
                Tables\Filters\SelectFilter::make('estado_agendamento_id')
                    ->options(EstadoAgendamento::pluck('nome', 'id')->toArray())
                    ->label('Estado do agendamento'),
                Tables\Filters\Filter::make('data')
                    ->form([
                        DatePicker::make('Data inicial')->default(now()),
                        DatePicker::make('Data final'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Data inicial'],
                                fn (Builder $query, $date): Builder => $query->whereDate('data', '>=', $date),
                            )
                            ->when(
                                $data['Data final'],
                                fn (Builder $query, $date): Builder => $query->whereDate('data', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Intervenção atualizada')
                            ->body('A intervenção foi atualizada com sucesso.'),
                    ),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    //Tables\Actions\DeleteBulkAction::make(),
                    //Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    //create custom bulk action
                    Tables\Actions\BulkAction::make('custom')
                        ->label('Custom Bulk Action')
                        ->action(fn (Collection $records) => $records->each(
                            function (Agenda $record) {
                                $faturacao =  Faturacao::where('agenda_id', $record->id)
                                    ->where('user_id', auth()->id())->first();
                                if (!$faturacao) {
                                    if ($record->cirurgiao_id == auth()->id())
                                        $funcao = 'cirurgiao';
                                    elseif ($record->ajudante_id == auth()->id())
                                        $funcao = 'ajudante';
                                    elseif ($record->anestesista_id == auth()->id())
                                        $funcao = 'anestesista';
                                    else
                                        $funcao = 'outro';
                                    if ($funcao != 'outro')
                                        $record->faturacao()->create([
                                            'user_id' => auth()->id(),
                                            'valor' => 0,
                                            'data' => now(),
                                            'funcao' => $funcao,

                                        ]);
                                }
                            }
                        ))
                        ->deselectRecordsAfterCompletion(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgendas::route('/'),
            //'create' => Pages\CreateAgenda::route('/create'),
            //'edit' => Pages\EditAgenda::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function formFields(): array
    {
        return [
            Section::make()
                ->extraAttributes(['style' => 'background-color:#f4f4f4'])
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('utente_id')
                        ->relationship('utente', 'nome')
                        ->preload()
                        ->native(false)
                        ->required()
                        ->afterStateUpdated(function (?string $state, ?string $old, callable $set) {
                            if ($state)
                                $set('sub_sistema_id', 1);
                            else
                                $set('sub_sistema_id', null);
                        })
                        ->manageOptionForm(function (Form $form) {
                            return $form
                                ->schema(UtenteResource::getUtenteFormSchema());
                        }),
                    Forms\Components\Select::make('sub_sistema_id')
                        ->label('Sub Sistema')
                        ->required()
                        ->options(function (Get $get) {
                            return Utente::find($get('utente_id'))?->subSistemas?->pluck('nome', 'id')->toArray();
                        })
                        ->native(false),
                    Forms\Components\Select::make('patologias')
                        ->preload()
                        ->label('Patologias')
                        ->multiple()
                        ->required()
                        ->relationship('patologias', 'nome')
                        ->createOptionForm(function (Form $form) {
                            return $form
                                ->schema(PatologiaResource::getForm());
                        }),
                    Forms\Components\Select::make('intervencaos')
                        ->preload()
                        ->label('Intervenções')
                        ->multiple()
                        ->required()
                        ->relationship('intervencaos', 'nome')
                        ->createOptionForm(function (Form $form) {
                            return $form
                                ->schema(
                                    IntervencaoResource::getForm()
                                );
                        }),

                ]),
            Section::make('Equipa e agenda')
                ->extraAttributes(['style' => 'background-color:#f4f4f4'])
                ->schema([
                    Group::make([
                        Forms\Components\Select::make('cirurgiao_id')
                            ->relationship('cirurgiao', 'name')
                            ->default(auth()->id())
                            ->label('Cirurgião')
                            ->native(false),
                        Forms\Components\Select::make('ajudante_id')
                            ->relationship('ajudante', 'name')
                            ->native(false),
                        Forms\Components\Select::make('anestesista_id')
                            ->relationship('anestesista', 'name')
                            ->native(false),
                    ])->columns(3),
                    Group::make([
                        Forms\Components\DatePicker::make('data')
                            ->required(),
                        Forms\Components\Select::make('estado_agendamento_id')
                            ->native(false)
                            ->relationship('estadoAgendamento', 'nome')
                            ->label('Estado do agendamento')
                            ->required(),
                    ])->columns(3),
                ]),
            Section::make()
                ->extraAttributes(['style' => 'background-color:#f4f4f4'])
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('observacoes')
                        ->columnSpanFull()
                        ->maxLength(255),
                ]),
        ];
    }
}
