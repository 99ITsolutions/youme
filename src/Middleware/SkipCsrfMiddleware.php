<?php

namespace App\Middleware;

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Core\Configure;

class SkipCsrfMiddleware extends CsrfProtectionMiddleware
{
    protected function _check(ServerRequestInterface $request): bool
    {
        // Get the requested URL
        $requestedUrl = $request->getRequestTarget();

        // Check if the requested URL matches the URL for which you want to skip CSRF token validation
        if ($requestedUrl === '/your-controller/your-action') {
            return true; // Skip CSRF check for this URL
        }

        return parent::_check($request);
    }
}

?>