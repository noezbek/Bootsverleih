<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen - Yachthafen Plau</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 40px 20px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background: #1a1a1a;
            color: #e0e0e0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 40px;
        }

        h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }

        body.dark-mode h1 {
            color: #e0e0e0;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0b3c5d;
            text-decoration: none;
            font-size: 14px;
        }

        body.dark-mode .back-link {
            color: #64b5f6;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .settings-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        body.dark-mode .settings-section {
            background: #2d2d2d;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .settings-section h2 {
            font-size: 24px;
            color: #0b3c5d;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        body.dark-mode .settings-section h2 {
            color: #64b5f6;
            border-bottom-color: #444;
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .setting-item:not(:last-child) {
            border-bottom: 1px solid #f0f0f0;
        }

        body.dark-mode .setting-item:not(:last-child) {
            border-bottom-color: #444;
        }

        .setting-info h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        body.dark-mode .setting-info h3 {
            color: #e0e0e0;
        }

        .setting-info p {
            font-size: 14px;
            color: #666;
        }

        body.dark-mode .setting-info p {
            color: #999;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #0b3c5d;
        }

        body.dark-mode input:checked + .slider {
            background-color: #64b5f6;
        }

        input:checked + .slider:before {
            transform: translateX(30px);
        }

        /* Button Styling */
        .edit-button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            color: #333;
            transition: all 0.3s ease;
        }

        body.dark-mode .edit-button {
            background: #3a3a3a;
            border-color: #555;
            color: #e0e0e0;
        }

        .edit-button:hover {
            background: #f5f5f5;
        }

        body.dark-mode .edit-button:hover {
            background: #4a4a4a;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            .settings-section {
                padding: 20px;
            }

            .setting-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="<?= base_url('/') ?>" class="back-link">← Zurück zum Dashboard</a>

    <header>
        <h1>Einstellungen</h1>
    </header>

    <div class="settings-section">
        <h2>Nutzereinstellungen</h2>
        <div class="setting-item">
            <div class="setting-info">
                <h3>Profilname</h3>
                <p>Ihr angezeigter Name im System</p>
            </div>
            <button class="edit-button" onclick="alert('Funktion in Entwicklung')">Bearbeiten</button>
        </div>
        <div class="setting-item">
            <div class="setting-info">
                <h3>E-Mail Benachrichtigungen</h3>
                <p>Erhalten Sie Updates per E-Mail</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="emailNotifications">
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <div class="settings-section">
        <h2>Darstellung</h2>
        <div class="setting-item">
            <div class="setting-info">
                <h3>Dark Mode</h3>
                <p>Zwischen hellem und dunklem Design wechseln</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="darkModeToggle">
                <span class="slider"></span>
            </label>
        </div>
    </div>
</div>

<script>
    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Check for saved dark mode preference
    const isDarkMode = localStorage.getItem('darkMode') === 'true';

    if (isDarkMode) {
        body.classList.add('dark-mode');
        darkModeToggle.checked = true;
    }

    // Toggle dark mode
    darkModeToggle.addEventListener('change', function() {
        if (this.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });

    // Email Notifications Toggle (just for demo)
    const emailToggle = document.getElementById('emailNotifications');
    const savedEmailPref = localStorage.getItem('emailNotifications') === 'true';

    if (savedEmailPref) {
        emailToggle.checked = true;
    }

    emailToggle.addEventListener('change', function() {
        localStorage.setItem('emailNotifications', this.checked);
    });
</script>

</body>
</html>
