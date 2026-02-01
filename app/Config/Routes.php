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


$routes->group('', ['filter' => 'auth'], function($routes) {

    //Views
    $routes->get('/', 'Home::index');
    $routes->get('/einstellungen', 'Home::settings');
    $routes->get('/support', 'Home::support');
    $routes->get('/zahlungen', 'Home::zahlungen');
    $routes->get('/bootsverleih', 'Home::bootsverleih');
    $routes->get('/kundenverwaltung', 'Home::kundenverwaltung');
    $routes->get('/liegeplaetze', 'Home::liegeplaetze');   // ← Liegeplätze

    // DATA
    $routes->get('/userdata/load', 'AuthController::loadUser');
    $routes->get('/enums/load', 'MetaController::loadEnums');
    $routes->get('/kundenverwaltung/load', 'KundenverwaltungController::loadKundenverwaltung');
    $routes->post('/kundenverwaltung/save', 'KundenverwaltungController::saveKundenverwaltung');
    $routes->post('/kundenverwaltung/delete', 'KundenverwaltungController::deleteKundenverwaltung');
    $routes->get('/bootsverleih/load', 'BootsverleihController::loadBoote');
    $routes->post('/bootsverleih/save', 'BootsverleihController::saveBoot');
    $routes->post('/bootsverleih/delete', 'BootsverleihController::deleteBoot');
    $routes->get('/zahlungen/load', 'ZahlungenController::loadZahlungen');
    $routes->post('/zahlungen/save', 'ZahlungenController::saveZahlung');
    $routes->post('/zahlungen/delete', 'ZahlungenController::deleteZahlung');

});

// $routes->setAutoRoute(true);