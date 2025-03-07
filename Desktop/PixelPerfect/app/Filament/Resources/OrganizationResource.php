<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Services\AuditService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Organizations';

    protected static ?string $recordTitleAttribute = 'name';

    // Use the existing policy for authorization
    protected static ?string $policy = \App\Policies\OrganizationPolicy::class;

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Organization Name'),

                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->label('Organization Description')
                        ->placeholder('Briefly describe your organization'),

                    Forms\Components\TextInput::make('vat')
                        ->label('VAT Number')
                        ->maxLength(255)
                        ->placeholder('Enter your organization VAT')
                        ->unique(table: 'organizations', column: 'vat', ignoreRecord: true)
                        ->required()
                        ->validationMessages([
                            'unique' => 'This VAT number is already in use by another organization.',
                        ]),

                    Forms\Components\TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->maxLength(255)
                        ->placeholder('Enter your organization phone number')
                        ->unique(table: 'organizations', column: 'phone', ignoreRecord: true)
                        ->required()
                        ->validationMessages([
                            'unique' => 'This phone number is already in use by another organization.',
                        ]),

                    Forms\Components\TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(table: 'organizations', column: 'email', ignoreRecord: true)
                        ->placeholder('Enter your organization email address')
                        ->validationMessages([
                            'unique' => 'This email address is already in use by another organization.',
                        ]),

                    Forms\Components\TextInput::make('address')
                        ->maxLength(255)
                        ->placeholder('Organization address'),

                    Forms\Components\FileUpload::make('logo_path')
                        ->label('Organization Logo')
                        ->image()
                        ->disk('public')
                        ->directory('organization-logos')
                        ->visibility('public')
                        ->imagePreviewHeight('100')
                        ->loadingIndicatorPosition('left')
                        ->panelAspectRatio('2:1')
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadButtonPosition('left')
                        ->uploadProgressIndicatorPosition('left')
                ])
                ->columns(1),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('vat')
                    ->label('VAT Number')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),

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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Organization $record) {
                        // Log deletion for audit trail
                        AuditService::log(
                            'organization_deleted',
                            "Organization {$record->name} was deleted",
                            ['id' => $record->id, 'name' => $record->name],
                            null
                        );

                        // Remove the logo if it exists
                        if ($record->logo_path) {
                            Storage::disk('public')->delete($record->logo_path);
                        }
                    }),
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }


    /*protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }*/
}
