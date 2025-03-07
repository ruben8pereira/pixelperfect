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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use App\Services\AuditService;
use App\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Only show users for the current organization
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Administrator sees all users
        if ($user->role->name === 'Administrator') {
            return $query;
        }

        // Organization users can only see users in their own organization
        if ($user->role->name === 'Organization') {
            return $query->where('organization_id', $user->organization_id)
                ->where('role_id', '!=', 1); // Exclude Administrator role
        }

        // Default to no users
        return $query->whereRaw('1 = 0');
    }

    public static function form(Form $form): Form
    {
        $userRole = Auth::user()->role->name ?? null;

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

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->label('Phone Number'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->dehydrated(fn($state) => !empty($state))
                            ->dehydrateStateUsing(fn($state) => Hash::make($state)),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->label('Confirm Password')
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->dehydrated(false) // Don't save this to the database
                            ->visible(fn(string $operation): bool => $operation === 'create')
                            ->same('password'),
                    ]),

                Forms\Components\Section::make('Access & Organization')
                    ->schema([
                        Forms\Components\Select::make('role_id')
                            ->label('Role')
                            ->relationship('role', 'name')
                            ->required()
                            ->default(function () {
                                // If current user is Organization, always set role_id to 3 (User)
                                if (Auth::user()->role && Auth::user()->role->name === 'Organization') {
                                    return 3; // ID for 'User' role
                                }
                                return null;
                            })
                            ->disabled(function () {
                                // Disable selection if current user is Organization
                                return Auth::user()->role && Auth::user()->role->name === 'Organization';
                            })
                            ->visible(function () {
                                // Only show to Administrators, hide from Organizations
                                return Auth::user()->role && Auth::user()->role->name === 'Administrator';
                            }),

                        Forms\Components\Hidden::make('role_id')
                            ->default(3) // User role ID
                            ->visible(function () {
                                // Only show to Organizations, hide from Administrators
                                return Auth::user()->role && Auth::user()->role->name === 'Organization';
                            }),

                        // For Organization users - hidden field with pre-set value
                        Forms\Components\Hidden::make('organization_id')
                            ->default(function () {
                                return Auth::user()->organization_id;
                            })
                            ->visible(function () {
                                return Auth::user()->role && Auth::user()->role->name === 'Organization';
                            }),

                        // For Admin users - visible dropdown
                        Forms\Components\Select::make('organization_id')
                            ->label('Organization')
                            ->relationship('organization', 'name')
                            ->searchable()
                            ->visible(function () {
                                return Auth::user()->role && Auth::user()->role->name === 'Administrator';
                            }),

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

                // Make sure this column is visible and not hidden by default
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organization')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                ->label('Phone Number')
                ->searchable(),

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
                // Enhance the organization filter with more options
                Tables\Filters\SelectFilter::make('organization')
                    ->relationship('organization', 'name')
                    ->label('Organization')
                    ->multiple() // Allow selecting multiple organizations
                    ->preload() // Preload all organizations for faster filtering
                ,

                Tables\Filters\SelectFilter::make('role')
                    ->relationship('role', 'name'),

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
                            try {
                                // Check that the organization can only archive its own users
                                if (
                                    Auth::user()->role->name === 'Organization' &&
                                    $record->organization_id !== Auth::user()->organization_id
                                ) {
                                    Notification::make()
                                        ->title('Permission denied')
                                        ->body('You can only archive users in your organization.')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                // Don't allow archiving yourself
                                if ($record->id === Auth::id()) {
                                    Notification::make()
                                        ->title('Cannot archive yourself')
                                        ->body('You cannot archive your own account.')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                $success = $record->update(['is_archived' => true]);

                                if ($success) {
                                    // Log for audit trail
                                    AuditService::log(
                                        'user_archived',
                                        "User {$record->name} was archived",
                                        ['is_archived' => false],
                                        ['is_archived' => true]
                                    );

                                    Notification::make()
                                        ->title('User archived')
                                        ->body("User {$record->name} has been archived successfully.")
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Archive failed')
                                        ->body('Failed to archive the user. Please try again.')
                                        ->danger()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('An error occurred: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->role->name !== 'Organization'),
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

    public static function createRecord(array $data)
    {
        // If an Organization user is creating a user, set the role to 'User' (role_id 3)
        if (Auth::user()->role->name === 'Organization') {
            $data['role_id'] = Role::where('name', 'User')->first()->id;
            $data['organization_id'] = Auth::user()->organization_id;
        }

        return parent::createRecord($data);
    }
}
