<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Friend;
use App\Models\Request;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $userId = auth()->id();
                $query->where('id', '!=', $userId);
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                ImageColumn::make('photo_profile')
                    ->circular()
                    ->label('Photo'),
                // Friends
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('Add')
                        ->action(function (User $record) {
                            // Send request
                            $notification = Notification::make();
                            if (Request::canSendRequest($record)) {
                                $request = Request::sendRequest($record);
                                $notification->title($request['message']);
                                isset($request['request']) ? $notification->success() : $notification->danger();
                            } else {
                                $notification->title('You already have a pending request or are already friends');
                                $notification->warning();
                            }
                            $notification->send();
                        })
                        ->disabled(function (User $record) {
                            // Validate if you can send a friend request to a user
                            $request = Request::canSendRequest($record);
                            return !$request;
                        })
                        ->icon('heroicon-o-user-plus')
                        ,
                    Action::make('Block')
                        ->url('')
                        ->icon('heroicon-o-user-minus')
                        ->color('danger'),
                ])
                    ->link()
                    ->label('Actions')
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
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
