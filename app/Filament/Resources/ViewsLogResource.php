<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViewsLogResource\Pages;
use App\Filament\Resources\ViewsLogResource\RelationManagers;
use App\Models\ViewsLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViewsLogResource extends Resource
{
    protected static ?string $model = ViewsLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('customer_id')->searchable(),
                Tables\Columns\TextColumn::make('customer_name')->searchable(), 
                Tables\Columns\TextColumn::make('customer_email')->searchable(), 
                Tables\Columns\TextColumn::make('order_id')->searchable(), 
                //Tables\Columns\TextColumn::make('product_id')->searchable(),
                Tables\Columns\TextColumn::make('product.title')->searchable(), 

                Tables\Columns\TextColumn::make('license.name')->searchable(),
                Tables\Columns\TextColumn::make('type')->searchable(),

                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListViewsLogs::route('/'),
            'create' => Pages\CreateViewsLog::route('/create'),
            'edit' => Pages\EditViewsLog::route('/{record}/edit'),
        ];
    }
}
