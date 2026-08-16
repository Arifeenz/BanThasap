<?php

namespace App\Filament\Resources\Attractions\Tables;

use App\Filament\Concerns\HasPlaceholderImage;
use App\Models\Attraction;
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

class AttractionsTable
{
    use HasPlaceholderImage;

    private static function typeLabel(?string $type): ?string
    {
        return Attraction::typeOptions()[$type] ?? $type;
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
                            ->label('ชื่อสถานที่')
                            ->weight(FontWeight::SemiBold)
                            ->searchable(['name', 'type', 'open_hours', 'contact'])
                            ->description(function ($record) {
                                return collect([
                                    self::typeLabel($record->type),
                                    $record->village?->name,
                                ])->filter()->join(' · ');
                            }),
                        TextColumn::make('open_hours')
                            ->label('เวลาเปิด-ปิด')
                            ->icon('heroicon-m-clock')
                            ->color('gray')
                            ->wrap()
                            ->visible(fn ($record) => filled($record?->open_hours)),
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
                TextColumn::make('village.name')
                    ->label('หมู่บ้าน')
                    ->searchable()
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
