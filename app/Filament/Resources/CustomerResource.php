<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Apartment;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = '顧客一覧';
    protected static ?string $navigationGroup = '不動産管理';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
           TextInput::make('name')->required()->label('お名前'),
           TextInput::make('room_number')->required()->label('住所'),
           TextInput::make('phone')->required()->label('連絡先'),
           TextInput::make('contract')->required()->label('契約'),
           Select::make('apartment_id')
           ->searchPrompt('マンションをお名前で検索してください')
            ->relationship('apartment', 'apartment_id')
            ->label('マンション名')
            ->options(function () {
       return Apartment::get()->pluck('name', 'id');
        }),
           TextInput::make('status')->required()->label('ステータス'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('name')->label('お名前')
            ->sortable()
            ->searchable(),
            TextColumn::make('room_number')->label('部屋番号')
            ->sortable()
            ->searchable(),
            TextColumn::make('phone')->label('連絡先')
            ->sortable()
            ->searchable(),
            TextColumn::make('contract')->label('契約')
            ->sortable()
            ->searchable(),
            TextColumn::make('status')->label('情報')
            ->sortable()
            ->searchable(),
            TextColumn::make('apartment.name')->label('マンション名')
            ->sortable()
            ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}