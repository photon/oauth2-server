<?php

namespace photon\auth\oauth2;
use photon\http\Request as PhotonRequest;

class Request extends \OAuth2\Request
{
    function __construct(PhotonRequest $request)
    {
        $this->initialize($request->GET, $request->POST, array(), $request->COOKIE, $request->FILES, (array) $request->mess->headers, $request->BODY, (array) $request->headers);
    }

    public function server($name, $default = null)
    {
        switch($name) {
            case 'REQUEST_METHOD':
                return strtolower($this->headers('METHOD', $default));

            case 'CONTENT_TYPE':
                return $this->headers('content-type', $default);

            default:
                return parent::server($name, $default);
        }
    }
}

