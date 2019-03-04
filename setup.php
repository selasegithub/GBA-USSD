<?php
/**
 * Created by PhpStorm.
 * User: kqwameselase
 * Date: 2019-02-27
 * Time: 23:14
 */

	// Sets up the database. Only needs to be run once. After that you can delete this file.
	require_once('db.php');

	$db = new DB();

	// Create the database tables
	$db->init();

	// Add some teams
	$db->add_brand('Origin Beer');
	$db->add_brand('Club Beer');
	$db->add_brand('Star Beer');
    $db->add_brand('Guinness');
    $db->add_brand('Gulder');
    /*$db->add_brand('Origin Beer1');
    $db->add_brand('Club Beer1');
    $db->add_brand('Star Beer1');
    $db->add_brand('Guinness1');
    $db->add_brand('Gulder1');*/
	echo 'Database created and brands added';
