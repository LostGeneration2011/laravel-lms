<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
{
    $user = $request->user();

    // Nếu đã verify rồi thì chỉ redirect tiếp
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    // Chọn route dựa vào role
    $routeName = $user->role === 'instructor'
        ? 'instructor.dashboard'
        : 'student.dashboard';

    // Redirect kèm thông số ?verified=1
    return redirect()->route($routeName, ['verified' => 1]);
}

}
