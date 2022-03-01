<?php
require 'conn.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $votedProduct = htmlentities($_POST['votedProd']);

    //put all brand_id into an array and check if the selected product matches with any of the products being voted for
    $ldProdsId = pg_query($db_connection,"SELECT id FROM brands");
    if(pg_num_rows($ldProdsId) > 0){

        $prodIdArr = array();
        while($prodIdDet = pg_fetch_array($ldProdsId)){
            array_push($prodIdArr,$prodIdDet['id']);
        }

    if(in_array($votedProduct,$prodIdArr)){

        //find the voted brand by the brand_id and up their vote count by 1
        $ldVotedPrd = pg_query($db_connection,"SELECT votes FROM brands WHERE id = '".$votedProduct."'");
        if(pg_num_rows($ldVotedPrd) == 1){

            $dbVoteCount = pg_fetch_array($ldVotedPrd);
            $voteCount = $dbVoteCount['votes'];
            $voteCount++; //vote count increased by 1

            //now save the new vote count to the database
            $updtVoteCount = pg_query($db_connection,"UPDATE brands SET votes = '".$voteCount."' WHERE product_id = '".$votedProduct."'");
            if($updtVoteCount && pg_affected_rows($updtVoteCount) == 1){

            //get the name of the product voted for
            $getName = pg_query($db_connection,"SELECT name FROM brands WHERE id = '".$votedProduct."'");
                $prodName = pg_fetch_array($getName);

                echo 'You successfully voted for '.$prodName['name'];

            }else{
                echo 'Error: query error or the number of rows affected is not equal to 1'; //either there was a query error or the number of rows affected is not equal to 1
            }

            }else{
            echo 'Error: num rows is not equal to 1'; //if the num rows is not equal to 1. this will not happen
            exit();
            }

        }else{
        echo 'Error: selected item does not exist'; //if the selected item does not exist
        exit();
        }

    }else{
    echo 'Error: no products were found'; //if no products were found in the db
    exit();
    }
}else{
    echo 'An error occurred; http request method is not post'; //if http request method is not post
    exit();

}
