<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;

class TrustPilotProxy extends TrustProxies
{
    protected function setTrustedProxyIpAddresses(Request $request)
    {
        if (! config('pilot.enabled')) {
            parent::setTrustedProxyIpAddresses($request);

            return;
        }

        $proxy = config('pilot.trusted_proxy');
        if (filter_var($proxy, FILTER_VALIDATE_IP)) {
            $this->setTrustedProxyIpAddressesToSpecificIps($request, [$proxy]);
        }
    }

    protected function headers()
    {
        return config('pilot.enabled')
            ? Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_PORT
            : parent::headers();
    }
}
