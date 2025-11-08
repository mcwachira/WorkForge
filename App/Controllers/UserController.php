<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController{
    protected $db;

    public function __construct()
    {
        $config = require basePath('/config/db.php');
        $this->db = new Database($config);
    }

    /**
     *
     * Show the login page
     *
     * @return void
     *

     **/
     public function login(): void
     {
         loadView('users/login');
     }


    /**
     *
     * Show the register  page
     *
     * @return void
     *

     **/
    public function create (): void
    {
        loadView('users/create');
    }



    /**
     *
     * Show the user in database
     *
     * @return void
     *

     **/

    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        $error = [];


        //Vaidation

        if(!Validation::email($email)){
            $errors['email'] = 'Please Enter a valid email address';
        }

        if(!Validation::string($name, 3, 50)){
            $errors['name'] = 'Name must be 3 and 60 characters long';
        }

        if(!Validation::string($password, 6, 50)){
            $errors['password'] = 'Password  must have a minimum of 6 characters long';
        }

        if(!Validation::match($password, $passwordConfirmation)){
            $errors['passwordConfirmation'] = 'Passwords do not match';
        }

        if(!empty($errors)){
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);
            exit;
        }

        //check if the email exist
        $params = [   'email' => $email
        ];
        $user = $this->db->query('SELECT * FROM users WHERE email = :email' , $params)->fetch();

        if($user){
            $errors['email'] = 'That Email already exists';
            loadView('users/create', [
                'errors' => $errors
            ]);
            exit;
        }


        //create user account

        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' =>password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (name, email,city, state, password) VALUES(:name, :email,:city, :state,:password)', $params);

        //Get User Id
        $userId = $this->db->conn->lastInsertId();

        //set user session
          Session::set('user',[
              'id' => $userId,
              'name' => $name,
              'email' => $email,
              'city' => $city,
              'state' => $state,

          ]);

        redirect('/');

    }

    /**
     * Logout a user and kill a sesion
     *
     * @return void
     *
     */

    public function logout(): void
    {
        Session::clearAll();
        setCookie("PHPSESSID", "", time() - 86400, $params['path'], $params['domain'], $params['secure'] );
        redirect('/');
    }

    /**
     * Login a user  with email and password
     *
     * @return void
     *
     */

    public function authenticate(): void
    {

        $email = $_POST['email'];
        $password = $_POST['password'];


        $errors = [];

        // validation
        if(!Validation::email($email)){
            $errors['email'] = 'Please Enter a valid email address';
        }

        if(!Validation::string($password, 6, 50)){
             $errors['password'] = 'Password  must have a minimum of 6 characters long';
        }

        //check for errors
        if(!empty($errors)){
            loadView('users/login',[
                'errors' =>$errors
            ]);
            exit;
        }


        //check for email
        $params = ['email' => $email];
$user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();
//        var_dump($user);
if(!$user){
    $errors['email'] = 'Incorrect Credentials';
    loadView('users/login',[
        'errors' => $errors
    ]);
    exit;
}

//ch eck if password is correct
        if(!password_verify($password, $user['password'])){
            $errors['email'] = 'Incorrect Credentials';
            loadView('users/login',[
                'errors' => $errors
            ]);
            exit;
        }

        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);


        redirect('/');
 
    }
    }