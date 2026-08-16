<?php

namespace App\Filament\Resources\Attractions\Schemas;

use App\Models\Attraction;
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

class AttractionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลทั่วไป')
                    ->description('ชื่อ ประเภท และหมู่บ้านที่ตั้งของสถานที่')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('name')
                            ->label('ชื่อสถานที่')
                            ->required()
                            ->columnSpan(2),
                        Select::make('type')
                            ->label('ประเภท')
                            ->default(null)
                            ->options(Attraction::typeOptions()),
                        Select::make('village_id')
                            ->label('หมู่บ้าน')
                            ->options(Village::all()->pluck('name', 'id'))
                            ->default(null),
                    ])
                    ->columns(3),

                Section::make('รูปภาพ')
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
                                        '4:3',
                                        '16:9',
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
                            ->helperText('ลากเพื่อจัดลำดับ และกดปุ่มรูปดาวเพื่อตั้งเป็นรูปหน้าปก (มีได้แค่รูปเดียว รูปที่ตั้งจะถูกย้ายขึ้นบนสุดให้อัตโนมัติ) | แนะนำรูปแนวนอน ขนาดประมาณ 1200x900 พิกเซล (สัดส่วน 4:3) ใช้ปุ่มแก้ไขรูปเพื่อครอปก่อนบันทึกได้เลย')
                            ->columnSpanFull(),
                    ]),

                Section::make('เวลาและติดต่อ')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextInput::make('open_hours')
                            ->label('เวลาเปิด-ปิด')
                            ->default(null)
                            ->placeholder('เช่น 08:00 - 17:00'),
                        TextInput::make('contact')
                            ->label('ติดต่อ')
                            ->default(null)
                            ->placeholder('เบอร์โทรหรือช่องทางติดต่อ'),
                    ])
                    ->columns(2),

                Section::make('รายละเอียด')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        RichEditor::make('description')
                            ->label('รายละเอียด')
                            ->default(null)
                            ->columnSpanFull(),
                        RichEditor::make('how_to_get')
                            ->label('วิธีเดินทาง')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('ตำแหน่งบนแผนที่')
                    ->description('ใช้แสดงหมุดในหน้าเว็บและปุ่มนำทางด้วย Google Maps')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('latitude')
                            ->label('ละติจูด (Latitude)')
                            ->numeric()
                            ->default(null)
                            ->placeholder('เช่น 6.541200')
                            ->helperText('เปิด Google Maps หาตำแหน่งจริง คลิกขวาที่จุดนั้น แล้วคัดลอกตัวเลขแรกมาใส่'),
                        TextInput::make('longitude')
                            ->label('ลองจิจูด (Longitude)')
                            ->numeric()
                            ->default(null)
                            ->placeholder('เช่น 101.280300')
                            ->helperText('คัดลอกตัวเลขที่สองจาก Google Maps มาใส่'),
                    ])
                    ->columns(2),

                Section::make('การแสดงผลหน้าเว็บ')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('เปิดใช้งาน')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('ปักหมุดหน้าแรก')
                            ->disabled(fn () => ! auth()->user()->isSuperAdmin())
                            ->helperText('ถ้าไม่ปักหมุด ระบบจะสุ่มสถานที่ขึ้นหน้าแรกให้เองทุกครั้งที่มีคนเข้าเว็บ เพื่อความเป็นธรรมระหว่างสมาชิกในชุมชน (เฉพาะผู้ดูแลสูงสุดเท่านั้นที่ปักหมุดได้)'),
                    ])
                    ->columns(2),
            ]);
    }
}
