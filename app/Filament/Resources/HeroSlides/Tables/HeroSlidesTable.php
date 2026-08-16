<?php

namespace App\Filament\Resources\HeroSlides\Tables;

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

class HeroSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('image')
                        ->label('รูปภาพ')
                        ->disk('public')
                        ->size(48)
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('title')
                            ->label('หัวข้อ')
                            ->weight(FontWeight::SemiBold)
                            ->searchable(['title', 'subtitle'])
                            ->description(function ($record) {
                                return collect([
                                    $record->subtitle,
                                    'ลำดับ '.$record->order,
                                    $record->duration.' วินาที',
                                ])->filter()->join(' · ');
                            }),
                        TextColumn::make('is_active')
                            ->label('เปิดใช้งาน')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'แสดงบนเว็บ' : 'ซ่อนจากเว็บ')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ]),
                ]),
            ])
            ->defaultSort('order')
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
