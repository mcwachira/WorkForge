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
function loadView($name){
    $viewPath = basePath("views/{$name}.view.php");

    if(file_exists($viewPath)){
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