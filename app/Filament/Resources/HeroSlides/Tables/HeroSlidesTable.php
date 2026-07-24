<?php

namespace App\Filament\Resources\HeroSlides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HeroSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                ImageColumn::make('image')
                    ->label('รูปภาพ')
                    ->disk('public'),
                TextColumn::make('title')
                    ->label('หัวข้อ')
                    ->searchable(),
                TextColumn::make('subtitle')
                    ->label('คำบรรยาย')
                    ->searchable(),
                TextColumn::make('order')
                    ->label('ลำดับ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('เวลาแสดง')
                    ->formatStateUsing(fn ($state) => $state . ' วินาที')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('เปิดใช้งาน')
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
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}