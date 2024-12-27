<?php
namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AdminLogin extends Component
{
    public $email = '';
    public $password = '';
    
    // Validation rules
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:4', // Add password minimum length
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
        // $this->fill(['email' => 'admin@gmail.com', 'password' => 'secret']);
    }

    // Method for logging in
    public function login()
    {
        // Validate input data
        $attributes = $this->validate();
        $user = User::where('email', $this->email)->first();
        // Attempt to authenticate the user from the default users table
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.',
            ]);
        }

        if ($user->user_type == 1) { // Admin login (assuming user_type 1 means admin)
            $creds = ['email' => $this->email, 'password' => $this->password];

            if (! Auth::guard('admin')->attempt($creds)) {
                throw ValidationException::withMessages([
                    'email' => 'Your provided credentials could not be verified.',
                ]);
            }

            session()->regenerate();
            return redirect()->route('admin.dashboard'); // Redirect to admin dashboard

        } elseif ($user->user_type == 0) { // Staff login (assuming user_type 0 means staff)
            // Use the default auth guard for staff
            $creds = ['email' => $this->email, 'password' => $this->password];

            if (! Auth::attempt($creds)) {
                throw ValidationException::withMessages([
                    'email' => 'Your provided credentials could not be verified.',
                ]);
            }

            session()->regenerate();
            return redirect()->route('product.view'); // Redirect to staff dashboard

        }else {
            throw ValidationException::withMessages([
                'email' => 'Invalid user type.',
            ]);
        }
        // Regenerate the session to prevent session fixation attacks
        session()->regenerate();

        // Redirect to the intended page or dashboard after successful login
        return redirect()->route('dashboard'); // Adjust the route or path as needed
    }
}
