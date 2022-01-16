<?php
/**
 * This is how the application uses the URL to determine
 * what actions to take.
 */

/**
 * Home page
 * Triggered by your-domain.com
 */
Route::add('/',function() {
    (new AbstractController)->view("index");
});

/*
 * Very basic action, no controller, no model, no view, just echo some text
 * 
 * Triggered by your-domain.com/super_basic
 */
Route::add('/super_basic',function() {
    echo "<b>Difficulty Level:</b> Basic";
});

/*
 * This one doesn't call a controller, it just takes the URL parameters,
 * creates and deletes a user using the AppUsersModel, and echos some text.
 * This example will only work with a database and database credentials in the 
 * config.json file, plus a table created called app_users with at least 
 * id (int primary), email (varchar), password (varchar) and 
 * registration_date (int)
 * 
 * Triggered by your-domain.com/create_user
 */
Route::add('/create_user',function() {
    $appUsers = new AppUsersModel();
    $user_id = $appUsers->createUser($_GET['email'],$_GET['pass']);
    echo "<p>User ".$_GET['email']." created!</p>";
    $appUsers->deleteUser($user_id);
    echo "<p>User ".$_GET['email']." deleted :(</p>";
});

/*****************************************************************************/
/********************* Custom Routes Above this line *************************/
/*****************************************************************************/

/*
 * Controller->Action
 * 
 * This will try to take the /controller/action from the url and attempt
 * to execute a corresponding controller->action. The urls need to be
 * snake_case.
 * 
 * For Example:
 * 
 * /examples/list_names = ExamplesController->ListNamesAction()
 * /examples/main = ExamplesController->mainAction()
 * /examples = ExamplesController->indexAction()
 * 
 * These ones are executed for request_method GET
 */
Route::add('/([a-zA-z]*)/([a-zA-z]*)',function($controllerName,$action) {
    $controller = new AbstractController;
    $controllerName = $controller->snakeToCamel($controllerName)."Controller";
    $action = empty($action) ? "IndexAction" : $controller->snakeToCamel($action)."Action";
    call_user_func([(new $controllerName()),$action]);
});

/*
 * API Controller->Action
 * 
 * This will try to take any url with /api/ in front of the /controller/action
 * and attempt to execute a corresponding controller->action. The urls need to 
 * be snake_case.
 * 
 * For Example:
 * 
 * /api/examples/list_names = ExamplesController->ListNamesAction()
 * /api/examples/main = ExamplesController->mainAction()
 * /api/examples = ExamplesController->indexAction()
 * 
 * These ones are executed for request_method POST
 */
Route::add('/api/([a-zA-z]*)/([a-zA-z]*)',function($controllerName,$action) {
    $controller = new AbstractController;
    $controllerName = $controller->snakeToCamel($controllerName)."Controller";
    $action = empty($action) ? "IndexAction" : $controller->snakeToCamel($action)."Action";
    call_user_func([(new $controllerName()),$action]);
},"post");

Route::run('/');