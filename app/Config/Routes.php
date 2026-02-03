<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//User
$routes->get('/auth', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->post('/register', 'AuthController::register');
$routes->post('/logout', 'AuthController::logout');

$routes->get(
    'reservierung/confirm/(:segment)',
    'LiegeplatzeController::confirm/$1'
);

$routes->post('reservierung/confirm', 'LiegeplatzeController::confirmPost');


// Alle authentifizierten Benutzer (Kunde + Mitarbeiter)
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Views für alle
    $routes->get('/', 'Home::index');
    $routes->get('/einstellungen', 'Home::settings');
    $routes->get('/support', 'Home::support');

    // DATA für alle (Mitarbeiter braucht Zugriff für Verwaltungsseiten)
    $routes->get('/userdata/load', 'AuthController::loadUser');
    $routes->get('/enums/load', 'MetaController::loadEnums');
    $routes->get('/features/load', 'BootsverleihController::loadFeatures');
    $routes->get('/bootsverleih/load', 'BootsverleihController::loadBoote');
    $routes->get('/liegeplaetze/load', 'LiegeplatzeController::loadLiegeplaetze');
    $routes->get('/zahlungen/loadFull', 'ZahlungenController::loadZahlungsVerwaltung');
    $routes->get('/bestellungen/load', 'ZahlungenController::loadBestellungen');
    $routes->get('/vertraege/load', 'ZahlungenController::loadVertaege');
    $routes->get('/zahlungen/load', 'ZahlungenController::loadZahlungen');

});

// Nur für Mitarbeiter
$routes->group('', ['filter' => 'mitarbeiter'], function($routes) {

    // Views nur für Mitarbeiter
    $routes->get('/kundenverwaltung', 'Home::kundenverwaltung');
    $routes->get('/liegeplatzverwaltung', 'Home::liegeplatzverwaltung');
    $routes->get('/bootsverwaltung', 'Home::bootsverwaltung');

    // DATA nur für Mitarbeiter
    $routes->get('/kundenverwaltung/load', 'KundenverwaltungController::loadKundenverwaltung');
    $routes->post('/kundenverwaltung/save', 'KundenverwaltungController::saveKundenverwaltung');
    $routes->post('/kundenverwaltung/delete', 'KundenverwaltungController::deleteKundenverwaltung');

});

// Nur für Kunden
$routes->group('', ['filter' => 'kunde'], function($routes) {

    // Views nur für Kunden
    $routes->get('/zahlungen', 'Home::zahlungen');
    $routes->get('/bootsverleih', 'Home::bootsverleih');
    $routes->get('/liegeplaetze', 'Home::liegeplaetze');
    $routes->get('/accountEinstellungen', 'Home::accountSettings');

    // Aktionen nur für Kunden
    $routes->post('/bootsverleih/mieten', 'BootsverleihController::saveBootMiete');
    $routes->post('/liegeplaetze/reservieren', 'LiegeplatzeController::saveReservierung');

    // Account Einstellungen nur für Kunden
    $routes->get('/accountEinstellungen/load', 'AccountEinstellungenController::loadAccountData');
    $routes->post('/accountEinstellungen/savePersonal', 'AccountEinstellungenController::savePersonalData');
    $routes->post('/accountEinstellungen/changeUsername', 'AccountEinstellungenController::changeUsername');
    $routes->post('/accountEinstellungen/changePassword', 'AccountEinstellungenController::changePassword');

});

// $routes->setAutoRoute(true);
