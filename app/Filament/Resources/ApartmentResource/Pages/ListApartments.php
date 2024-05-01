<?php

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApartments extends ListRecords
{
    protected static string $resource = ApartmentResource::class;
    protected static ?int $navigationSort = 1;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('新規登録'),
        ];
    }
}