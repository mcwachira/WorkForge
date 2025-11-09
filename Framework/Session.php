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


    /**
     *
     * Set a flash message
     *
     * @params string $key
     * @param $key
     * @param string $message
     * @return  void
     *
     */


    public static function setFlashMessage(string $key, string $message): void
    {
        self::set('flash_' . $key, $message);
    }


    /**
     *
     * Get a flash message
     *
     * @params string $key
     * @param $key
     * @param mixed|null $default
     * @return  string
     */


    public static function getFlashMessage(string $key, mixed $default = null )
    {

        $sessionKey = 'flash_' . $key;

        $message = self::get($sessionKey, $default);

        self::clear($sessionKey);

        return $message;
    }
}