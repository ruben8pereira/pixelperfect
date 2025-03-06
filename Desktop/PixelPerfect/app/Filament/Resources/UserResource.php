<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use function Illuminate\Support\filled;
use App\Services\AuditService;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->dehydrated(fn($state) => !empty($state))
                            ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                    ]),

                Forms\Components\Section::make('Access & Organization')
                    ->schema([
                        Forms\Components\Select::make('role_id')
                            ->label('Role')
                            ->relationship('role', 'name')
                            ->required(),

                        Forms\Components\Select::make('organization_id')
                            ->label('Organization')
                            ->relationship('organization', 'name')
                            ->searchable(),

                        Forms\Components\Toggle::make('is_validated')
                            ->label('Account is Validated')
                            ->default(true),

                        Forms\Components\Toggle::make('is_archived')
                            ->label('Account is Archived')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('organization.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_validated')
                    ->boolean()
                    ->label('Validated'),

                Tables\Columns\IconColumn::make('is_archived')
                    ->boolean()
                    ->label('Archived'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('role', 'name'),

                Tables\Filters\SelectFilter::make('organization')
                    ->relationship('organization', 'name'),

                Tables\Filters\TernaryFilter::make('is_validated')
                    ->label('Validation Status'),

                Tables\Filters\TernaryFilter::make('is_archived')
                    ->label('Archive Status'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('resetPassword')
                        ->label('Reset Password')
                        ->icon('heroicon-o-key')
                        ->form([
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->label('New Password'),

                            Forms\Components\TextInput::make('password_confirmation')
                                ->password()
                                ->required()
                                ->same('password')
                                ->label('Confirm Password'),
                        ])
                        ->action(function (User $record, array $data): void {
                            $record->update([
                                'password' => Hash::make($data['password']),
                            ]);

                            Notification::make()
                                ->title('Password reset successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('archiveUser')
                        ->label('Archive User')
                        ->icon('heroicon-o-archive-box')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->visible(fn(User $record) => !$record->is_archived)
                        ->action(function (User $record): void {
                            $record->update(['is_archived' => true]);

                            // Log for audit trail
                            AuditService::log(
                                'user_archived',
                                "User {$record->name} was archived",
                                ['is_archived' => false],
                                ['is_archived' => true]
                            );
                        }),

                    Tables\Actions\Action::make('restoreUser')
                        ->label('Restore User')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->visible(fn(User $record) => $record->is_archived)
                        ->action(function (User $record): void {
                            $record->update(['is_archived' => false]);

                            // Log for audit trail
                            AuditService::log(
                                'user_restored',
                                "User {$record->name} was restored",
                                ['is_archived' => true],
                                ['is_archived' => false]
                            );
                        }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
