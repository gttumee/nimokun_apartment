<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerResource\Pages;
use App\Filament\Resources\OwnerResource\RelationManagers;
use App\Models\Owner;
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

class OwnerResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $modelLabel = 'オーナ一管理';
    protected static ?string $navigationGroup = '不動産管理';
    protected static ?int $navigationSort = 1;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label('お名前'),
                TextInput::make('email')->required()->label('メール')
                ->placeholder('example@mail.com'),
                TextInput::make('phone')->required()->label('連絡先')
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
                TextColumn::make('name')->label('お名前')
                ->sortable()
                ->searchable(),
                TextColumn::make('email')->label('メール')
                ->sortable()
                ->searchable()
                ->icon('heroicon-m-envelope'),
                TextColumn::make('phone')->label('連絡先')
                ->sortable()
                ->searchable()
                ->icon('heroicon-m-phone'),
                TextColumn::make('status')->label('ステータス')
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }
}