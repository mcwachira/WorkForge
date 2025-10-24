<?php
/**
 *  Get Base path
 *
 * @Param string $path
 * @return string
 */

function basePath($path = ''){
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 *
 * @Param  string $name
 * @return void
 */
function loadView($name , $data=[]){
    $viewPath = basePath("views/{$name}.view.php");

//    inspectAndDie($viewPath);
    if(file_exists($viewPath)){
        extract($data);
        require $viewPath;
    }else{
        echo "View '{$name} does not exist'";
    }
}



/**
 * Load a partial
 *
 * @Param  string $name
 * @return void
 */
function loadPartial($name){
    $partialPath =  basePath("views/partials/{$name}.php");


    if(file_exists($partialPath)){
        require $partialPath;
    }else{
        echo "View '{$name} does not exist'";
    }
}

/**
 * Inspect a value(s)
 *
 * @Param  mixed $value
 * @return void
 */

function inspect($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a value(s) and die
 *
 * @Param  mixed $value
 * @return void
 */

function inspectAndDie($value){
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
}

/**
 * Load a partial
 *
 * @Param  string $salary
 * @return string Formated Salary
 */

function formatSalary(string $salary): string
{
    return '$' . number_format((float)$salary);
}