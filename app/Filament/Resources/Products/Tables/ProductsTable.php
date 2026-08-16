<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Concerns\HasPlaceholderImage;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    use HasPlaceholderImage;

    private static function categoryLabel(?string $category): ?string
    {
        return Product::categoryOptions()[$category] ?? $category;
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('image')
                        ->label('รูปภาพ')
                        ->disk('public')
                        ->size(48)
                        ->defaultImageUrl(self::placeholderImage())
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('name')
                            ->label('ชื่อสินค้า')
                            ->weight(FontWeight::SemiBold)
                            ->searchable(['name', 'category', 'unit', 'contact'])
                            ->description(fn ($record) => self::categoryLabel($record->category)),
                        TextColumn::make('price')
                            ->label('ราคา')
                            ->weight(FontWeight::SemiBold)
                            ->color('success')
                            ->formatStateUsing(fn ($state, $record) => $state !== null
                                ? '฿'.number_format($state, 0).($record->unit ? ' / '.$record->unit : '')
                                : null)
                            ->visible(fn ($record) => filled($record?->price)),
                        TextColumn::make('contact')
                            ->label('ติดต่อ')
                            ->icon('heroicon-m-phone')
                            ->color('gray')
                            ->visible(fn ($record) => filled($record?->contact)),
                        TextColumn::make('is_active')
                            ->label('เปิดใช้งาน')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'แสดงบนเว็บ' : 'ซ่อนจากเว็บ')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                        TextColumn::make('is_featured')
                            ->label('ปักหมุดหน้าแรก')
                            ->badge()
                            ->icon('heroicon-m-star')
                            ->formatStateUsing(fn () => 'ปักหมุดหน้าแรก')
                            ->color('warning')
                            ->visible(fn ($record) => (bool) $record?->is_featured),
                    ]),
                ]),
                TextColumn::make('price')
                    ->label('ราคา')
                    ->formatStateUsing(fn ($state) => $state !== null ? '฿'.number_format($state, 0) : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('md'),
                TextColumn::make('created_at')
                    ->label('สร้างเมื่อ')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('md'),
                TextColumn::make('updated_at')
                    ->label('แก้ไขเมื่อ')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('md'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->authorize(fn ($record) => auth()->user()->isSuperAdmin() || $record->created_by === auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
