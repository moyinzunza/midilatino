<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\Products;
use App\Models\Licenses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('download_url')->label('URL de descarga'),
                Forms\Components\TextInput::make('file_size')->label('Tamaño de archivo'),
                Forms\Components\Select::make('license_id')
                ->searchable()
                ->options(function (callable $get, callable $set) {
                    return Licenses::get()->pluck('name', 'id');
                }),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('product_id')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable(), 
                Tables\Columns\ImageColumn::make('image_url'), 
                Tables\Columns\TextColumn::make('license.name')->searchable(),
                Tables\Columns\TextColumn::make('license.id')->label('')->formatStateUsing(fn ($record) => __("<a href='https://midilatino.portaldev.xyz/preview_license/{$record->product_id}' target='_blank'>Preview</a>"))->html()->searchable(),
                    
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                /*
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),*/
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}
