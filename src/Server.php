<?php

namespace photon\auth\oauth2;

use photon\http\Request as PhotonRequest;
use photon\http\Response as PhotonResponse;
use photon\auth\oauth2\Request as PhotonOAuth2Request;
use photon\auth\oauth2\Response as PhotonOAuth2Response;

abstract class Server
{
    protected $server = null;

    abstract protected function initializeServer(&$server);

    public function __construct()
    {
        $this->server = new \OAuth2\Server;
        $this->initializeServer($this->server);
    }

    public function validateAuthorizeRequest(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->validateAuthorizeRequest($oauthRequest, $oauthResponse);
    }

    public function handleAuthorizeRequest(PhotonRequest $request, PhotonResponse $response, $is_authorized, $user_id = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->handleAuthorizeRequest($oauthRequest, $oauthResponse, $is_authorized, $user_id);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->server, $name), $arguments);
    }
}
