<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote for Product of the Year</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="contentArea">
    <?php
    require 'conn.php';
    if($db_connection){
//    echo 'Database connected';
        //get the list of brands in the database
        $ldBrands = pg_query($db_connection, "SELECT name,id FROM brands");
        $numRws = pg_num_rows($ldBrands);
        if($numRws > 0){
            $brandsArr = array();
            while($brandDets = pg_fetch_array($ldBrands)){
                $brandsArr[] = $brandDets;
            }
        }
    }else{
        die('An error occurred');
        exit();
    }

    if($numRws > 0){
        ?>
        <div>
            <?php
            foreach($brandsArr as $brandItm){
                ?>
                <div class="voteItem">
                    <label>
                        <input type="radio" value="<?php echo $brandItm['id']; ?>" name="poty" required> <?php echo $brandItm['name']; ?>
                    </label>
                </div>
                <?php
            }
            ?>
        </div>
        <div>
            <button class="btn" id="submitBtn" type="button">Vote</button>
        </div>
        <?php
    }else{
        echo 'No brands were found';
        exit();
    }
    ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="app.js"></script>
</body>
</html>
