<?php

namespace App\Filament\Resources\Villages\Tables;

use App\Filament\Concerns\HasPlaceholderImage;
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

class VillagesTable
{
    use HasPlaceholderImage;

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
                            ->label('ชื่อหมู่บ้าน')
                            ->weight(FontWeight::SemiBold)
                            ->searchable(['name', 'highlight'])
                            ->description(function ($record) {
                                return collect([
                                    'หมู่ '.$record->number,
                                    $record->highlight,
                                ])->filter()->join(' · ');
                            }),
                        TextColumn::make('is_active')
                            ->label('เปิดใช้งาน')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'แสดงบนเว็บ' : 'ซ่อนจากเว็บ')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ]),
                ]),
                TextColumn::make('number')
                    ->label('หมู่ที่')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('md'),
            ])
            ->defaultSort('number')
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
