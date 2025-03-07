<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DefectTypeResource\Pages;
use App\Models\DefectType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DefectTypeResource extends Resource
{
    protected static ?string $model = DefectType::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    // Control visibility in navigation based on user role
    public static function canAccess(): bool
    {
        // Hide from Administrator role (role_id = 1)
        // Show for Organization (role_id = 2) and User (role_id = 3)
        $userRole = Auth::user()->role->name ?? null;
        return $userRole !== 'Administrator';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->rows(3),

                        Forms\Components\Fieldset::make('Translations')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Section::make('English')
                                            ->schema([
                                                Forms\Components\TextInput::make('translations.name.en')
                                                    ->label('Name'),
                                                Forms\Components\Textarea::make('translations.description.en')
                                                    ->label('Description')
                                                    ->rows(2),
                                            ]),

                                        Forms\Components\Section::make('French')
                                            ->schema([
                                                Forms\Components\TextInput::make('translations.name.fr')
                                                    ->label('Name'),
                                                Forms\Components\Textarea::make('translations.description.fr')
                                                    ->label('Description')
                                                    ->rows(2),
                                            ]),

                                        Forms\Components\Section::make('German')
                                            ->schema([
                                                Forms\Components\TextInput::make('translations.name.de')
                                                    ->label('Name'),
                                                Forms\Components\Textarea::make('translations.description.de')
                                                    ->label('Description')
                                                    ->rows(2),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('translations.name.fr')
                    ->label('French Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('translations.name.de')
                    ->label('German Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListDefectTypes::route('/'),
            'create' => Pages\CreateDefectType::route('/create'),
            'edit' => Pages\EditDefectType::route('/{record}/edit'),
        ];
    }
}
