<?php

namespace App\Filters;

use App\Enums\UserType;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class MitarbeiterFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nicht eingeloggt?
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth');
        }

        // Kein Mitarbeiter?
        $role = session()->get('role');
        if ($role !== UserType::MITARBEITER->value && $role !== UserType::MITARBEITER) {
            // Zurück zum Dashboard mit Fehlermeldung
            return redirect()->to('/')->with('error', 'Zugriff verweigert. Nur für Mitarbeiter.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nichts zu tun
    }
}
