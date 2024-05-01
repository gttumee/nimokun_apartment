<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApartmentResource\Pages;
use App\Filament\Resources\ApartmentResource\RelationManagers;
use App\Models\Apartment;
use App\Models\Owner;
use Doctrine\DBAL\Schema\View;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\View\View as ViewView;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $modelLabel = 'マンション一覧';
    protected static ?string $navigationGroup = '不動産管理';
    protected static ?int $navigationSort = 2;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
           TextInput::make('name')->required()->label('お名前'),
           TextInput::make('address')->required()->label('住所'),
           TextInput::make('image')
           ->required()->label('画像'),
           Select::make('status')
                ->options([
                    '契約中' => '契約中',
                    '契約終了' => '契約終了',
                    '契約一時停止' => '契約一時停止',
                ])->label('ステータス')->required(),
                Select::make('owner_id')
                    ->searchPrompt('オーナーをお名前で検索してください')
                     ->relationship('owner', 'owner_id')
                     ->label('オーナー')
                     ->options(function () {
                return Owner::get()->pluck('name', 'id');
                 })
                     ->live()
                     ->searchable()
                     ->required()
            ]);     
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('name')->label('お名前')
            ->sortable()
            ->searchable(),
            TextColumn::make('address')->label('住所')
            ->sortable()
            ->searchable(),
            TextColumn::make('owner.name')
            ->label('オーナー'),
            ImageColumn::make('avatar'),
            TextColumn::make('status')->label('ステータス')
            ->sortable()
            ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('QRコード表示')
                ->modalContent(function (Apartment $record){
                    $url = 'https://mediafiles.botpress.cloud/2809c371-b5ee-4e7d-82ef-0b0cfd915e91/webchat/bot.html/'.$record->id;
                    $qrCode = QrCode::size(500)->generate($url); 
                     return view('qrcode',['qrCode' => $qrCode,'apatment_names' => $record->name]);
                    } )
                    ->modalAlignment(Alignment::Center)
                    ->label('QRコード表示'),
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
            'index' => Pages\ListApartments::route('/'),
            'create' => Pages\CreateApartment::route('/create'),
            'edit' => Pages\EditApartment::route('/{record}/edit'),
        ];
    }
}