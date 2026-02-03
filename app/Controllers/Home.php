<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('homepage');
    }

    public function settings(): string
    {
        return view('einstellungen');
    }

    public function support(): string
    {
        return view('support');
    }

    public function zahlungen(): string
    {
        return view('zahlungen');
    }

    public function kundenverwaltung(): string
    {
        return view('kundenverwaltung');
    }

    public function bootsverleih(): string
    {
        return view('bootsverleih');
    }

    public function liegeplaetze(): string
    {
        return view('liegeplaetze');
    }

    public function accountSettings(): string
    {
        return view('accountEinstellungen');
    }

    public function liegeplatzverwaltung(): string
    {
        return view('liegeplatzverwaltung');
    }

    public function bootsverwaltung(): string
    {
        return view('bootsverwaltung');
    }
}
