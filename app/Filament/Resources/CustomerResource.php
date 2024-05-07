<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Apartment;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Panel;


class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = '顧客管理';
    protected static ?string $navigationGroup = '不動産管理';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
           TextInput::make('name')->required()->label('お名前'),
           Select::make('apartment_id')
           ->searchPrompt('物件名で検索してください')
           ->live()
           ->searchable()
           ->required()
            ->label('物件名')
            ->options(function () {
       return Apartment::get()->pluck('name', 'id');
        }),
           TextInput::make('room_number')->required()->label('部屋番号')
           ->maxLength(6)
           ->placeholder('900'),
           TextInput::make('phone')->required()->label('連絡先')
           ->regex('/^(0\d{1,4}-?\d{1,4}-?\d{3,4})$/')
           ->placeholder('000-0000-0000'),
           DatePicker::make('contract_start')->label('契約開始'),
           DatePicker::make('contract_end')->label('契約終了'),
           Select::make('status')
           ->options([
               '契約中' => '契約中',
               '契約終了' => '契約終了',
               '契約一時停止' => '契約一時停止',
           ])->label('ステータス')->required()
           ]);
       
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('name')
                    ->label('お名前')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-user'),
                    TextColumn::make('apartment.name')->label('物件名')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-home-modern'),
                    TextColumn::make('room_number')->label('部屋番号')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record)=>'部屋番号: '.$record->room_number),
                Panel::make([
                    Stack::make([
                    TextColumn::make('status')->label('ステータス')
                        ->sortable()
                        ->searchable(),
                    TextColumn::make('contract_start')->label('契約日付')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-calendar-days')
                    ->getStateUsing(fn($record)=>$record->contract_start.'～'.$record->contract_end),
                    ]),
                    TextColumn::make('phone')->label('連絡先')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                    ])->collapsed(true),   
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->label('詳細'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
           TextEntry::make('name')->label('お名前'),
           TextEntry::make('apartment.name')->label('物件名'),
           TextEntry::make('phone')->label('連絡先'),
           TextEntry::make('room_number')->label('部屋番号'),
           TextEntry::make('contract_start')->label('契約開始日'),
           TextEntry::make('contract_end')->label('契約終了日'),
           TextEntry::make('status')->label('ステータス'),
   
        ]);      
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}