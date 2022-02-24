<?php
require 'conn.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo 'An error occurred'; //if http request method is not post
    exit();
}

$votedProduct = htmlentities($_POST['votedProd']);

//put all brand_id into an array and check if the selected product matches with any of the products being voted for
$ldProdsId = pg_query($con,"SELECT product_id FROM brands");
if(pg_num_rows($ldProdsId) > 0){

    $prodIdArr = array();
    while($prodIdDet = pg_fetch_array($ldProdsId)){
        array_push($prodIdArr,$prodIdDet['product_id']);
    }

    if(in_array($votedProduct,$prodIdArr)){

        //find the voted product by the product_id and up their vote count by 1
        $ldVotedPrd = pg_query($con,"SELECT votes FROM brands WHERE product_id = '".$votedProduct."'");
        if(pg_num_rows($ldVotedPrd) == 1){

            $dbVoteCount = pg_fetch_array($ldVotedPrd);
            $voteCount = $dbVoteCount['votes'];
            $voteCount++; //vote count increased by 1

            //now save the new vote count to the database
            $updtVoteCount = pg_query($con,"UPDATE brands SET votes = '".$voteCount."' WHERE product_id = '".$votedProduct."'");
            if($updtVoteCount && pg_affected_rows($updtVoteCount) == 1){

            //get the name of the product voted for
            $getName = pg_query($con,"SELECT name FROM brands WHERE product_id = '".$votedProduct."'");
                $prodName = pg_fetch_array($getName);

                echo 'You successfully voted for '.$prodName['name'];

            }else{
                echo 'Error'; //either there was a query error or the number of rows affected is not equal to 1
            }

        }else{
            echo 'Error'; //if the num rows is not equal to 1. this will not happen
            exit();
        }

    }else{
        echo 'Error'; //if the selected item does not exist
        exit();
    }

}else{
    echo 'Error'; //if no products were found in the db
    exit();
}