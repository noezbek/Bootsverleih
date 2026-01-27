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
        *Geburtstag
        <input type="date" name="birthday" required>
    </label>

    <label>
        *E-Mail
        <input type="email" name="email" placeholder="max@example.de" required>
    </label>

    <label>
        *Telefonnummer
        <input type="tel" name="phone" placeholder="+49 176 12345678" required>
    </label>

    <label>
        Straße + Hausnummer
        <input type="text" name="adress" placeholder="Musterstraße 12">
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
            Passwort
            <div class="password-field">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password" data-target="password">👁</button>
            </div>
        </label>

        <label>
            Passwort wiederholen
            <div class="password-field">
                <input type="password" name="password_repeat" id="password_repeat" required>
                <button type="button" class="toggle-password" data-target="password_repeat">👁</button>
            </div>
        </label>
    </legend>

    <button type="submit">Registrieren</button>

    <p class="form-error" id="register-error"></p>
</form>

<p class="switch-text">
    Schon registriert?
    <button type="button" data-switch="login">Zurück zum Login</button>
</p>
