<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstructorApprovedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->isInstructor() && !$user->is_approved) {
            return redirect()->route('instructor.pending');
        }

        return $next($request);
    }
}
