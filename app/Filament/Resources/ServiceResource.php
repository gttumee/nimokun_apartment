<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
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



class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = '作業管理';
    protected static ?string $modelLabel = '業者管理';
    protected static ?int $navigationSort = 5;
    protected function getRedirectUrl(){
        return $this->getResource()::getUrl('index');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->label('事業名')
                ->placeholder('○○株式会社'),
                TextInput::make('address')
                ->required()
                ->label('住所')
                ->placeholder('兵庫県神戸市兵庫区荒田町3丁目14-7'),
                TextInput::make('phone')
                ->required()
                ->label('連絡先')
                ->regex('/^(0\d{1,4}-?\d{1,4}-?\d{3,4})$/')
                ->placeholder('000-0000-0000'),
                Select::make('status')
                ->options([
                    '契約中' => '契約中',
                    '契約終了' => '契約終了',
                    '契約一時停止' => '契約一時停止',
                ])->label('ステータス')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('name')->label('事業名')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-wrench-screwdriver'),
                    TextColumn::make('status')->label('ステータス')
                    ->sortable()
                    ->searchable()
                    ->visibleFrom('md'),
                Panel::make([
                    Split::make([
                    TextColumn::make('address')->label('住所')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-home'),
                    TextColumn::make('phone')->label('連絡先')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ])
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
           TextEntry::make('phone')->label('連絡先'),
           TextEntry::make('address')->label('住所'),
           TextEntry::make('status')->label('ステータス'),
   
        ]);      
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}