<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FileUpload;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use FileUpload;

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate all incoming fields, including the 'type'
        $request->validate([
            'type'                  => ['required', 'in:student,instructor'],
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Determine role and approval status
        $role    = $request->input('type');
        $approve = $role === 'student' ? 'approved' : 'pending';

        // Base data for creating the user
        $data = [
            'name'           => $request->input('name'),
            'email'          => $request->input('email'),
            'password'       => Hash::make($request->input('password')),
            'role'           => $role,
            'approve_status' => $approve,
        ];

        // If instructor, handle document upload
        if ($role === 'instructor') {
            $request->validate([
                'document' => ['required', 'mimes:pdf,doc,docx,jpg,png', 'max:12000'],
            ]);

            $data['document'] = $this->uploadFile($request->file('document'));
        }

        // Create and log in the user
        $user = User::create($data);
        event(new Registered($user));
        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('instructor.dashboard');
    }
}
