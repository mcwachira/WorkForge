<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('/config/db.php');
        $this->db = new Database($config);
    }

    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

        loadView('listings/index', ['listings' => $listings]);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show($params)
    {
        $id = $params['id'] ?? "";

        $params = [
            'id' => $id
        ];

        $listings = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        //Check if listings exist
        if (!$listings) {
            ErrorController::notFound('Listing not found');
            return;
        }
        loadView('listings/show', ['listing' => $listing]);
    }


    /**
     *
     * Store data in database
     *
     * @retrun void
     */

    public function store()
    {

        //only this fields will be submitted
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'email', 'phone', 'requirements', 'benefits'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_data'] = 1;

        $newListingData = array_map('sanitize', $newListingData);

        inspectAndDie($newListingData);
    }



}