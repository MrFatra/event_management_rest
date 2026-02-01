<?php

namespace App\Filament\Resources\EventRatingResource\Pages;

use App\Filament\Resources\EventRatingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventRatings extends ListRecords
{
    protected static string $resource = EventRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
