<?php

namespace App\Filters;

use App\Enums\UserType;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class KundeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nicht eingeloggt?
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth');
        }

        // Kein Kunde?
        $role = session()->get('role');
        if ($role !== UserType::KUNDE->value && $role !== UserType::KUNDE) {
            // Zurück zum Dashboard mit Fehlermeldung
            return redirect()->to('/')->with('error', 'Zugriff verweigert. Nur für Kunden.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nichts zu tun
    }
}
