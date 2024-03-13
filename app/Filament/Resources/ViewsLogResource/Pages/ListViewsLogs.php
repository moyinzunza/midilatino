<?php

namespace App\Filament\Resources\ViewsLogResource\Pages;

use App\Filament\Resources\ViewsLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListViewsLogs extends ListRecords
{
    protected static string $resource = ViewsLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
