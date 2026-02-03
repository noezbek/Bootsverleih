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


$routes->group('', ['filter' => 'auth'], function($routes) {

    //Views
    $routes->get('/', 'Home::index');
    $routes->get('/einstellungen', 'Home::settings');
    $routes->get('/support', 'Home::support');
    $routes->get('/zahlungen', 'Home::zahlungen');
    $routes->get('/bootsverleih', 'Home::bootsverleih');
    $routes->get('/kundenverwaltung', 'Home::kundenverwaltung');
    $routes->get('/liegeplaetze', 'Home::liegeplaetze');
    $routes->get('/accountEinstellungen', 'Home::accountSettings');
    $routes->get('/liegeplatzverwaltung', 'Home::liegeplatzverwaltung');
    $routes->get('/bootsverwaltung', 'Home::bootsverwaltung');

    // DATA
    $routes->get('/userdata/load', 'AuthController::loadUser');
    $routes->get('/enums/load', 'MetaController::loadEnums');
    $routes->get('/kundenverwaltung/load', 'KundenverwaltungController::loadKundenverwaltung');
    $routes->post('/kundenverwaltung/save', 'KundenverwaltungController::saveKundenverwaltung');
    $routes->post('/kundenverwaltung/delete', 'KundenverwaltungController::deleteKundenverwaltung');
    $routes->get('/features/load', 'BootsverleihController::loadFeatures');
    $routes->get('/bootsverleih/load', 'BootsverleihController::loadBoote');
    $routes->post('/bootsverleih/mieten', 'BootsverleihController::saveBootMiete');
    $routes->get('/zahlungen/loadFull', 'ZahlungenController::loadZahlungsVerwaltung');
    $routes->get('/liegeplaetze/load', 'LiegeplatzeController::loadLiegeplaetze');
    $routes->post('/liegeplaetze/reservieren', 'LiegeplatzeController::saveReservierung');
    $routes->get('/bestellungen/load', 'ZahlungenController::loadBestellungen');
    $routes->get('/vertraege/load', 'ZahlungenController::loadVertaege');
    $routes->get('/zahlungen/load', 'ZahlungenController::loadZahlungem');

    // Account Einstellungen
    $routes->get('/accountEinstellungen/load', 'AccountEinstellungenController::loadAccountData');
    $routes->post('/accountEinstellungen/savePersonal', 'AccountEinstellungenController::savePersonalData');
    $routes->post('/accountEinstellungen/changeUsername', 'AccountEinstellungenController::changeUsername');
    $routes->post('/accountEinstellungen/changePassword', 'AccountEinstellungenController::changePassword');

});

// $routes->setAutoRoute(true);