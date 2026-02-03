<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Zahlungsweise auswählen</title>
</head>
<body>

<h2>Reservierung bestätigen</h2>

<p>
    Bitte wählen Sie eine Zahlungsweise aus und bestätigen Sie anschließend
    Ihre Reservierung.
</p>

<form method="post" action="<?= base_url('reservierung/confirm') ?>">
    <?php /** @var string $token */ ?>
    <input type="hidden" name="token" value="<?= esc($token) ?>">

    <label>
        <input type="radio" name="zahlungsart" value="BAR" required>
        Barzahlung vor Ort
    </label><br>

    <label>
        <input type="radio" name="zahlungsart" value="UEBERWEISUNG">
        Überweisung
    </label><br><br>

    <button type="submit">
        Reservierung verbindlich bestätigen
    </button>
</form>

</body>
</html>
