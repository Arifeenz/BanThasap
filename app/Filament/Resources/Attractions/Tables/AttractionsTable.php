<?php

namespace App\Filament\Resources\Attractions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttractionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('ชื่อสถานที่')
                    ->searchable(),
                TextColumn::make('type')
                        ->label('ประเภท')
                        ->formatStateUsing(fn (string $state) => match($state) {
                            'nature' => 'ธรรมชาติ',
                            'history' => 'ประวัติศาสตร์',
                            'learning' => 'แหล่งเรียนรู้',
                            'community' => 'ชุมชน',
                            default => $state,
                        })
                        ->searchable(),
                TextColumn::make('open_hours')
                    ->label('เวลาเปิด-ปิด')
                    ->searchable(),
                TextColumn::make('contact')
                    ->label('ติดต่อ')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('รูปภาพ')
                    ->disk('public'),
                TextColumn::make('village.name')
                    ->label('หมู่บ้าน')
                    ->sortable(),
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