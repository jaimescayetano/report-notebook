<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestResource\Pages;
use App\Filament\Resources\RequestResource\RelationManagers;
use App\Models\Friend;
use App\Models\Request;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationIcon = 'heroicon-c-inbox-arrow-down';

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
                $query->where('user_id', '=', $userId);
            })
            ->columns([
                TextColumn::make('sender.name')
                    ->label('Send by'),
                TextColumn::make('user.name')
                    ->label('For'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'P':
                                return 'Pending';
                            case 'A':
                                return 'Accepted';
                            case 'R':
                                return 'Rejected';
                            default:
                                return 'Unknown';
                        }
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'P' => 'gray',
                        'A' => 'success',
                        'R' => 'danger'
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('Accept')
                        ->action(function (Request $record) {
                            $data['user_id'] = auth()->id();
                            $data['friend_id'] = $record->send_by;
                            Friend::create($data);
                        })
                        ->icon('heroicon-o-check-circle'),
                    Action::make('Reject')
                        ->url('')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger'),
                ])
                    ->link()
                    ->label('Actions')
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }
}
