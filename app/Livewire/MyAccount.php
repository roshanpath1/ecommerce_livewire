<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My-Account ')]

class MyAccount extends Component
{
    public $name;
    public $email;

    public function mount(){
        $user=Auth::user();
        $this->name=$user->name;
        $this->email=$user->email;

    }
    // public function updateProfile(){
    //     $this->validate([
    //         'name'=>'required|string|max:255',
    //         'email'=>'required|email|unique:users,email|max:255',
    //     ]);
    //     $user=Auth::user();
    //     $user->name=$this->name;
    //       $user->email=$this->email;
    //     $this->$user->updateProfile();

    //     session()->flash('message','Profile updated successfully');

    //     return redirect()->route('home');
    // }



    public function render()
    {
        return view('livewire.my-account');
    }
}
