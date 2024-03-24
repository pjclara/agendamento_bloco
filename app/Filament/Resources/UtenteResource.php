<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtenteResource\Pages;
use App\Filament\Resources\UtenteResource\RelationManagers;
use App\Models\Utente;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UtenteResource extends Resource
{
    protected static ?string $model = Utente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('numero_processo')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('sexo')
                    ->required()
                    ->options([
                        'Masculino' => 'M',
                        'Feminino' => 'F',
                    ]),
                Forms\Components\Select::make('sub_sistemas')
                    ->relationship('subSistemas', 'nome')
                    ->multiple()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_processo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sexo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subSistemas.nome')
                    ->searchable(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUtentes::route('/'),
            'create' => Pages\CreateUtente::route('/create'),
            'edit' => Pages\EditUtente::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getUtenteFormSchema(): array
    {
        return [
            Section::make('Informações do Utente')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('numero_processo')
                        ->label('Número do M1')
                        ->required()
                        ->numeric(),
                    Forms\Components\Select::make('sexo')
                        ->required()
                        ->options([
                            'M' => 'Masculino',
                            'F' => 'Feminino',
                        ]),
                    Forms\Components\Select::make('sub_sistemas')
                        ->relationship('subSistemas', 'nome')
                        ->multiple()
                ]),
        ];
    }
}
