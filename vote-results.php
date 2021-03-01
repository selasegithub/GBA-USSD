<?php
/**
 * Created by PhpStorm.
 * User: kqwameselase
 * Date: 2019-02-27
 * Time: 23:31
 */
	require_once('db.php');
	$db = new DB();

	//total vote count
    $totalVotes = 0;

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
            $totalVotes += $brand['votes'];
        }

        echo '<hr>Total vote count: '.$totalVotes.'<hr>';
    }
    else{
        echo "Results from db not an array";
        //echo '<li>'.$brand['name'].': '.$brand['votes'].' votes</li>';
    }