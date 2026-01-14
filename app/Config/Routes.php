<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');   // ← Startseite
$routes->get('/einstellungen', 'Home::settings');   // ← Einstellungen
$routes->get('/support', 'Home::support');   // ← Support
$routes->get('/zahlungen', 'Home::zahlungen');   // ← Zahlungen
$routes->get('/kundenverwaltung', 'KundenverwaltungController::loadKundenverwaltung');   // ← Kundenverwaltung
$routes->post('/kundenverwaltung/save', 'KundenverwaltungController::saveKundenverwaltung');   // ← Kundenverwaltung speichern
$routes->post('/kundenverwaltung/delete', 'KundenverwaltungController::deleteKundenverwaltung');   // ← Kundenverwaltung löschen
$routes->get('/bootsverleih', 'Home::bootsverleih');   // ← Bootsverleih
// $routes->setAutoRoute(true);      