<?php
namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLogin extends Component
{
    public $email = '';
    public $password = '';
    
    // Validation rules
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6', // Add password minimum length
    ];

    public function render()
    {
        return view('livewire.auth.admin-login');
    }

    public function mount()
    {
        $this->fill(['email' => 'admin@gmail.com', 'password' => 'secret']);
    }

    // Method for logging in
    public function login()
    {
        $attributes = $this->validate();

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.',
            ]);
        }

        $user = Auth::user();
        if($user->user_type == 0){
            return redirect()->route('dashboard');
        }elseif($user->user_type == 1){
            return redirect()->route('dashboard');
        }else{
             Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Unauthorized user type.',
            ]);
        }
        session()->regenerate();

        return redirect()->route('dashboard'); // Adjust the route or path as needed
    }
}
