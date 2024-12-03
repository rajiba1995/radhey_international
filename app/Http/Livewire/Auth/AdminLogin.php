<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLogin extends Component
{
    public $email = '';
    public $password = '';
    
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function render()
    {
        return view('livewire.auth.admin-login');
    }

    public function mount()
    {
        // Optional: Pre-fill the login credentials (for testing purposes)
        $this->fill(['email' => 'admin@gmail.com', 'password' => 'secret']);
    }

    public function login()
    {
        // Validate input data
        $attributes = $this->validate();

        // Attempt to authenticate the admin user
        if (! Auth::guard('admin')->attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.'
            ]);
        }

        // Regenerate the session to prevent session fixation attacks
        session()->regenerate();

        // Redirect to the admin dashboard
        return redirect()->route('dashboard');
    }
}
