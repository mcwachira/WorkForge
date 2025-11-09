<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('/config/db.php');
        $this->db = new Database($config);
    }

    /**
     *
     * Show all listings
     *
     * @return void
     *
     */
    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC ')->fetchAll();

        loadView('listings/index', ['listings' => $listings]);
    }

    public function create()
    {
        loadView('listings/create');
    }


    /**
     *
     * Show a single listing
     *
     * @param  array $params
     * @return void
     *
     */
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
        $newListingData['user_id'] = Session::get('user')['id'];
//        inspectAndDie($_POST);
//        inspectAndDie($newListingData);


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


            //set flash message
            $_SESSION['success_message'] = 'Listing created successfully';

            redirect("/listings");


        }

    }


    /**
     *
     * Show a  listing for editing
     *
     * @param  array $params
     * @return void
     *
     */
    public function edit($params)
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
        loadView('listings/edit', ['listing' => $listings]);
    }


    /**
     *
     * Update a listing
     *
     * @params array $params
     * @return void
     *
     */

   public function update($params)
   {
       $id = $params['id'] ?? "";

       $params = [
           'id' => $id
       ];

       $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

       //Check if listings exist
       if (!$listing) {
           ErrorController::notFound('Listing not found');
           return;
       }

       //only this fields will be submitted
       $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

       $updateValues = [];

       $updateValues = array_intersect_key($_POST, array_flip($allowedFields));


       $updateValues = array_map('sanitize', $updateValues);


       $requiredFields = ['title', 'description', 'salary',  'city', 'state', 'email'];

       $errors = [];

       foreach ($requiredFields as $field) {
           if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {

               $errors[$field] = ucfirst($field) .  " is required";
           }
       }

       if(!empty($errors)){
           loadView('listing/edit', [
               'listing' =>$listing,
               'errors' => $errors]);
                exit;

       }else{

           //Submit to database
           $updateFields = [];
           foreach (array_keys($updateValues) as $field){
               $updateFields[] = "{$field} = :{$field}";
           }

           $updateFields = implode(', ', $updateFields);
           $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

           $updateValues['id'] = $id;
           $this->db->query($updateQuery, $updateValues);

           $_SESSION['success_message'] = 'Listing Updated';

           redirect('/listings/' . $id);
       }

   }

    /**
     *
     * Delete a listing
     *
     * @params array $params
     * @return void
     *
     */

    public function destroy($params){
        $id = $params['id'];
        $params = [
            'id' => $id
        ];
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        //check if listing exists
        if(!$listing){
            ErrorController::notFound('Listing not Found');
            return;
        }

        //inspectAndDie($listing['user_id']);
        //Authorization
        if(!Authorization::isOwner($listing['user_id'])){
            $_SESSION['error_message'] = 'You are not authorized to delete this listing';
            return redirect('/listings/' . $listing->id );
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        //set flash message
        $_SESSION['success_message'] = 'Listing deleted successfully';


        redirect("/listings");
    }


}