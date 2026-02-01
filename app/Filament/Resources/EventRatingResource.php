<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventRatingResource\Pages;
use App\Filament\Resources\EventRatingResource\RelationManagers;
use App\Models\EventRating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventRatingResource extends Resource
{
    protected static ?string $model = EventRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Feedback';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Rating')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Pengguna')
                            ->placeholder('Pilih pengguna'),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Event')
                            ->placeholder('Pilih event'),
                        Forms\Components\Select::make('rating')
                            ->options([
                                1 => '⭐ (1 - Sangat Buruk)',
                                2 => '⭐⭐ (2 - Buruk)',
                                3 => '⭐⭐⭐ (3 - Cukup)',
                                4 => '⭐⭐⭐⭐ (4 - Baik)',
                                5 => '⭐⭐⭐⭐⭐ (5 - Sangat Baik)',
                            ])
                            ->required()
                            ->native(false)
                            ->label('Rating')
                            ->placeholder('Pilih rating'),
                        Forms\Components\Textarea::make('comment')
                            ->rows(4)
                            ->label('Komentar')
                            ->placeholder('Masukkan komentar (opsional)')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Pengguna'),
                Tables\Columns\TextColumn::make('event.title')
                    ->searchable()
                    ->sortable()
                    ->label('Event')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->label('Rating')
                    ->badge()
                    ->formatStateUsing(fn(int $state): string => str_repeat('⭐', $state))
                    ->colors([
                        'danger' => fn($state): bool => $state <= 2,
                        'warning' => 3,
                        'success' => fn($state): bool => $state >= 4,
                    ]),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50)
                    ->label('Komentar')
                    ->wrap()
                    ->toggleable()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (!$state || strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->label('Tanggal Rating'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui Pada'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ])
                    ->label('Filter by Rating'),
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Event'),
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListEventRatings::route('/'),
            'create' => Pages\CreateEventRating::route('/create'),
            'edit' => Pages\EditEventRating::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $avgRating = static::getModel()::avg('rating');
        return $avgRating ? number_format($avgRating, 1) . ' ⭐' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getPluralLabel(): string
    {
        return 'Rating Event';
    }
}
