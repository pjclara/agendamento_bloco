<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IntervencaoResource\Pages;
use App\Filament\Resources\IntervencaoResource\RelationManagers;
use App\Models\Intervencao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IntervencaoResource extends Resource
{
    protected static ?string $model = Intervencao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Intervenções';

    //protected static ?string $recordTitleAttribute = 'pageName';

    protected static ?string $modelLabel = 'Intervenção';

    protected static ?string $pluralModelLabel = 'Intervenções';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                static::getForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('abrv')
                    ->label("Abreviatura")
                    ->searchable(),
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
            'index' => Pages\ListIntervencaos::route('/'),
            'create' => Pages\CreateIntervencao::route('/create'),
            'edit' => Pages\EditIntervencao::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getForm()
    {
        return [
            Forms\Components\TextInput::make('nome')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('abrv')
                ->required()
                ->maxLength(10)
        ];
    }
}
