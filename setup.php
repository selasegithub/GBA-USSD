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
	$db->add_brand('Bel Aqua Active');
	$db->add_brand('Alomo Bitters');
	$db->add_brand('Coca Cola');
    $db->add_brand('Blueskies');
    $db->add_brand('Vitamilk');
    $db->add_brand('Verna Water');
    /*$db->add_brand('Bel Aqua Active2');
    $db->add_brand('Alomo Bitters2');
    $db->add_brand('Coca Cola2');
    $db->add_brand('Blueskies2');
    $db->add_brand('Vitamilk2');*/
	echo 'Database created and brands added';
