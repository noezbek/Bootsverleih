<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
    <style>
        .yippie {
            color: red;
            font-size: 50px;
        }

        /* Grundlayout */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e6e6e6, #ffffff);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding-top: 80px;
        }

        /* Formularcontainer */
        form {
            background: white;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            width: 850px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 30px;
        }

        /* Überschrift */
        form h2 {
            grid-column: span 2;
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }

        /* Labels & Inputs */
        label {
            display: flex;
            flex-direction: column;
            font-weight: bold;
            color: #444;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 4px rgba(76, 175, 80, 0.4);
        }

        /* Fieldsets */
        fieldset {
            grid-column: span 2;
            border: none;
            margin: 10px 0;
            padding: 0;
        }

        fieldset legend {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 15px;
        }

        /* Geschlecht Radio Buttons */
        .gender-options {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        .gender-options label {
            font-weight: normal;
            font-size: 14px;
            flex-direction: row;
            align-items: center;
            gap: 6px;
        }

        /* Passwort- und AGB-Bereich */
        .fullwidth {
            grid-column: span 2;
        }

        /* AGB Checkbox */
        .agb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        /* Button */
        button {
            grid-column: span 2;
            padding: 14px;
            background: #4CAF50;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #45a049;
        }
    </style>
</head>

<body>
    <form action="auswertung.php" method="POST">
        <h2>Registrieren</h2>
        <fieldset>
            <legend>Personendaten:</legend>
            <label>Vorname <input type="text" name="firstName" placeholder="Max" required> </label>
            <label>Nachname <input type="text" name="lastName" placeholder="Mustermann" required> </label>
            <label>Geburtstag <input type="date" name="birthday" required> </label>
            <label>Telefonnummer <input type="tel" name="phone" placeholder="+49 176 12345678" required> </label>
            <label>E-Mail <input type="email" name="email" placeholder="max@example.de" required> </label>
            <label>Straße <input type="text" name="street" placeholder="Musterstraße" required> </label>
            <label>Hausnummer <input type="text" name="housenumber" placeholder="12a" required> </label>
            <label>PLZ <input type="text" name="plz" placeholder="12345" required> </label>
            <label>Ort <input type="text" name="city" placeholder="Musterstadt" required> </label>
        </fieldset>
        <fieldset>
            <legend>Geschlecht:</legend>
            <div class="gender-options">
                <label><input type="radio" name="gender" value="männlich" required> Männlich</label>
                <label><input type="radio" name="gender" value="weiblich"> Weiblich</label>
                <label><input type="radio" name="gender" value="divers"> Divers</label>
            </div>
        </fieldset>
        <label class="fullwidth">Passwort <input type="password" name="password" placeholder="Passwort" required>
        </label>
        <label class="fullwidth">Passwort wiederholen <input type="password" name="passwordRepeat"
                placeholder="Passwort wiederholen" required> </label>
        <label class="agb fullwidth"> <input type="checkbox" name="agb" required> Ich akzeptiere die AGB </label>
        <button type="submit">Registrieren</button>
    </form>
</body>

</html>