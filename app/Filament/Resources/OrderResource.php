<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\count;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\ToggleButtons;
// use Filament\Forms\Components\TextInput\sortable;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('payment_method')
                            ->options([
                                'stripe' => 'Stripe', //online payment
                                'cod' => 'Cash On Delivery',
                                'esewa'=>'Esewa',
                            ])->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending', //payment is initiated but not yet completed.
                                'paid' => 'Paid', //payment was successfully processed.
                                'failed' => 'Failed', //payment failed due to error.
                            ])->required()
                            ->default('pending'),

                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing', //the order is being prepared for shipment.
                                'shipped' => 'Shipped', //the item has been dispatchd and is on its way.
                                'delivered' => 'Delivered', //the customer has recieved the items.
                                'cancelled' => 'Cancelled', //the item has been cancelled.
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning', //the order is being prepared for shipment.
                                'shipped' => 'success', //the item has been dispatchd and is on its way.
                                'delivered' => 'success', //the customer has recieved the items.
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path', //the order is being prepared for shipment.
                                'shipped' => 'heroicon-m-truck', //the item has been dispatchd and is on its way.
                                'delievered' => 'heroicon-m-check-badge', //the customer has recieved the items.
                                'cancelled' => 'heroicon-m-x-circle',
                            ]),
                        Select::make('currency')
                            ->options([
                                'npr' => 'NPR',
                                'usd' => 'USD',
                                'euro' => 'EURO'
                            ])
                            ->required()
                            ->default('npr'),

                        Select::make('shipping_method')
                            ->options([
                                'fedex' => 'FedEx', //Federal Express[2-7 delievery time ]
                                'ups' => 'UPS', //United Parcel Services[3-10 businness days]
                                'courier' => 'Courier',
                                'pathao' => 'Pathao',
                            ]),

                        Textarea::make('notes')
                            ->columnSpanFull()

                    ])->columns(2),
                    Section::make('Order_Items')->schema([
                        Repeater::make('items')
                            ->relationship()->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0)),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),

                                TextInput::make('unit_amount')
                                    ->numeric()

                                    ->required()->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),

                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3)

                            ])->columns(12),
                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get('items')) {
                                    return $total;
                                }
                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.total_amount");
                                }
                                $set('grand_total', $total);
                                return Number::currency($total, 'NPR');
                            }),
                        Hidden::make('grand_total')
                            ->default(0),

                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->money('NPR'),
                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('currency')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('shipping_method')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])

                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),


                ])

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
            AddressRelationManager::class
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
