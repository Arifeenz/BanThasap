<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('รูปภาพสไลด์')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->label('รูปภาพ')
                            ->image()
                            ->disk('public')
                            ->required()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                            ])
                            ->automaticallyResizeImagesToWidth('2560')
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->helperText('แนะนำรูปแนวนอนกว้าง ขนาดอย่างน้อย 1920x1080 พิกเซล (สัดส่วน 16:9) เพื่อความคมชัดเต็มจอ ใช้ปุ่มแก้ไขรูปเพื่อครอปก่อนบันทึกได้เลย')
                            ->placeholder('ลากและวางไฟล์ หรือคลิกเพื่อเลือก')
                            ->columnSpanFull(),
                    ]),

                Section::make('ข้อความทับรูป')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->schema([
                        TextInput::make('title')
                            ->label('หัวข้อ (ทับรูป)')
                            ->default(null),
                        TextInput::make('subtitle')
                            ->label('คำบรรยาย (ทับรูป)')
                            ->default(null),
                    ])
                    ->columns(2),

                Section::make('การตั้งค่าการแสดงผล')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        TextInput::make('order')
                            ->label('ลำดับ (น้อยสุดแสดงก่อน)')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('duration')
                            ->label('ระยะเวลาแสดง (วินาที)')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(30)
                            ->suffix('วินาที'),
                        Toggle::make('is_active')
                            ->label('เปิดใช้งาน')
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
