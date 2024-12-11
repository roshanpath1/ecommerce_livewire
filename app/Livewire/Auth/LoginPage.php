<?php

namespace App\Livewire\Auth;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]


class LoginPage extends Component
{
    use LivewireAlert;


    public $email;
    public $password;



    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|min:8|max:255',
        ]);




        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid credentials.');
        }
        else
        {
            $this->alert('success', 'Login successfully!', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'color'=>'green',
            ]);
            return redirect()->route('home');

        }
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
