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
        loadView('listings/show', ['listing' => $listings]);
    }


    /**
     *
     * Store data in database
     *
     * @retrun void
     */

    public function store(): void
    {

        //only this fields will be submitted
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
//        inspectAndDie($_POST);
//        inspectAndDie($newListingData);

       $newListingData['user_data'] = 1;

        //sanitizing the data
        $newListingData = array_map('sanitize', $newListingData);


        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];
        $errors =[];


        //checks if each field is empty
        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {

                $errors[$field] = ucfirst($field) .  " is required";
            }
        }

        if(!empty($errors)){
            //Reload Views with errors
            loadView('listings/create', [
                'errors' => $errors,
            'listing' => $newListingData]);
        }else{


            //insert Data

            $fields = [];
            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }


            $fields = implode(', ', $fields);


            $values = [];
            foreach ($newListingData as $field => $value) {

                if($value === ""){
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(", ", $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
            $this->db->query($query, $newListingData);


            redirect("/listings");


        }

    }



}