<?php

namespace App\Filament\Resources\VariablesResource\Pages;

use App\Filament\Resources\VariablesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariables extends ListRecords
{
    protected static string $resource = VariablesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
