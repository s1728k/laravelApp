<?php

namespace App\Traits;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath($rtype = "")
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($rtype);
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
