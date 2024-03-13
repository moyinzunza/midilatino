<?php

namespace App\Filament\Widgets;

use App\Models\Machines;
use App\Models\Products;
use App\Models\Purchases;
use App\Models\Txs;
use App\Models\Users;
use App\Models\Variables;
use App\Models\ViewsLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $widget_arr = array(); 

        $total_downloads = count(ViewsLog::where('type', 'download')->get());
        $total_license_view = count(ViewsLog::where('type', 'license_view')->get());
        $total_products = count(Products::get());

        array_push($widget_arr, Stat::make('Downloads', $total_downloads)
        ->url('/admin/views-logs?tableSearch=download')
        ->description('View Downloads')
        ->descriptionIcon('heroicon-o-archive-box-arrow-down')
        ->color('success'));

        array_push($widget_arr, Stat::make('License Views', $total_license_view)
        ->url('/admin/views-logs?tableSearch=license_view')
        ->description('License Views')
        ->descriptionIcon('heroicon-o-document-text')
        ->color('success'));

        array_push($widget_arr, Stat::make('Products', $total_products)
        ->url('/admin/products')
        ->description('View Products')
        ->descriptionIcon('heroicon-o-shopping-bag')
        ->color('success'));

        

        return $widget_arr;
    }
}
