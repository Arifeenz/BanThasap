<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // สร้าง Role
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin']);

        $email = env('SUPER_ADMIN_EMAIL', 'superadmin@thasap.com');

        if (User::where('email', $email)->exists()) {
            return;
        }

        $password = env('SUPER_ADMIN_PASSWORD');
        $isGenerated = blank($password);

        if ($isGenerated) {
            $password = Str::password(20);
        }

        $user = User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('super_admin');

        if ($isGenerated) {
            $this->command?->warn("สร้างบัญชี super admin: {$email}");
            $this->command?->warn("รหัสผ่านที่สุ่มให้: {$password}");
            $this->command?->warn('เก็บรหัสนี้ไว้ทันที ระบบจะไม่แสดงซ้ำอีก');
        }
    }
}
