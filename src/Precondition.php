<?php

namespace photon\auth\oauth2;
use photon\config\Container as Conf;

/*
 *  Verify the provided token is valid in the requested scope.
 */
class Precondition
{
    public static function noscope(&$request)
    {
        return self::_verify($request, null);
    }

    public static function _verify(&$request, $scope)
    {
        $server = Conf::f('oauth_server', null);
        if ($server === null) {
            throw new Exception;
        }

        $server = new $server;
        $oauthResponse = new \photon\auth\oauth2\Response;

        // Ensure the Token is valid for the requested scope
        $valid = $server->verifyResourceRequest($request, $oauthResponse, $scope);
        if ($valid === false) {
            return $oauthResponse;
        }

        // Store the token context in the photon request object
        $token = $server->getAccessTokenData($request, $oauthResponse);
        if ($token === null) {
            return $oauthResponse;
        }
        $request->oauthToken = $token;

        return true;
    }
}
