<?php

namespace App\Controllers;

use App\Models\DBConnection;
use App\Models\User;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $pdo = DBConnection::getConnection();
        $user = User::findForAuth($pdo, $username);

        if (! $user) {
            return redirect()->back()->with('error', 'User nicht gefunden');
        }

        if (! password_verify($password, $user->getPasswordHash())) {
            return redirect()->back()->with('error', 'Passwort falsch');
        }

        session()->set([
            'user_id'   => $user->getID(),
            'username'  => $user->getUsername(),
            'logged_in' => true
        ]);

        $redirect = session()->get('redirect_after_login') ?? '/';
        session()->remove('redirect_after_login');

        return redirect()->to($redirect);

    }

    public function register()
    {
        // 1. Formulardaten
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (! $username || ! $password) {
            return redirect()->back()->with('error', 'Bitte alle Felder ausfüllen');
        }

        // 2. DB holen (statisch – völlig okay)
        $pdo = DBConnection::getConnection();

        // 3. Prüfen, ob User existiert
        $existingUser = User::findForAuth($pdo, $username);
        if ($existingUser) {
            return redirect()->back()->with('error', 'Username bereits vergeben');
        }

        // 4. Passwort hashen
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 5. User anlegen (je nach deinem Model)
        $user = new User($username, $passwordHash);

        // Speichern (Methodenname ggf. anpassen)
        $user->saveEntry($pdo);

        // 6. Session setzen (Auto-Login)
        session()->set([
            'user_id'   => $user->getID(),
            'username'  => $user->getUsername(),
            'logged_in' => true
        ]);

        // 7. Redirect
        return redirect()->to('/');
    }

    public function logout()
    {
        // komplette Session löschen
        session()->destroy();

        // optional: neue Session-ID erzwingen
        session()->regenerate(true);

        // zurück zur Auth-Seite
        return redirect()->to('/auth');
    }
}
