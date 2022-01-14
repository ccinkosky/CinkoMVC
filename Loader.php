<?php
/**
 * This is where we include all the different objects
 * that could be used in the application
 * 
 * The AppObject class is an abstract that provides
 * A bunch of tools. AbstractController and AbstractModel.php
 * AbstractModel extend AppObject
 * 
 * The Routes.php file will list the different routes
 * and what to do when a user takes that route
 */
session_start();
// Load the abstract class
require "AppObject.php";
// Load library files
foreach (glob(__DIR__."/library/*.php") as $filename) { require $filename; }
// Load all models
foreach (glob(__DIR__."/models/*.php") as $filename) { require $filename; }
// Load all controllers
foreach (glob(__DIR__."/controllers/*.php") as $filename) { require $filename; }
// Load routes
require "Routes.php";