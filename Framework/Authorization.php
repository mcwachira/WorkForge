<?php

namespace Framework;

use Framework\Session;

class Authorization {


    /**
     *
     * Check if current logged-in user owns a resource
     *
     * @params int $resource
     * @param $resourceId
     * @return bool
     */

    public static function isOwner($resourceId): bool
    {
        $sessionUser = Session::get('user');
        if($sessionUser !== null && isset($sessionUser['id'])){
            $sessionUserId = (int) $sessionUser['id'];

            //inspectAndDie($sessionUserId);
            //inspectAndDie($sessionUser);
//            inspectAndDie($resourceId);
            return $sessionUserId === $resourceId;
        }
        return false;
    }
}
