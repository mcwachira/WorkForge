<?php

namespace Framework\middleware;


use Framework\Session;

class Authorize
{

    /**
     * Check if user is authenticated
     *
     *
     * @return bool
     *
     */
    public function isAuthenticated(): bool
    {
        return Session::has('user');
    }


    /**
     * Handle User's request
     *
     * @param string $role
     *
     *
     *
     */

    public function handle($role)
    {
        if ($role === 'guest' && $this->isAuthenticated()) {
            return redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthenticated()) {
            return redirect('/auth/login');
        }

    }
}