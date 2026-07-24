<?php

namespace App\Filament\Resources\Villages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VillageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลหมู่บ้าน')
                    ->icon('heroicon-o-home-modern')
                    ->schema([
                        TextInput::make('number')
                            ->label('หมู่ที่')
                            ->required()
                            ->numeric(),
                        TextInput::make('name')
                            ->label('ชื่อหมู่บ้าน')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('highlight')
                            ->label('จุดเด่น')
                            ->default(null)
                            ->placeholder('เช่น แหล่งท่องเที่ยว, สินค้าชุมชน')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('ประวัติหมู่บ้าน')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        RichEditor::make('description')
                            ->label('ประวัติหมู่บ้าน')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('รูปภาพ')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->label('รูปภาพ')
                            ->image()
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '4:3',
                                '16:9',
                                '1:1',
                                null,
                            ])
                            ->helperText('แนะนำรูปแนวนอน ขนาดประมาณ 1200x900 พิกเซล (สัดส่วน 4:3) ใช้ปุ่มแก้ไขรูปเพื่อครอปก่อนบันทึกได้เลย')
                            ->columnSpanFull(),
                    ]),

                Section::make('ตำแหน่งบนแผนที่')
                    ->description('ใช้แสดงหมุดของหมู่บ้านในแผนที่ชุมชนและหน้าแรก')
                    ->icon('heroicon-o-globe-alt')
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
                    ]),
            ]);
    }
}
