<?php
/**
 * Created by PhpStorm.
 * User: kqwameselase
 * Date: 2019-02-27
 * Time: 23:31
 */
	require_once('db.php');
	$db = new DB();

	// Grab all the brands (and their vote counts) from the database
	$brands = $db->get_brands();
	/*echo '<ul>';*/

	/*// Loop through each brand and display how many votes they got
	foreach ($brands as $brand)
    {
        echo '<li>'.$brand['name'].': '.$brand['votes'].' votes</li>';
    }*/
	/*echo '</ul>';*/

    if (is_array($brands) || is_object($brands))
    {
        // Loop through each brand and display how many votes they got
        foreach ($brands as $brand)
        {
            echo '<li>'.$brand['name'].': '.$brand['votes'].' votes</li>';
        }
    }
    else{
        echo "Results from db not an array";
    }