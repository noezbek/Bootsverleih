<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\DBConnection;
use App\Models\Kunde;
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

        session()->regenerate();

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
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $passwordRepeat = $this->request->getPost('password_repeat');
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $birthday = $this->request->getPost('birthday');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $street = ($this->request->getPost('adress')) ?? null;
        $zip = $this->request->getPost('zip') ?? null;
        $city = $this->request->getPost('city') ?? null;

        if ((Helper::isAnyEmpty($username, $password, $passwordRepeat, $firstName, $lastName, $email, $phone))) {
            return redirect()->back()->with('error', 'Bitte alle Felder ausfüllen');
        }

        if ($password !== $passwordRepeat) {
            return redirect()->back()->with('error', 'Passwörter stimmen nicht überein');
        }

        $pdo = DBConnection::getConnection();

        $existingUser = User::findForAuth($pdo, $username);
        if ($existingUser) {
            return redirect()->back()->with('error', 'Username bereits vergeben');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $kunde = new Kunde($firstName, $lastName, $email, $birthday, $phone, $street, $zip, $city);
        $kunde->saveEntry($pdo);
        $kundeID = $kunde->getID();

        $user = new User($username, $passwordHash, $kundeID);

        $user->saveEntry($pdo);

        session()->set([
            'user_id'   => $user->getID(),
            'username'  => $user->getUsername(),
            'logged_in' => true
        ]);

        return redirect()->to('/');
    }

    public function logout()
    {
        // komplette Session löschen
        session()->destroy();

        // zurück zur Auth-Seite
        return redirect()->to('/auth');
    }
}
