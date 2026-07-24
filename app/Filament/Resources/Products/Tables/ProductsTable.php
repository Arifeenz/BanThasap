<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('รูปภาพ')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('ชื่อสินค้า')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('หมวดหมู่')
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'food' => 'อาหาร',
                        'handicraft' => 'หัตถกรรม',
                        'health' => 'สุขภาพ',
                        'other' => 'อื่นๆ',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('price')
                    ->label('ราคา')
                    ->formatStateUsing(fn ($state) => $state ? '฿' . number_format($state, 0) : '-')
                    ->sortable(),
                TextColumn::make('unit')
                    ->label('หน่วย')
                    ->searchable(),
                TextColumn::make('contact')
                    ->label('ติดต่อ')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('เปิดใช้งาน')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('ปักหมุดหน้าแรก')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('สร้างเมื่อ')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('แก้ไขเมื่อ')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->authorize(fn ($record) => auth()->user()->hasRole('super_admin') || $record->created_by === auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}