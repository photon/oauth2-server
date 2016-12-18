<?php

namespace photon\auth\oauth2;
use photon\config\Container as Conf;

/*
 *  Verify the provided token is valid in the requested scope.
 *  The requested scope is the name of the function called
 */
class Precondition
{
    public static function noscope($request)
    {
        return self::_verify($request, null);
    }

    public static function __callStatic($name, $arguments)
    {
        return forward_static_call_array(array(__CLASS__, '_verify'), array($arguments[0], $name));
    }

    public static function _verify($request, $scope)
    {
        $server = Conf::f('oauth_server', null);
        if ($server === null) {
            throw new Exception;
        }

        $server = new $server;
        $oauthRequest = new \photon\auth\oauth2\Request($request);
        $oauthResponse = new \photon\auth\oauth2\Response;

        $valid = $server->verifyResourceRequest($oauthRequest, $oauthResponse, $scope);
        if ($valid === false) {
            return $oauthResponse;
        }

        return true;
    }
}
