<!DOCTYPE html>
<html>
<head>
    <title>Kunde Test</title>
</head>
<body>

<h2>Kunde Schnelltest</h2>
 
<form method="post" action="testkunde">
    <button type="submit">➕ Neuen Test-Kunden speichern</button>
</form>

<?php if (isset($message)): ?>
    <p><strong><?= esc($message) ?></strong></p>
<?php endif; ?>

</body>
</html>
