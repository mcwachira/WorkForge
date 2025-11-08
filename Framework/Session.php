<?php


namespace Framework;

class Session {
    /**
     *
     * Start Session
     *
     * @return void
     */

    public static function start(){

        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

}


/**
 *
 * Set a session key/value pair
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */

public static function set(string $key, $value): mixed
{
    return $_SESSION[$key] = $value;
}



    /**
     *
     * Get a session value by the key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */

    public static function get(string $key, $default = null): mixed
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;

    }




    /**
     *
     * CHECK IF SESSION KEY EXIST
     *
     * @param string $key
     * @return bool
     *
     */

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);

    }

    /**
     *
     * Clear session by key
     *
     * @param string $key
     * @return  void
     *
     */

    public static function clear(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

    }
        /**
         *
         * Clear all session data
         *
         *
         * @return  void
         *
         */

        public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }

}