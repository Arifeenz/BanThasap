<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลบทความ')
                    ->icon('heroicon-o-newspaper')
                    ->schema([
                        TextInput::make('title')
                            ->label('หัวข้อ')
                            ->required()
                            ->columnSpan(2),
                        Select::make('category')
                            ->label('หมวดหมู่')
                            ->required()
                            ->default('news')
                            ->options(Post::categoryOptions()),
                    ])
                    ->columns(3),

                Section::make('รูปภาพ')
                    ->description('อัปโหลดได้หลายรูปตามที่ต้องการ เช่น รูปบรรยากาศงาน')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Repeater::make('images')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                FileUpload::make('image')
                                    ->label('รูปภาพ')
                                    ->image()
                                    ->disk('public')
                                    ->required()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                        null,
                                    ])
                                    ->placeholder('ลากและวางไฟล์ หรือคลิกเพื่อเลือก')
                                    ->columnSpanFull(),
                                Hidden::make('is_cover')
                                    ->default(false),
                            ])
                            ->extraItemActions([
                                Action::make('setCover')
                                    ->label('ตั้งเป็นรูปหน้าปก')
                                    ->icon('heroicon-m-star')
                                    ->visible(function (array $arguments, Get $get): bool {
                                        $items = $get('images') ?? [];

                                        return ! ($items[$arguments['item']]['is_cover'] ?? false);
                                    })
                                    ->action(function (array $arguments, Set $set, Get $get) {
                                        $items = $get('images') ?? [];
                                        $key = $arguments['item'];

                                        foreach ($items as $itemKey => $item) {
                                            $items[$itemKey]['is_cover'] = ($itemKey === $key);
                                        }

                                        // ย้ายรูปที่ตั้งเป็นปกขึ้นไปอยู่ลำดับแรกเสมอ
                                        $cover = $items[$key];
                                        unset($items[$key]);
                                        $items = [$key => $cover] + $items;

                                        $set('images', $items);
                                    }),
                            ])
                            ->itemLabel(fn (array $state): ?string => ($state['is_cover'] ?? false) ? '⭐ รูปหน้าปก' : null)
                            ->columns(2)
                            ->orderColumn('sort_order')
                            ->reorderable()
                            ->collapsible()
                            ->addActionLabel('เพิ่มรูปภาพ')
                            ->helperText('ลากเพื่อจัดลำดับ และกดปุ่มรูปดาวเพื่อตั้งเป็นรูปหน้าปก (มีได้แค่รูปเดียว รูปที่ตั้งจะถูกย้ายขึ้นบนสุดให้อัตโนมัติ) | แนะนำรูปแนวนอน ขนาดประมาณ 1200x675 พิกเซล (สัดส่วน 16:9) ใช้ปุ่มแก้ไขรูปเพื่อครอปก่อนบันทึกได้เลย')
                            ->columnSpanFull(),
                    ]),

                Section::make('เนื้อหา')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        RichEditor::make('content')
                            ->label('เนื้อหา')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('การเผยแพร่')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('เผยแพร่'),
                        DateTimePicker::make('published_at')
                            ->label('วันที่เผยแพร่'),
                    ])
                    ->columns(2),
            ]);
    }
}
