<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerContactResource\Pages;
use App\Filament\Resources\CustomerContactResource\RelationManagers;
use App\Models\Apartment;
use App\Models\CustomerContact;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerContactResource extends Resource
{
    protected static ?string $model = CustomerContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = '作業管理';
    protected static ?string $modelLabel = '問い合わせ依頼';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('id','=','2')->count();
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('apartment_id')
                ->searchPrompt('オーナーをお名前で検索してください')
                 ->relationship('apartment', 'apartment_id')
                 ->label('マンション名')
                 ->options(function () {
            return Apartment::get()->pluck('name', 'id');
             })
             ->live()
             ->searchable()
             ->required(),
             TextInput::make('name')->required()->label('部屋番号'),
             Textarea::make('info')->required()->label('問い合わせ内容'),
             Select::make('status')
             ->options([
                 '契約中' => '契約中',
                 '契約終了' => '契約終了',
                 '契約一時停止' => '契約一時停止',
             ])->label('ステータス')->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('apartment.name')
            ->label('マンション名'),
            TextColumn::make('name')->label('部屋番号')
            ->sortable()
            ->searchable(),
            TextColumn::make('updated_at')->label('日付')
            ->sortable()
            ->searchable(),
            TextColumn::make('status')->label('ステータス')
            ])
            
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCustomerContacts::route('/'),
            'create' => Pages\CreateCustomerContact::route('/create'),
            'edit' => Pages\EditCustomerContact::route('/{record}/edit'),
        ];
    }
}