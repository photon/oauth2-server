<?php

class WrapperTest extends \photon\test\TestCase
{
    public function testRequest()
    {
        $request = \photon\test\HTTP::baseRequest();
        $obj = new \photon\auth\oauth2\Request($request);
    }

    public function testResponse()
    {
        $obj = new \photon\auth\oauth2\Response;
    }
}
