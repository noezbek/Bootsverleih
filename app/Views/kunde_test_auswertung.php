<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kunde Test Auswertung</title>
</head>
<body>

<h2>Ergebnis Kundentest</h2>

<?php if (isset($message)): ?>
    <p><strong><?= esc($message) ?></strong></p>
<?php else: ?>
    <p>Keine Nachricht vorhanden.</p>
<?php endif; ?>

<br>

<form action="formularzeigen" method="get">
    <button type="submit">↩ Zurück</button>
</form>

</body>
</html>
