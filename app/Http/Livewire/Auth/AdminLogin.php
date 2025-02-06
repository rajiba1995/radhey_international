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

        // Attempt to authenticate user
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        // Check if logged-in user has user_type = 0
        $user = Auth::user();
        if ($user->user_type !== 0) {
            Auth::logout(); // Logout immediately
            throw ValidationException::withMessages([
                'email' => 'You are not authorized to access this panel.',
            ]);
        }

        // Regenerate session to prevent session fixation
        session()->regenerate();

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }
}
