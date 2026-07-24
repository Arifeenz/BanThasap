<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('หัวข้อ')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('หมวดหมู่')
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'news' => 'ข่าวสาร',
                        'event' => 'กิจกรรม',
                        'announcement' => 'ประกาศ',
                        default => $state,
                    })
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('รูปภาพ')
                    ->disk('public'),
                IconColumn::make('is_published')
                    ->label('เผยแพร่แล้ว')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('วันที่เผยแพร่')
                    ->dateTime()
                    ->sortable(),
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