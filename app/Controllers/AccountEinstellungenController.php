<?php

namespace App\Controllers;

use App\Models\DBConnection;
use App\Models\User;
use App\Models\Kunde;

class AccountEinstellungenController extends BaseController
{
    /**
     * Load current user's account data
     * GET /accountEinstellungen/load
     */
    public function loadAccountData(): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Not authenticated']);
        }

        $db = DBConnection::getConnection();

        // Load User
        $user = User::findByIdEntry($db, $userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        // Load associated Kunde (if exists)
        $kunde = null;
        $kundeId = $user->getKunde();
        if ($kundeId) {
            $kundeObj = Kunde::findByIdEntry($db, $kundeId);
            if ($kundeObj) {
                $kunde = $kundeObj->toArray();
                unset($kunde['bestellungen']);
            }
        }

        return $this->response->setJSON([
            'user' => [
                'id' => $user->getID(),
                'username' => $user->getUsername(),
            ],
            'kunde' => $kunde,
        ]);
    }

    /**
     * Save personal data (Kunde fields)
     * POST /accountEinstellungen/savePersonal
     */
    public function savePersonalData(): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Not authenticated']);
        }

        $data = json_decode($this->request->getPost('data'), true);

        $db = DBConnection::getConnection();

        // Get user to find associated Kunde
        $user = User::findByIdEntry($db, $userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        $kundeId = $user->getKunde();
        if (!$kundeId) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No customer data associated']);
        }

        // Load existing Kunde
        $kunde = Kunde::findByIdEntry($db, $kundeId);
        if (!$kunde) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Customer not found']);
        }

        // Update fields
        $kunde->setVorname($data['vorname'] ?? $kunde->getVorname());
        $kunde->setNachname($data['nachname'] ?? $kunde->getNachname());
        $kunde->setEmail($data['email'] ?? $kunde->getEmail());
        $kunde->setTelefon((int)($data['telefon'] ?? $kunde->getTelefon()));
        $kunde->setStrasse($data['strasse'] ?? $kunde->getStrasse());
        $kunde->setPlz((int)($data['plz'] ?? $kunde->getPlz()));
        $kunde->setStadt($data['stadt'] ?? $kunde->getStadt());
        $kunde->setGeburtsdatum($data['geburtsdatum'] ?? $kunde->getGeburtsdatum());

        // Save
        $kunde->saveEntry($db);

        $kundeArray = $kunde->toArray();
        unset($kundeArray['bestellungen']);

        return $this->response->setJSON([
            'success' => true,
            'kunde' => $kundeArray,
        ]);
    }

    /**
     * Change username
     * POST /accountEinstellungen/changeUsername
     */
    public function changeUsername(): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Not authenticated']);
        }

        $data = json_decode($this->request->getPost('data'), true);
        $currentPassword = $data['currentPassword'] ?? '';
        $newUsername = trim($data['newUsername'] ?? '');

        if (empty($currentPassword) || empty($newUsername)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Alle Felder sind erforderlich.']);
        }

        $db = DBConnection::getConnection();

        // Load user with password hash
        $user = User::findByIdEntry($db, $userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'error' => 'User not found']);
        }

        // Verify current password
        if (!$user->verifyPassword($currentPassword)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Aktuelles Passwort ist falsch.']);
        }

        // Check if new username is already taken
        $existingUser = User::findForAuth($db, $newUsername);
        if ($existingUser && $existingUser->getID() !== $userId) {
            return $this->response->setJSON(['success' => false, 'error' => 'Benutzername ist bereits vergeben.']);
        }

        // Update username
        $user->setUsername($newUsername);
        $user->saveEntry($db);

        // Update session
        session()->set('username', $newUsername);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Change password
     * POST /accountEinstellungen/changePassword
     */
    public function changePassword(): \CodeIgniter\HTTP\ResponseInterface
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Not authenticated']);
        }

        $data = json_decode($this->request->getPost('data'), true);
        $currentPassword = $data['currentPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Alle Felder sind erforderlich.']);
        }

        if (strlen($newPassword) < 8) {
            return $this->response->setJSON(['success' => false, 'error' => 'Das neue Passwort muss mindestens 8 Zeichen lang sein.']);
        }

        $db = DBConnection::getConnection();

        // Load user
        $user = User::findByIdEntry($db, $userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'error' => 'User not found']);
        }

        // Verify current password
        if (!$user->verifyPassword($currentPassword)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Aktuelles Passwort ist falsch.']);
        }

        // Hash and save new password
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->setPasswordHash($newHash);
        $user->saveEntry($db);

        return $this->response->setJSON(['success' => true]);
    }
}
