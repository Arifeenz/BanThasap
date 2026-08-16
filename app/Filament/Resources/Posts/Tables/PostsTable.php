<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Concerns\HasPlaceholderImage;
use App\Models\Post;
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

class PostsTable
{
    use HasPlaceholderImage;

    private static function categoryLabel(?string $category): ?string
    {
        return Post::categoryOptions()[$category] ?? $category;
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
                        TextColumn::make('title')
                            ->label('หัวข้อ')
                            ->weight(FontWeight::SemiBold)
                            ->searchable(['title', 'category'])
                            ->description(function ($record) {
                                return collect([
                                    self::categoryLabel($record->category),
                                    $record->published_at?->translatedFormat('j M Y'),
                                ])->filter()->join(' · ');
                            }),
                        TextColumn::make('is_published')
                            ->label('เผยแพร่แล้ว')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'เผยแพร่แล้ว' : 'ฉบับร่าง')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ]),
                ]),
                TextColumn::make('published_at')
                    ->label('วันที่เผยแพร่')
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
