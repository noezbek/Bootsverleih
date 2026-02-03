<?php

namespace App\Models;

use Config\Services;
use Exception;

class ReservationEmail
{
    private const FROM_EMAIL = 'nathanoezbek2004@gmail.com';
    private const FROM_NAME  = 'Bootsverleih';

    public static function sendConfirmation(
        Kunde $kunde,
        string $confirmUrl,
        string $expiresAt
    ): bool {
        $email = \Config\Services::email(null, false);


        $kundenMail = $kunde->getEmail();

        if (!$kundenMail) {
            throw new Exception('Keine Email vorhanden');
        }

        $email->setFrom(self::FROM_EMAIL, self::FROM_NAME);
        $email->setTo($kundenMail);
        $email->setSubject('Reservierung bestätigen – Bootsverleih');

        $email->setMessage(
            "Hallo {$kunde->getFullName()},\n\n" .
            "bitte bestätige deine Reservierung über den folgenden Link:\n\n" .
            "{$confirmUrl}\n\n" .
            "Die Reservierung ist gültig bis: {$expiresAt}\n\n" .
            "Ohne Bestätigung verfällt sie automatisch.\n\n" .
            "Viele Grüße\n" .
            "Dein Bootsverleih-Team"
        );

        $success = $email->send();

        if (!$success) {
            log_message('error', $email->printDebugger(['headers']));
            throw new Exception('Email konnte nicht gesendet werden');
        }

        return $success;
    }
}
