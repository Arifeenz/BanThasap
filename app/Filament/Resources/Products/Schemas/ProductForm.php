<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use App\Models\Village;
use Filament\Actions\Action;
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

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลสินค้า')
                    ->description('ชื่อ หมวดหมู่ และรายละเอียดของสินค้า')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        TextInput::make('name')
                            ->label('ชื่อสินค้า')
                            ->required()
                            ->columnSpan(2),
                        Select::make('category')
                            ->label('หมวดหมู่')
                            ->default(null)
                            ->options(Product::categoryOptions())
                            ->columnSpan(1),
                        Select::make('village_id')
                            ->label('หมู่บ้าน')
                            ->options(Village::all()->pluck('name', 'id'))
                            ->default(null)
                            ->helperText('ใช้ปักหมุดตำแหน่งสินค้านี้บนแผนที่ชุมชน (ระดับหมู่บ้าน)')
                            ->columnSpan(2),
                        RichEditor::make('description')
                            ->label('รายละเอียด')
                            ->default(null)
                            ->columnSpanFull(),
                        RichEditor::make('story')
                            ->label('เรื่องราวสินค้า')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('รูปภาพสินค้า')
                    ->description('อัปโหลดได้หลายรูปตามที่ต้องการ')
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
                                        '1:1',
                                        null,
                                    ])
                                    ->automaticallyResizeImagesToWidth('1400')
                                    ->automaticallyResizeImagesMode('contain')
                                    ->automaticallyUpscaleImagesWhenResizing(false)
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
                            ->helperText('ลากเพื่อจัดลำดับ และกดปุ่มรูปดาวเพื่อตั้งเป็นรูปหน้าปก (มีได้แค่รูปเดียว รูปที่ตั้งจะถูกย้ายขึ้นบนสุดให้อัตโนมัติ) | แนะนำรูปสี่เหลี่ยมจตุรัส (1:1) ขนาดประมาณ 1000x1000 พิกเซล ใช้ปุ่มแก้ไขรูปเพื่อครอปก่อนบันทึกได้เลย')
                            ->columnSpanFull(),
                    ]),

                Section::make('ราคาและการติดต่อ')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        TextInput::make('price')
                            ->label('ราคา')
                            ->numeric()
                            ->default(null)
                            ->prefix('฿'),
                        TextInput::make('unit')
                            ->label('หน่วย')
                            ->default(null)
                            ->placeholder('เช่น ชิ้น, กล่อง, โหล'),
                        TextInput::make('contact')
                            ->label('ติดต่อ')
                            ->default(null)
                            ->placeholder('เบอร์โทรหรือช่องทางติดต่อ'),
                    ])
                    ->columns(3),

                Section::make('การแสดงผลหน้าเว็บ')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('เปิดใช้งาน')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('ปักหมุดหน้าแรก')
                            ->disabled(fn () => ! auth()->user()->isSuperAdmin())
                            ->helperText('ถ้าไม่ปักหมุด ระบบจะสุ่มสินค้าขึ้นหน้าแรกให้เองทุกครั้งที่มีคนเข้าเว็บ เพื่อความเป็นธรรมระหว่างสมาชิกในชุมชน (เฉพาะผู้ดูแลสูงสุดเท่านั้นที่ปักหมุดได้)'),
                    ])
                    ->columns(2),
            ]);
    }
}
