<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Enums\EventType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar Event')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Event')
                            ->placeholder('Masukkan judul event')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->label('Deskripsi')
                            ->placeholder('Masukkan deskripsi event')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                            ]),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Kategori'),
                            ])
                            ->label('Kategori'),
                        Forms\Components\Select::make('event_type')
                            ->options([
                                EventType::Seminar->value => 'Seminar',
                                EventType::Webinar->value => 'Webinar',
                                EventType::Workshop->value => 'Workshop',
                            ])
                            ->required()
                            ->native(false)
                            ->label('Tipe Event'),
                        Forms\Components\Select::make('speakers')
                            ->relationship('speakers', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Pembicara'),
                                Forms\Components\Textarea::make('bio')
                                    ->label('Biografi'),
                                Forms\Components\FileUpload::make('photo')
                                    ->image()
                                    ->directory('speakers')
                                    ->label('Foto'),
                            ])
                            ->label('Pembicara')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Waktu & Tempat')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->native(false)
                            ->label('Tanggal Mulai'),
                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->native(false)
                            ->afterOrEqual('start_date')
                            ->label('Tanggal Selesai'),
                        Forms\Components\TimePicker::make('start_time')
                            ->required()
                            ->native(false)
                            ->label('Waktu Mulai'),
                        Forms\Components\TimePicker::make('end_time')
                            ->required()
                            ->native(false)
                            ->label('Waktu Selesai'),
                        Forms\Components\Toggle::make('is_online')
                            ->label('Event Online?')
                            ->reactive()
                            ->default(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255)
                            ->label('Lokasi')
                            ->placeholder('Masukkan lokasi event')
                            ->hidden(fn(Get $get): bool => $get('is_online'))
                            ->required(fn(Get $get): bool => !$get('is_online'))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('meeting_link')
                            ->url()
                            ->maxLength(255)
                            ->label('Link Meeting')
                            ->placeholder('Masukkan link meeting (Zoom, Google Meet, dll)')
                            ->hidden(fn(Get $get): bool => !$get('is_online'))
                            ->required(fn(Get $get): bool => $get('is_online'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Harga & Media')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0)
                            ->label('Harga Tiket')
                            ->helperText('Masukkan 0 untuk event gratis'),
                        Forms\Components\FileUpload::make('poster')
                            ->image()
                            ->imageEditor()
                            ->directory('event-posters')
                            ->label('Poster Event')
                            ->maxSize(3072)
                            ->helperText('Upload poster event (max 3MB)')
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('Benefit Event')
                    ->schema([
                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\TextInput::make('benefit')
                                    ->required()
                                    ->label('Benefit')
                                    ->placeholder('Contoh: Sertifikat, Materi PDF, dll'),
                            ])
                            ->label('Daftar Benefit')
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Benefit')
                            ->columnSpanFull()
                            ->columns(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('poster')
                    ->label('Poster')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul Event')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->label('Kategori')
                    ->color('success'),
                Tables\Columns\TextColumn::make('event_type')
                    ->badge()
                    ->label('Tipe')
                    ->colors([
                        'info' => EventType::Seminar->value,
                        'warning' => EventType::Webinar->value,
                        'success' => EventType::Workshop->value,
                    ])
                    ->formatStateUsing(fn(EventType $state): string => match ($state->name) {
                        EventType::Seminar->name => 'Seminar',
                        EventType::Webinar->name => 'Webinar',
                        EventType::Workshop->name => 'Workshop',
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal'),
                Tables\Columns\IconColumn::make('is_online')
                    ->boolean()
                    ->label('Online')
                    ->trueIcon('heroicon-o-wifi')
                    ->falseIcon('heroicon-o-map-pin'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga')
                    ->formatStateUsing(fn($state): string => $state == 0 ? 'Gratis' : 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registrasi')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Kategori'),
                Tables\Filters\SelectFilter::make('event_type')
                    ->options([
                        EventType::Seminar->value => 'Seminar',
                        EventType::Webinar->value => 'Webinar',
                        EventType::Workshop->value => 'Workshop',
                    ])
                    ->label('Tipe Event'),
                Tables\Filters\Filter::make('is_online')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('is_online', true))
                    ->label('Event Online'),
                Tables\Filters\Filter::make('free')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('price', 0))
                    ->label('Event Gratis'),
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
            ->defaultSort('start_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RegistrationsRelationManager::class,
            RelationManagers\SpeakersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPluralLabel(): string
    {
        return 'Event';
    }
}
