<?php

namespace App\Filament\Resources\ViewsLogResource\Pages;

use App\Filament\Resources\ViewsLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditViewsLog extends EditRecord
{
    protected static string $resource = ViewsLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
