<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FriendResource\Pages;
use App\Filament\Resources\FriendResource\RelationManagers;
use App\Models\Friend;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;

class FriendResource extends Resource
{
    protected static ?string $model = Friend::class;

    protected static ?string $modelLabel = 'My friends';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                $query->where('user_id', '=', $userId)->orWhere('friend_id', '=', $userId);
            })
            ->columns([
                TextColumn::make('friend.name')
                    ->searchable(),
                TextColumn::make('friend.email')
                    ->searchable(),
                ImageColumn::make('user.photo_profile')
                    ->label('Photo'),
            ])
            ->filters([
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
            'index' => Pages\ListFriends::route('/'),
            'create' => Pages\CreateFriend::route('/create'),
            'edit' => Pages\EditFriend::route('/{record}/edit'),
        ];
    }
}
