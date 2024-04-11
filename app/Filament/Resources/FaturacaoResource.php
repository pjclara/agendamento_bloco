<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaturacaoResource\Pages;
use App\Filament\Resources\FaturacaoResource\RelationManagers;
use App\Models\Faturacao;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaturacaoResource extends Resource
{
    protected static ?string $model = Faturacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Faturações';

    //protected static ?string $recordTitleAttribute = 'pageName';

    protected static ?string $modelLabel = 'Faturação';

    protected static ?string $pluralModelLabel = 'Faturações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agenda_id')
                    ->relationship('agenda.utente', 'nome')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->required(),
                Forms\Components\TextInput::make('valor')
                    ->required()
                    ->numeric(),
                Forms\Components\Datepicker::make('data')
                    ->required()
                    ->default(now()->format('Y-m-d')),
                Forms\Components\Textarea::make('observacoes')
                    ->nullable(),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agenda.utente.nome')
                    ->label('Utente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agenda.subSistema.nome')
                    ->label('SubSistema')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilizador')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('funcao')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('data')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observacoes')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //apenas user_id auth


                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFaturacaos::route('/'),
            'create' => Pages\CreateFaturacao::route('/create'),
            'edit' => Pages\EditFaturacao::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('admin')) {
            return static::getModel()::query();
        }
        return static::getModel()::query()->where('user_id', auth()->id());
    }
}
