# README – Frontend-Struktur

## Überblick

Dieses Dokument beschreibt die Frontend-Struktur des Projekts sowie die zentrale Initialisierung von JavaScript, CSRF-Schutz und seitenabhängiger Logik.

---

## Zentrale Meta- und Konfigurationsdaten

Jede View MUSS im `<head>` folgenden Block enthalten:

```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<script>
    window.APP = {
        baseUrl: "<?= rtrim(base_url(), '/') ?>",
        csrf: document.querySelector('meta[name="csrf-token"]').content
    };
</script>
```

### Zweck

* Verbindung zwischen Backend (PHP / CodeIgniter) und Frontend (JavaScript)
* Übergabe serverseitiger Werte an das Frontend
* Aktivierung des CSRF-Schutzes für Formulare und API-Requests

### Verfügbare Werte

```js
APP.baseUrl // Basis-URL der Anwendung
APP.csrf    // Aktuelles CSRF-Token
```

---

## Einbindung des zentralen JavaScript-Loaders

Am Ende jeder View wird folgendes Skript eingebunden:

```html
<script type="module" src="<?= base_url('js/app.js') ?>"></script>
```

### Erklärung

* `type="module"` aktiviert native ES-Module
* Ermöglicht `import` / `export`
* Erlaubt dynamisches Nachladen von JavaScript-Dateien

---

## app.js – Zentraler Frontend-Loader

`app.js` ist der zentrale Einstiegspunkt für das gesamte Frontend und wird auf jeder Seite geladen.

### Aufgaben von app.js

1. Initialisierung globaler Logik (z. B. Dark Mode)
2. Auslesen des `data-page`-Attributs im `<body>`
3. Dynamisches Laden der passenden Seiten-JavaScript-Datei
4. Aufruf der `init()`-Funktion der jeweiligen Seite

### Beispiel

```html
<body data-page="support">
```

Lädt automatisch:

```text
/public/js/pages/support.js
```

---

## Seiten-JavaScript

Jede Seite besitzt genau eine JavaScript-Datei im Ordner `public/js/pages/`.

Jede dieser Dateien exportiert eine `init()`-Funktion:

```js
export function init() {
    // Seitenlogik
}
```

Diese Funktion wird automatisch von `app.js` aufgerufen.

---

## Projektstruktur (JavaScript)

```text
public/js/
├─ app.js              # Zentraler Loader
├─ api.js              # Fetch- und API-Hilfsfunktionen
└─ pages/
   ├─ home.js
   ├─ kundenverwaltung.js
   ├─ zahlungen.js
   └─ support.js
```

---

## Vorteile dieser Struktur

* Saubere Trennung von PHP (Views), JavaScript (Logik) und CSS (Styles)
* Bessere Performance durch Lazy Loading
* Keine unnötigen Skripte auf anderen Seiten
* Leicht erweiterbar
* Gut geeignet für Schul- und Projektarbeiten

---

## Kurzfassung

* `window.APP` verbindet Backend und Frontend
* `app.js` ist der zentrale JavaScript-Loader
* Jede Seite hat genau eine eigene JS-Datei mit `init()`
