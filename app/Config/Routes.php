<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');   // ← Startseite
$routes->get('/einstellungen', 'Home::settings');   // ← Einstellungen
$routes->get('/support', 'Home::support');   // ← Support
$routes->get('/zahlungen', 'Home::zahlungen');   // ← Zahlungen
$routes->get('/kundenverwaltung', 'Home::kundenverwaltung');   // ← Kundenverwaltung
$routes->get('/bootsverleih', 'Home::bootsverleih');   // ← Bootsverleih
// $routes->setAutoRoute(true);      