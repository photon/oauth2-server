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

    /**
     * Return claims about the authenticated end-user.
     * This would be called from the "/UserInfo" endpoint as defined in the spec.
     *
     * @param $request - photon\http\Request
     * Request object to grant access token
     *
     * @param $response - photon\http\Response
     * Response object containing error messages (failure) or user claims (success)
     *
     * @throws InvalidArgumentException
     * @throws LogicException
     *
     * @see http://openid.net/specs/openid-connect-core-1_0.html#UserInfo
     */
    public function handleUserInfoRequest(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->handleUserInfoRequest($oauthRequest, $oauthResponse);
    }

    /**
     * Grant or deny a requested access token.
     * This would be called from the "/token" endpoint as defined in the spec.
     * Obviously, you can call your endpoint whatever you want.
     *
     * @param $request - photon\http\Request
     * Request object to grant access token
     *
     * @param $response - photon\http\Response
     * Response object containing error messages (failure) or access token (success)
     *
     * @throws InvalidArgumentException
     * @throws LogicException
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4
     * @see http://tools.ietf.org/html/rfc6749#section-10.6
     * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
     *
     * @ingroup oauth2_section_4
     */
    public function handleTokenRequest(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->handleTokenRequest($oauthRequest, $oauthResponse);
    }

    public function grantAccessToken(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->grantAccessToken($oauthRequest, $oauthResponse);
    }

    /**
     * Handle a revoke token request
     * This would be called from the "/revoke" endpoint as defined in the draft Token Revocation spec
     *
     * @see https://tools.ietf.org/html/rfc7009#section-2
     *
     * @param $request - photon\http\Request
     *
     * @param $response - photon\http\Response
     */
    public function handleRevokeRequest(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->handleRevokeRequest($oauthRequest, $oauthResponse);
    }

    /**
     * Redirect the user appropriately after approval.
     *
     * After the user has approved or denied the resource request the
     * authorization server should call this function to redirect the user
     * appropriately.
     *
     * @param $request - photon\http\Request
     * The request should have the follow parameters set in the querystring:
     * - response_type: The requested response: an access token, an
     * authorization code, or both.
     * - client_id: The client identifier as described in Section 2.
     * - redirect_uri: An absolute URI to which the authorization server
     * will redirect the user-agent to when the end-user authorization
     * step is completed.
     * - scope: (optional) The scope of the resource request expressed as a
     * list of space-delimited strings.
     * - state: (optional) An opaque value used by the client to maintain
     * state between the request and callback.
     * @param $is_authorized
     * TRUE or FALSE depending on whether the user authorized the access.
     * @param $user_id
     * Identifier of user who authorized the client
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4
     *
     * @ingroup oauth2_section_4
     */
    public function handleAuthorizeRequest(PhotonRequest $request, PhotonResponse $response, $is_authorized, $user_id = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->handleAuthorizeRequest($oauthRequest, $oauthResponse, $is_authorized, $user_id);
    }

    /**
     * Pull the authorization request data out of the HTTP request.
     * - The redirect_uri is OPTIONAL as per draft 20. But your implementation can enforce it
     * by setting $config['enforce_redirect'] to true.
     * - The state is OPTIONAL but recommended to enforce CSRF. Draft 21 states, however, that
     * CSRF protection is MANDATORY. You can enforce this by setting the $config['enforce_state'] to true.
     *
     * The draft specifies that the parameters should be retrieved from GET, override the Response
     * object to change this
     *
     * @return
     * The authorization parameters so the authorization server can prompt
     * the user for approval if valid.
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.1.1
     * @see http://tools.ietf.org/html/rfc6749#section-10.12
     *
     * @ingroup oauth2_section_3
     */
    public function validateAuthorizeRequest(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->validateAuthorizeRequest($oauthRequest, $oauthResponse);
    }

    public function verifyResourceRequest(PhotonRequest $request, PhotonResponse $response = null, $scope = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->verifyResourceRequest($oauthRequest, $oauthResponse, $scope);
    }

    public function getAccessTokenData(PhotonRequest $request, PhotonResponse $response = null)
    {
        $oauthRequest = new PhotonOAuth2Request($request);
        $oauthResponse = new PhotonOAuth2Response($response);

        return $this->server->getAccessTokenData($oauthRequest, $oauthResponse);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->server, $name), $arguments);
    }
}
