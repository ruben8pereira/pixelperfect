<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Report';

    // Set the policy for this resource
    protected static ?string $policy = \App\Policies\AdminPolicy::class;

    // Only show reports for non-Administrator roles
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        // Administrators cannot see any reports
        if ($user->role->name === 'Administrator') {
            return $query->whereRaw('1 = 0'); // Returns empty query
        }

        // Organization can only see reports from their organization
        if ($user->role->name === 'Organization') {
            return $query->where('organization_id', $user->organization_id);
        }

        // Registered Users can see reports from their organization and their own reports
        if ($user->role->name === 'User') {
            return $query->where(function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id)
                    ->orWhere('created_by', $user->id);
            });
        }

        // Default to no reports
        return $query->whereRaw('1 = 0');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Your existing form fields
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                // Add other relevant fields
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                // Add other relevant columns
            ])
            ->filters([
                // Your existing filters
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // Your existing relations
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
