<?php

namespace App\Filament\Resources\CustomerContactResource\Pages;

use App\Filament\Resources\CustomerContactResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerContact extends CreateRecord
{
    protected static string $resource = CustomerContactResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}