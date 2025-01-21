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

    // Render method for the login view
    public function render()
    {
        return view('livewire.auth.admin-login');
    }

    // Mount method (optional for setting default values for testing)
    public function mount()
    {
        // Optional: Pre-fill the login credentials (for testing purposes)
        $this->fill(['email' => 'admin@gmail.com', 'password' => 'secret']);
    }

    // Method for logging in
    public function login()
    {
        // Validate input data
        $attributes = $this->validate();

        // Attempt to authenticate the user from the default users table
        if (! Auth::attempt($attributes)) {
            // If authentication fails, throw a validation exception
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.',
            ]);
        }
        // Regenerate the session to prevent session fixation attacks
        session()->regenerate();

        // Redirect to the intended page or dashboard after successful login
        return redirect()->route('dashboard'); // Adjust the route or path as needed
    }
}
