<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthorChangePassword extends Component
{
    public $current_password, $new_password, $confirm_new_password;

    public function changePassword(){
        $this->validate([
            'current_password'=>[
                'required', function($attribute, $value, $fail){
                    if(!Hash::check($value, User::find(auth('web')->id())->password)){
                        return $fail(__('The current password is incorrect'));
                    }
                }
            ],
            'new_password'=>'required|min:5|max:25',
            'confirm_new_password'=>'same:new_password',
        ],[
            'current_password.required'=>'Enter your current password',
            'new_password.required'=>'Enter your new password',
            'confirm_new_password.same'=>'The confirm new password must be equal to new password',
        ]);

        $query = User::find(auth('web')->id())->update([
            'password'=>Hash::make($this->new_password)
        ]);

        if ($query) {
            session()->flash('success', 'Success update password');
            $this->current_password = $this->new_password = $this->confirm_new_password = null;
        }else {
            session()->flash('fail', 'Incorrect update password');
        }
    }

    public function render()
    {
        return view('livewire.author-change-password');
    }
}
