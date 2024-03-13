<?php

namespace App\Filament\Resources\VariablesResource\Pages;

use App\Filament\Resources\VariablesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariables extends EditRecord
{
    protected static string $resource = VariablesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
