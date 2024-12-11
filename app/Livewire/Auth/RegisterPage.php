<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Register')]

class RegisterPage extends Component
{
    use LivewireAlert;

    public $name;
    public $email;
    public $password;

    public function save(){
        $this->validate([
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|min:8|max:255',
        ]);
        $user=User::create([
            'name'=>$this->name,
            'email'=>$this->email,
            'password'=>Hash::make($this->password),
        ]);
        Auth::login($user);
        $this->alert('success', 'Registration successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'color'=>'green',
        ]);


        return redirect()->route('login');

    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
