<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
class ListingController {
    protected $db;
    public function __construct() {
        $config = require basePath('/config/db.php');
        $this->db = new Database($config);
    }

    public function index() {
        $listings = $this ->db->query('SELECT * FROM listings')->fetchAll();

        loadView('listings/index', ['listings'=>$listings]);
    }

    public function create() {
        loadView('listings/create');
    }

    public function show($params) {
    $id = $params['id'] ?? "";

    $params = [
        'id' => $id
    ];

    $listings = $this ->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    //Check if listings exist
        if(!$listings) {
            ErrorController::notFound('Listing not found');
            return;
        }
    loadView('listings/show', ['listing'=>$listing]);
    }
}