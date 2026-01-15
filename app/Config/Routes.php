<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Views
$routes->get('/', 'Home::index');   // ← Startseite
$routes->get('/einstellungen', 'Home::settings');   // ← Einstellungen
$routes->get('/support', 'Home::support');   // ← Support
$routes->get('/zahlungen', 'Home::zahlungen');   // ← Zahlungen
$routes->get('/bootsverleih', 'Home::bootsverleih');   // ← Bootsverleih
$routes->get('/kundenverwaltung', 'Home::kundenverwaltung');   // ← Kundenverwaltung

//Data
$routes->group('', ['filter' => 'api'], function($routes) {
    $routes->get('/kundenverwaltung/load', 'KundenverwaltungController::loadKundenverwaltung');
    $routes->post('/kundenverwaltung/save', 'KundenverwaltungController::saveKundenverwaltung');
    $routes->post('/kundenverwaltung/delete', 'KundenverwaltungController::deleteKundenverwaltung');
});

// $routes->setAutoRoute(true);      