<?php

namespace App\Livewire;

use App\Mail\ContactEmail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Contact-Page')]

class ContactPage extends Component
{
    public $name='';
    public $email='';
    public $message='';

    protected $rules=[
        'name'=>'required',
        'email'=>'required|email'
    ];

    public function save(){
        $this->validate();
        $mailData=[
            'subject'=>'you have received a contact email',
            'name'=>$this->name,
            'email'=>$this->email,
            'message'=>$this->message,
        ];
        Mail::to('admin@gmail.com')->send(new ContactEmail($mailData));
        session()->flash('Thanks for contacting,will get back to you soon.');
        $this->redirect('/contact');

    }

    public function render()
    {
        return view('livewire.contact-page');
    }
}
