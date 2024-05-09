<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApartmentResource\Pages;
use App\Models\Apartment;
use App\Models\Owner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Panel;



class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $modelLabel = '物件管理';
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
           TextInput::make('name')->required()->label('物件名')
           ->placeholder('エンゼルマリン'),
           TextInput::make('room_count')->required()->label('部屋件数')
           ->placeholder('30')
           ->maxLength(6),
           Select::make('owner_id')
                    ->searchPrompt('オーナーで検索してください')
                    ->options(function () {
                        return Owner::get()->pluck('name', 'id');
                         })
                     ->label('オーナー')
                     ->live()
                     ->searchable()
                     ->required(),
                     FileUpload::make('image')
                     ->acceptedFileTypes(['image/jpeg'])
                     ->required()->label('画像/jpg'),
            TextInput::make('qr_text')->label('QRコード内容')
            ->placeholder('QRコードに表示する内容入力してください'),
        
            ]);     
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('name')
                    ->label('物件名')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-home-modern'),
                    TextColumn::make('owner.name')
                    ->label('オーナー')
                    ->icon('heroicon-m-user-circle'),
                    Panel::make([
                        Split::make([
                    TextColumn::make('room_count')->label('部屋件数')
                        ->weight(FontWeight::Bold)
                        ->searchable()
                        ->sortable()
                        ->searchable()
                        ->getStateUsing(fn($record)=>'部屋件数: '.$record->room_count),
                    ImageColumn::make('image')
                        ->label('画像')
                        ->square()
                        ])
                    ])->collapsed(true),
                
            ])
                        
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->label('詳細'),
                Tables\Actions\Action::make('QRコード')
                ->modalContent(function (Apartment $record){
                    $url = 'https://mediafiles.botpress.cloud/2809c371-b5ee-4e7d-82ef-0b0cfd915e91/webchat/bot.html';
                    $qrCode = QrCode::size(300)->generate($url); 
                     return view('qrcode',['qrCode' => $qrCode,'apatment_names' => $record->name, 'qr_text' => $record->qr_text]);
                    } )
                    ->modalAlignment(Alignment::Center)
                    ->label('QRコード')
                    ->icon('heroicon-m-qr-code'),
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
        TextEntry::make('name')->label('物件名'),
        TextEntry::make('owner.name')->label('オーナー'),
        TextEntry::make('room_count')->label('部屋件数'),
        TextEntry::make('image')->label('画像'),
     ]);   
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