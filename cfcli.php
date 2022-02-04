<?php
/**
 * This a simple command line tool to trigger a
 * controller->action. If you're controller->action
 * displays a view, it will put out the html/json/etc
 * here just the same as if you were being sent to the
 * browser.
 * 
 * For example:
 * 
 * $ php cfcli.php -c Index -a testCommandLine
 * 
 * ...will execute IndexController->testCommandLineAction()
 */

// Load the abstract class
require "AppObject.php";
// Load library files
foreach (glob(__DIR__."/library/*.php") as $filename) { require $filename; }
// Load all models
foreach (glob(__DIR__."/models/*.php") as $filename) { require $filename; }
// Load all controllers
foreach (glob(__DIR__."/controllers/*.php") as $filename) { require $filename; }

// Find the controller and action command line options
$shortopts  = "";
$shortopts .= "c:";
$shortopts .= "a:";
$options = getopt($shortopts,[]);

// Create instance of the controller and call the action
$controller = $options["c"]."Controller";
$action = $options["a"]."Action";
$controller = new $controller;
$controller->$action();