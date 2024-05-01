<?php

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApartment extends CreateRecord
{
    protected static string $resource = ApartmentResource::class;
    protected static ?string $modelLabel = 'マンション登録';
    protected static ?string $navigationGroup = 'マンション管理';
    
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}