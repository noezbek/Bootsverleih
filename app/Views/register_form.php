<h2>Registrieren</h2>

<form method="post" action="<?= base_url('register') ?>">
    <?= csrf_field() ?>


    <label>
        *Vorname
        <input type="text" name="first_name" placeholder="Max" required>
    </label>

    <label>
        *Nachname
        <input type="text" name="last_name" placeholder="Mustermann" required>
    </label>

    <label>
        *E-Mail
        <input type="email" name="email" placeholder="max@example.de" required>
    </label>

    <label>
        *Telefonnummer
        <input type="tel" name="phone" placeholder="+49 176 12345678">
    </label>

    <label>
        Straße
        <input type="text" name="street" placeholder="Musterstraße">
    </label>

    <label>
        Hausnummer
        <input type="text" name="house_number" placeholder="12a">
    </label>

    <label>
        PLZ
        <input type="text" name="zip" placeholder="12345">
    </label>

    <label>
        Ort
        <input type="text" name="city" placeholder="Musterstadt">
    </label>

    <hr>

    <legend>
        <label>
            *Benutzername
            <input type="text" name="username" required>
        </label>

        <label>
            *Passwort
            <input type="password" name="password" required>
        </label>

        <label>
            *Passwort wiederholen
            <input type="password" name="password_repeat" required>
        </label>
    </legend>

    <button type="submit">Registrieren</button>
</form>

<p class="switch-text">
    Schon registriert?
    <button type="button" data-switch="login">Zurück zum Login</button>
</p>
