<?php

namespace App\Controllers;

use App\Enums\UserType;
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

        if (!$user->verifyPassword($password)) {
            return redirect()->back()->with('error', 'Passwort falsch');
        }

        session()->regenerate();

        session()->set([
            'user_id'   => $user->getID(),
            'kunde_id'   => $user->getKunde() ?? null,
            'role'   => $user->getUserType(),
            'mitarbeiter_id'=> $user->getMitarbeiter() ?? null,
            'username'  => $user->getUsername(),
            'logged_in' => true
        ]);

        $redirect = session()->get('redirect_after_login') ?? '/';
        session()->remove('redirect_after_login');

        return redirect()->to($redirect);

    }

    public function register()
    {
        $data = $this->request->getPost(['username', 'password', 'password_repeat', 'first_name', 'last_name', 'birthday', 'email', 'phone', 'adress', 'zip', 'city',]);

        $username        = $data['username'];
        $password        = $data['password'];
        $passwordRepeat  = $data['password_repeat'];
        $firstName       = $data['first_name'];
        $lastName        = $data['last_name'];
        $birthday        = $data['birthday'];
        $email           = $data['email'];
        $phone           = $data['phone'];
        $street          = $data['adress'] ?? null;
        $zip             = $data['zip'] ?? null;
        $city            = $data['city'] ?? null;


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

        $user = new User($username, $passwordHash, UserType::KUNDE->value, $kundeID);

        $user->saveEntry($pdo);

        session()->set([
            'user_id'   => $user->getID(),
            'kunde_id'   => $user->getKunde(),
            'role'   => $user->getUserType(),
            'mitarbeiter_id'=> null,
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

    public function loadUser()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return $this->response->setStatusCode(401);
        }

        $db = DBConnection::getConnection();
        $user = User::findByIdEntry($db, (int)$userId);

        if (! $user) {
            session()->destroy();
            return $this->response->setStatusCode(401);
        }

        return $this->response->setJSON([
            'user_id'   => $user->getID(),
            'kunde_id'   => $user->getKunde(),
            'role'   => $user->getUserType(),
            'mitarbeiter_id'=> $user->getMitarbeiter(),
            'username'  => $user->getUsername(),
            'logged_in' => true
        ]);
    }
}
