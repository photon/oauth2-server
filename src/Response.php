<?php

namespace photon\auth\oauth2;
use photon\http\Response as PhotonResponse;
use OAuth2\ResponseInterface;

class Response extends PhotonResponse implements ResponseInterface
{
    public function addParameters(array $parameters)
    {
        if ($this->content && $data = json_decode($this->content, true)) {
            $parameters = array_merge($data, $parameters);
        }

        $this->content = json_encode($parameters);
    }

    public function getParameter($name)
    {
        if ($this->content && $data = json_decode($this->content, true)) {
            return isset($data[$name]) ? $data[$name] : null;
        }
    }

    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;
    }

    public function addHttpHeaders(array $httpHeaders)
    {
        $this->headers = array_merge($this->headers, $httpHeaders); 
    }

    public function setError($statusCode, $error, $description = null, $uri = null)
    {
        $this->status_code = $statusCode;
        $this->addParameters(array_filter(array(
            'error'             => $error,
            'error_description' => $description,
            'error_uri'         => $uri,
        )));
    }

    public function setRedirect($statusCode = 302, $url, $state = null, $error = null, $errorDescription = null, $errorUri = null)
    {
        $this->status_code = $statusCode;

        $params = array_filter(array(
            'state'             => $state,
            'error'             => $error,
            'error_description' => $errorDescription,
            'error_uri'         => $errorUri,
        ));

        if ($params) {
            $parts = parse_url($url);
            $sep = isset($parts['query']) && count($parts['query']) > 0 ? '&' : '?';
            $url .= $sep . http_build_query($params);
        }

        $this->headers['Location'] = $url;
    }
}

