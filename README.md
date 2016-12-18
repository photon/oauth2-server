oauth2-server
=============

[![Build Status](https://travis-ci.org/photon/oauth2-server.svg?branch=master)](https://travis-ci.org/photon/oauth2-server)


Quick start
-----------

1) Add the module in your project

    composer require "photon/oauth2-server:dev-master"

or for a specific version

    composer require "photon/oauth2-server:1.0.0"


2) Create a OAuth2 server class 

You need to extends the abstract class "Server".
This class perform automatics convertion for $request and $response object.

    class MyOAuth2Server extends \photon\auth\oauth2\Server
    {
        protected function initializeServer(&$server)
        {
            $storage = new \OAuth2\Storage\Mongo();
            $server->addStorage($storage);
            $server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storage));
            ...
        }
    }

You use this class like the original "\OAuth2\Server".


