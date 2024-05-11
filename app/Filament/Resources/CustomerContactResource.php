<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerContactResource\Pages;
use App\Models\Apartment;
use App\Models\CustomerContact;
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
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Filters\SelectFilter;

class CustomerContactResource extends Resource
{
    protected static ?string $model = CustomerContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = '作業管理';
    protected static ?string $modelLabel = 'チャット';
    protected static ?int $navigationSort = 4;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status','=','未対応')->count().' リクエスト';

    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('status','=','未対応')->count() > 1 ? 'danger':'success';
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('apartment_id')
                ->searchPrompt('物件名で検索してください')
                 ->relationship('apartment', 'apartment_id')
                 ->label('物件名')
                 ->options(function () {
            return Apartment::get()->pluck('name', 'id');
             })
             ->searchable()
             ->required(),
             TextInput::make('room_number')->required()->label('部屋番号'),
             Select::make('status')
             ->options([
                 '未対応' => '未対応',
                 '対応中' => '対応中',
                 '対応完了' => '対応完了',
             ])->label('ステータス')->required(),
             TextInput::make('info')->required()->label('チャット内容'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('apartment.name')
                    ->label('物件名')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-home-modern'),
                    TextColumn::make('room_number')->label('部屋番号')
                        ->searchable()
                        ->sortable()
                        ->searchable()
                        ->getStateUsing(fn($record)=>'部屋番号: '.$record->room_number),
                    Panel::make([
                        Stack::make([
                        TextColumn::make('status')->label('ステータス'),
                        TextColumn::make('created_at')->label('チャット日付'),
                        TextColumn::make('info')->label('チャット内容')
                        ->weight(FontWeight::Bold)
                        ->sortable()
                        ->searchable()
                        ->icon('heroicon-o-chat-bubble-left')
                        ->getStateUsing(fn($record)=>$record->info),
                    ])
                ])->collapsed(true),
            ])
            
            ->filters([
                SelectFilter::make('status')
                ->label('ステータス')
                ->options([
                    '未対応' => '未対応',
                    '対応中' => '対応中',
                    '対応完了' => '対応完了',
                ])
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
        TextEntry::make('apartment.name')->label('物件名'),
        TextEntry::make('room_number')->label('部屋番号'),
        TextEntry::make('info')->label('チャット内容'),
        TextEntry::make('created_at')->label('チャット日付'),
        TextEntry::make('status')->label('ステータス'),

     ]);      
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerContacts::route('/'),
            'create' => Pages\CreateCustomerContact::route('/create'),
            'edit' => Pages\EditCustomerContact::route('/{record}/edit'),
        ];
    }
}