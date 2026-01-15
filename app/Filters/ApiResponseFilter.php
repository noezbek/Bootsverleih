<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiResponseFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // nichts
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nur JSON-Antworten anfassen
        if ($response->getHeaderLine('Content-Type') !== 'application/json') {
            return $response;
        }

        $body = $response->getBody();
        $decoded = json_decode($body, true);

        // Bereits im API-Format? Dann nix tun
        if (isset($decoded['success'])) {
            return $response;
        }

        return $response->setJSON([
            'success' => true,
            'data'    => $decoded,
        ]);
    }
}
