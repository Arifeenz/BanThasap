<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                            ->options([
                                'news' => 'ข่าวสาร',
                                'event' => 'กิจกรรม',
                                'announcement' => 'ประกาศ',
                            ]),
                    ])
                    ->columns(3),

                Section::make('เนื้อหา')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        RichEditor::make('content')
                            ->label('เนื้อหา')
                            ->required()
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
                                '16:9',
                                '4:3',
                                '1:1',
                                null,
                            ])
                            ->helperText('แนะนำรูปแนวนอน ขนาดประมาณ 1200x675 พิกเซล (สัดส่วน 16:9) ใช้ปุ่มแก้ไขรูปเพื่อครอปก่อนบันทึกได้เลย')
                            ->placeholder('ลากและวางไฟล์ หรือคลิกเพื่อเลือก')
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
