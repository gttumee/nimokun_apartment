<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerContactResource\Pages;
use App\Models\Apartment;
use App\Models\CustomerContact;
use App\Models\RequestVendor;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
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
                 '業者に依頼中' => '業者に依頼中',
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
                    TextColumn::make('status')->label('ステータス')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '未対応' => 'danger',
                        '対応中' => 'warning',
                        '対応完了' => 'success',
                        '業者に依頼中' => 'info',
                        default => 'gray',
                    }),
                    Panel::make([
                        Stack::make([
                        TextColumn::make('created_at')->label('チャット日付'),
                        TextColumn::make('info')->label('チャット内容')
                        ->weight(FontWeight::Bold)
                        ->sortable()
                        ->searchable()
                        ->icon('heroicon-o-chat-bubble-left')
                        ->getStateUsing(fn($record)=>$record->info),
                    ])
                ])->collapsed(true)
            ])
            ->defaultSort('status', 'desc')
            ->recordUrl(null)
            ->filters([
                SelectFilter::make('status')
                ->label('ステータス')
                ->options([
                    '未対応' => '未対応',
                    '対応中' => '対応中',
                    '対応完了' => '対応完了',
                    '業者に依頼中' => '業者に依頼中',
                ])
                ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->label('詳細'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('業者に依頼する')
                ->form([
                    Select::make('service')
                        ->label('業者')
                        ->multiple()
                        ->options(Service::query()->pluck('name', 'id'))
                        ->required(),
                        RichEditor::make('work_contents')->required()->label('作業内容'),
                        DatePicker::make('desired_start')->label('希望開始日'),
                        DatePicker::make('desired_end')->label('希望終了日'),
                        TextInput::make('price')->required()->label('金額')
                ])
                ->action(function (CustomerContact $record,array $data): void {
                    $vendorRequest = new RequestVendor();
                    $vendorRequest->service_id = $data['service'];
                    $vendorRequest->work_contents = $data['work_contents'];
                    $vendorRequest->desired_start = $data['desired_start'];
                    $vendorRequest->desired_end = $data['desired_end'];
                    $vendorRequest->price = $data['price'];
                    $vendorRequest->save();
                    $order = CustomerContact::find($record->id); 
                    $order->status = '業者に依頼中'; 
                    $order->save();
                    Notification::make()
                     ->success()
                     ->title('依頼を送付しました!')
                     ->icon('heroicon-o-check')
                     ->send();
      
                })
                ->modalSubmitActionLabel('メールで送信')
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