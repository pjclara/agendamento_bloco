<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendaResource\Pages;
use App\Models\Agenda;
use App\Models\EstadoAgendamento;
use App\Models\Utente;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->extraAttributes(['style' => 'background-color:#f4f4f4'])
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('utente_id')
                            ->relationship('utente', 'nome')
                            ->native(false)
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function (?string $state, ?string $old, callable $set) {
                                $set('sub_sistema_id', 1);
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
                            ->relationship('patologias', 'nome')
                            ->createOptionForm(function (Form $form) {
                                return $form
                                    ->schema([
                                        Forms\Components\TextInput::make('nome')
                                            ->required()
                                            ->unique('patologias', 'nome'),
                                    ]);
                            }),
                        Forms\Components\Select::make('intervencaos')
                            ->preload()
                            ->label('Intervenções')
                            ->multiple()
                            ->relationship('intervencaos', 'nome')
                            ->createOptionForm(function (Form $form) {
                                return $form
                                    ->schema([
                                        Forms\Components\TextInput::make('nome')
                                            ->required()
                                            ->unique('intervencaos', 'nome'),
                                    ]);
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
                                ->visibleOn('edit')
                                ->native(false),
                            Forms\Components\Select::make('anestesista_id')
                                ->relationship('anestesista', 'name')
                                ->visibleOn('edit')
                                ->native(false),
                        ])->columns(3),
                        Group::make([
                            Forms\Components\DateTimePicker::make('data')
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
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('utente.nome')
                    ->label('Utente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('intervencaos.nome')
                    ->label('Intervenção')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cirurgiao.abrv')
                    ->label('Cirurgião')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subSistema.nome')
                    ->label('Sub Sistema'),
                Tables\Columns\TextColumn::make('observacoes')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('estado_agendamento_id')
                    ->options(EstadoAgendamento::pluck('nome', 'id')->toArray())
                    ->rules(['required'])
                    ->label('Estado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
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
                /*
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                */]);
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
}
