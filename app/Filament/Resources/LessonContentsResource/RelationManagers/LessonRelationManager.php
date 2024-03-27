<?php

namespace App\Filament\Resources\LessonContentsResource\RelationManagers;

use App\Models\LessonContent;
use App\Models\Text;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonRelationManager extends RelationManager
{
    protected static string $relationship = 'contents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('contentable_type')
                    ->label('Content Type')
                    ->options([
                        Text::class => 'Text',
                        Video::class => 'Video',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $set('contentable_id', null);
                    }),
                Forms\Components\Select::make('contentable_id')
                    ->label('Content ID')
                    ->options(function (callable $get) {
                        $modelClass = $get('contentable_type');
                        return $modelClass ? $modelClass::query()->pluck('id', 'id') : [];
                    })
                    ->reactive()
                    ->visible(fn (callable $get) => $get('contentable_type') !== null),
                Forms\Components\TextInput::make('order')
                    ->default(function (){
                        $lessonId  = $this->getRelationship()->getParent()->id;
                        $maxOrder = LessonContent::where('lesson_id', $lessonId)->max('order');
                        return is_null($maxOrder) ? 1 : $maxOrder + 1;
                    })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->reorderable('order')
            ->columns([
                Tables\Columns\TextColumn::make('lesson_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contentable_id')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('contentable_type')
                    ->label('Content Type')
                    ->options([
                        Text::class => 'Text',
                        Video::class => 'Video',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contentable_type')
                    ->options([
                        Text::class => 'Text',
                        Video::class => 'Video',
                    ])
                    ->label('Content Type'),
                Tables\Filters\Filter::make('Filter By ID')->form([
                    Forms\Components\TextInput::make('min_id')
                        ->numeric()
                        ->placeholder('Min Content ID'),
                    Forms\Components\TextInput::make('max_id')
                        ->numeric()
                        ->placeholder('Max Content ID')
                ])->query(function (Builder $query, array $data): Builder{
                    return $query->when(
                        $data['min_id'],
                        fn (Builder $query, $value): Builder => $query->where('contentable_id', '>=', $value)
                    )->when(
                        $data['max_id'],
                        fn (Builder $query, $value): Builder => $query->where('contentable_id', '<=', $value)
                    );
                })
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
