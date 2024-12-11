<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int| string |array $columnSpan='full';
    protected static ?int $sort=2;
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery() )
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at','desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),
                    TextColumn::make('user.name')
                    ->searchable(),
                    TextColumn::make('grand_total')
                    ->money('NPR'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'cancelled' => 'danger',
                        'delivered'=>'success',
                    })
                    ->icon(fn(string $state): string => match ($state) {


                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path', //the order is being prepared for shipment.
                        'shipped' => 'heroicon-m-truck', //the item has been dispatchd and is on its way.
                        'delivered' => 'heroicon-m-check-badge', //the customer has recieved the items.
                        'cancelled' => 'heroicon-m-x-circle',
                    })
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->badge()
                    ->sortable(),

                    TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ])

                    ->actions([
                        Action::make('View Order')
                   ->url(fn(Order $record):string=>OrderResource::getUrl('view',['record'=>$record]))
                   ->color('info')
                   ->icon('heroicon-o-eye'),

                       // Tables\Actions\EditAction::make(),
                    //    Tables\Actions\DeleteAction::make(),

            ]);
    }
}
