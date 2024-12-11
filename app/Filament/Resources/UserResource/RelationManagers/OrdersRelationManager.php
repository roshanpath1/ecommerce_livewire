<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
// use Filament\Actions\Modal\Actions\Action;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('id')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {


        return $table
            ->recordTitleAttribute('id')
            ->columns([

                TextColumn::make('id')
                    ->label('Order ID')
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
                    // ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                 Action::make('View Order')
            ->url(fn(Order $record):string=>OrderResource::getUrl('view',['record'=>$record]))
            ->color('info')
            ->icon('heroicon-o-eye'),

                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

                 Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
