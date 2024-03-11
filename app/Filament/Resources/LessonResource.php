<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonContentsResource\RelationManagers\LessonRelationManager;
use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\TextInput::make('meta_description')->required(),
                    Forms\Components\TextInput::make('duration')->integer()->required(),
                    Forms\Components\RichEditor::make('overview')->required(),
                    Forms\Components\Checkbox::make('is_free'),
                    Forms\Components\Select::make('course_id')
                        ->relationship('course', 'title')
                        ->searchable()
                        ->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('meta_description')->searchable(),
                Tables\Columns\TextColumn::make('duration')->searchable(),
                Tables\Columns\CheckboxColumn::make('is_free'),
                Tables\Columns\TextColumn::make('course.title')
                    ->searchable()
                    ->label('Course')
                    ->badge()
            ])
            ->filters([
                Tables\Filters\Filter::make('Filter By Duration')->form([
                    Forms\Components\TextInput::make('min_duration')
                        ->numeric()
                        ->placeholder('Min Duration'),
                    Forms\Components\TextInput::make('max_duration')
                        ->numeric()
                        ->placeholder('Max Duration')
                ])->query(function (Builder $query, array $data): Builder{
                    return $query->when(
                        $data['min_duration'],
                        fn (Builder $query, $value): Builder => $query->where('duration', '>=', $value)
                    )->when(
                        $data['max_duration'],
                        fn (Builder $query, $value): Builder => $query->where('duration', '<=', $value)
                    );
                })
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
            LessonRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
