<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลผู้ใช้')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->label('ชื่อ')
                            ->required(),
                        TextInput::make('email')
                            ->label('อีเมล')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label('รหัสผ่าน')
                            ->password()
                            ->required(fn ($record) => $record === null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('สิทธิ์การใช้งาน')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Select::make('roles')
                            ->label('สิทธิ์')
                            ->options(Role::all()->pluck('name', 'name'))
                            ->relationship('roles', 'name')
                            ->required(),
                    ]),
            ]);
    }
}
