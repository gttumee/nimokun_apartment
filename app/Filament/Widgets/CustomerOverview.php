<?php

namespace App\Filament\Widgets;

use App\Models\Apartment;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerOverview extends BaseWidget
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected function getStats(): array
    {
        return [
            Stat::make('顧客', Customer::count())
            ->description('契約中のお客さん')
            ->descriptionIcon('heroicon-o-user-group',IconPosition::Before)
            ->url('customers')
            ->chart([1,2,3,4,5,1,0,1,2,3])
            ->color('success'),
            Stat::make('物件', Apartment::count())
            ->description('現在の物件件数')
            ->descriptionIcon('heroicon-o-home-modern',IconPosition::Before)
            ->url('apartments'),
            Stat::make('問い合わせ', CustomerContact::count())
            ->description('新規問い合わせ依頼')
            ->descriptionIcon('heroicon-o-chat-bubble-left-right',IconPosition::Before)
            ->url('customer-contacts'),
        ];
    }
}