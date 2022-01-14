#! /usr/bin/php-cgi
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
 * $ php-cgi cfcli.php controller=Index action=testCommandLine
 * 
 * ...will execute IndexController->testCommandLineAction()
 * 
 * You can also add additional parameters to the command and
 * those we'll become available to your action vie $_GET.
 * 
 * For example:
 * 
 * $ php-cgi cfcli.php controller=Index action=testCommandLine a=one b=two
 * 
 * ...will set $_GET["a"] = "one" and $_GET["b"] = "two" in
 * IndexController->testCommandLineAction() * 
 */

// Load the abstract class
require "AppObject.php";
// Load library files
foreach (glob(__DIR__."/library/*.php") as $filename) { require $filename; }
// Load all models
foreach (glob(__DIR__."/models/*.php") as $filename) { require $filename; }
// Load all controllers
foreach (glob(__DIR__."/controllers/*.php") as $filename) { require $filename; }

// Create instance of the controller and call the action
$controller = $_GET["controller"]."Controller";
$action = $_GET["action"]."Action";
$controller = new $controller;
$controller->$action();