<?php

use Framework\Database;
$config = require basePath('/config/db.php');
$db = new Database($config);
$listings = $db->query('SELECT * FROM listings LIMIT 5')->fetchAll();

loadView('home', ['listings'=>$listings]);