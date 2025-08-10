<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * This is the main entry point for the API routes
 * app/requests/routes/api.php
 * 
 * @package App
 * @author rrafiq
 * @version 1.0.0
 * @license MIT
 */

require_once __DIR__ . '/../app/config/bootstrap.php';
require_once CORE_PATH . '/Route.php';



/** Import Request classes below *********************************************************************** */

require_once REQUESTS_PATH . '/users/User.php';
require_once SECURITY_PATH . '/Auth.php';

/** **************************************************************************************************** */


/** Define API routes below **************************************************************************** */
// Auth
Route::post('/login', [Auth::class, 'login']); // login
Route::post('/logout', [Auth::class, 'logout']); // logout

// Users
Route::get('/users', [User::class, 'all']); // get all users
Route::get('/users/{id}', [User::class, 'find']); // get user by id
Route::post('/users', [User::class, 'create']); // create a new user
Route::put('/users/{id}', [User::class, 'update']); // update a user
Route::delete('/users/{id}', [User::class, 'delete']); // delete a user

/** **************************************************************************************************** */



/** DO NOT EDIT BELOW THIS LINE ************************************************************************ */
Route::dispatch();
