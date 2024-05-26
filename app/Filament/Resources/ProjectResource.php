<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationGroup = "Productivity";

    protected static ?string $navigationIcon = 'heroicon-c-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpan(2),
                Textarea::make('description')
                    ->rows(2)
                    ->columnSpan(2),
                Radio::make('visibility')
                    ->options([
                        'P' => 'Public',
                        'R' => 'Private',
                    ])
                    ->inline()
                    ->inlineLabel(false)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Created by')
                    ->searchable(),
                TextColumn::make('')
                    ->label('Users'),
                TextColumn::make('visibility')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'P': return 'Public';
                            case 'R': return 'Private';
                            default: return 'Unknown';
                        }
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'O': return 'Open';
                            case 'C': return 'Close';
                            default: return 'Unknown';
                        }
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'O' => 'success',
                        'C' => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
            ])
            ->filters([
                Filter::make('my_projects')
                    ->label('My projects')
                    ->query(fn (Builder $query) => $query->where('user_id', auth()->id())),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('Add users')
                        ->action(function (Project $record) {
                            
                        })
                        ->icon('heroicon-o-user-plus')
                        ->disabled(fn (Project $record) => !Project::canApplyActions($record)),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-s-pencil-square')
                        ->disabled(fn (Project $record) => !Project::canApplyActions($record)),
                    Action::make('Eliminar')
                        ->url('')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->disabled(fn (Project $record) => !Project::canApplyActions($record)),
                ])
                    ->link()
                    ->label('Actions')
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
