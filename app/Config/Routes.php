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
$routes->get('/meta/load', 'MetaController::loadEnums');
$routes->get('/kundenverwaltung/load', 'KundenverwaltungController::loadKundenverwaltung');
$routes->post('/kundenverwaltung/save', 'KundenverwaltungController::saveKundenverwaltung');
$routes->post('/kundenverwaltung/delete', 'KundenverwaltungController::deleteKundenverwaltung');
$routes->get('/bootsverleih/load', 'BootsverleihController::loadBoote');
$routes->post('/bootsverleih/save', 'BootsverleihController::saveBoot');
$routes->post('/bootsverleih/delete', 'BootsverleihController::deleteBoot');
$routes->get('/zahlungen/load', 'ZahlungenController::loadZahlungen');
$routes->post('/zahlungen/save', 'ZahlungenController::saveZahlung');
$routes->post('/zahlungen/delete', 'ZahlungenController::deleteZahlung');

// $routes->setAutoRoute(true);      