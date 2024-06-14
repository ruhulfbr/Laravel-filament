<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required() // cannot empty
                    ->maxLength(255), // max char 255

                Forms\Components\TextInput::make('email')
                    ->required() // cannot empty
                    ->email() // email validation
                    ->unique(ignoreRecord: true)
                    ->maxLength(255), // max char 255

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->required(fn($record) => $record === null), // Required only when creating a new record

                Forms\Components\TextInput::make('passwordConfirmation')
                    ->password()
                    ->label('Confirm Password')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => null)
                    ->required(fn($record) => $record === null)
                    ->same('password'),

                Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->columnSpanFull()
                    ->imageEditor()
                    ->directory('user-avatar')
                    ->dehydrated(fn($state) => empty($state['avatar'])) // Only send the avatar if it's not empty
                    ->maxSize(2048) // Example: Restricting file size to 2MB
                    ->rules(['image', 'max:2048']),

                Forms\Components\Radio::make('status')
                    ->default(1)
                    ->visibleOn(['edit', 'view'])
                    ->options(UserStatus::class),

                Forms\Components\Radio::make('role')
                    ->default(1)
                    ->visibleOn(['edit', 'view'])
                    ->options(UserRole::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->sortable()->dateTime(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(url('/avatar/nophoto.jpg'))
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(UserStatus::class)->toggleable(),
                Tables\Columns\TextColumn::make('role')->badge(UserRole::class)->toggleable()
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(UserStatus::class),
                Tables\Filters\SelectFilter::make('role')->options(UserRole::class),
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
