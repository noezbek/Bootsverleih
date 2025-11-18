<?php

namespace App\Models;

use CodeIgniter\Model;

class Kundendaten extends Model {
    protected $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function speichern($vorname, $nachname) {
        $builder = $this->db->table("kunden");
        $data = [
            "Vorname" => $vorname,
            "Nachname" => $nachname
        ];
        $builder->insert($data);
    }
}