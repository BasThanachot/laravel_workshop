<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Signin extends Component
{
    public $username;
    public $password;
    public $errorUsername;
    public $errorPassword;
    public $error = null;

    public function signin() {
        // รีเซ็ตค่าข้อผิดพลาด
        $this->errorUsername = null;
        $this->errorPassword = null;

        // ตรวจสอบข้อมูลที่กรอก
        $validator = Validator::make([
            'username' => $this->username,
            'password' => $this->password
        ], [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            // ตั้งค่าข้อผิดพลาด
            $this->errorUsername = $validator->errors()->get('username')[0] ?? null;
            $this->errorPassword = $validator->errors()->get('password')[0] ?? null;
        } else {
            // ค้นหาผู้ใช้ในฐานข้อมูล
            $user = User::where('name', $this->username)->first();

            if ($user && Hash::check($this->password, $user->password)) {
                // หากผู้ใช้ล็อกอินสำเร็จ ให้เก็บข้อมูลใน Session
                session()->put('user_id', $user->id);
                session()->put('user_name', $user->name);
                session()->put('user_lavel', $user->lavel);

                // เปลี่ยนเส้นทางไปยัง Dashboard
                return redirect('/dashboard');
            } else {
                // ข้อผิดพลาดการเข้าสู่ระบบ
                $this->error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            }
        }
    }

    public function render()
    {
        return view('livewire.signin');
    }
}
