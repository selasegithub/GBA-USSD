<?php
/**
 * Created by PhpStorm.
 * User: kqwameselase
 * Date: 2019-02-27
 * Time: 10:29
 */
date_default_timezone_set('Africa/Ghana');

require_once('db.php');
header('Content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: http://apps.smsgh.com", false);
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");



// Begin by reading the HTTP request body contents.
// Since we expect is to be in JSON format, let's parse as well.
$ussdRequest = json_decode(@file_get_contents('php://input'));

// Our response object. We shall use PHP's json_encode function
// to convert the various properties (we'll set later) into JSON.
$ussdResponse = new stdClass;

// Check if no errors occured.
if ($ussdRequest != NULL)
    switch ($ussdRequest->Type) {
        // Initiation request. This is the first type of request every
        // USSD application will receive. So let's display our main menu.
        case 'Initiation':

            $ussdResponse->Message =
                "Product of the year #GhBevAwards18 .\n" .
                "1. Kpoo Keke\n2. Storm Energy\n3. Bel Aqua\n4. Guinness\n5. Club Beer\n6. Vitamilk\n7. Ɔdehyeɛ Beer"; // \n6. Origin Beer1 \n7. Club Beer1 \n8. Star Beer1 \n9. Guinness1 \n10. Gulder1";
            $ussdResponse->Type = 'Response';
            break;


        // Response request. This is where all other interactions occur.
        // Every time the mobile subscriber responds to any of our vote options,
        // this will be the type of request we shall receive.
        case 'Response':
            switch ($ussdRequest->Sequence) {

                // Menu selection. Note that everytime we receive a request
                // in a particular session, the Sequence will increase by 1.
                // Sequence number 1 was that of the initiation request.
                case 2:
                    $items = array('1' => 'Kpoo Keke', '2' => 'Storm Energy Drink', '3' => 'Bel Aqua Mineral Water', '4' => 'Guinness Foreign Extra Stout', '5' => 'Club Beer', '6' => 'Vitamilk', '7' => 'Ɔdehyeɛ Beer'); //, '6' => 'Origin Beer1', '7' => 'Club Beer1', '8' => 'Star Beer1', '9' => 'Guinness1', '10' => 'Gulder1');
                    if (isset($items[$ussdRequest->Message])) {
                        $ussdResponse->Message = 'Please confirm your preferred product of the year is  '
                            . $items[$ussdRequest->Message] . "?\n1. Yes\n2. No";
                        $ussdResponse->Type = 'Response';
                        $ussdResponse->ClientState = $items[$ussdRequest->Message];
                    } else {
                        $ussdResponse->Message = 'Invalid option.';
                        $ussdResponse->Type = 'Release';
                    }
                    break;

                // Order confirmation. Here the user has responded to our
                // previously sent menu (i.e. Please confirm your preferred product of the year is...)
                // Note that we saved the option the user selected in our
                // previous dialog into the ClientState property.
                case 3:
                    switch ($ussdRequest->Message) {
                        case '1':
                            $db = new DB();

                            // save_vote will check to see if the person has already voted
                            $phone_number = $ussdRequest->Mobile;

                            //Return the array number for the selected vote to be used when updated votes
                            $items2 = array('1' => 'Kpoo Keke', '2' => 'Storm Energy Drink', '3' => 'Bel Aqua Mineral Water', '4' => 'Guinness Foreign Extra Stout', '5' => 'Club Beer', '6' => 'Vitamilk', '7' => 'Ɔdehyeɛ Beer'); //, '6' => 'Origin Beer1', '7' => 'Club Beer1', '8' => 'Star Beer1', '9' => 'Guinness1', '10' => 'Gulder1');
                            $voted_for = array_search($ussdRequest->ClientState, $items2) ;

                            $response = $db->save_vote($phone_number, $voted_for);
                            //echo $response;

                            //Get Response
                            if ($response === true){
                                //Display Success message after vote saved.
                                $ussdResponse->Message =
                                    'Thank you. You have successfully voted for '
                                    . $ussdRequest->ClientState . ' as your preferred Product of the Year.';
                            }
                            else {
                                $ussdResponse->Message = 'Sorry you can only vote once.';
                            }



                            break;
                        case '2':
                            $ussdResponse->Message = 'Vote cancelled.';
                            break;
                        default:
                            $ussdResponse->Message = 'Invalid selection';
                            break;
                    }
                    $ussdResponse->Type = "Release";
                    break;

                // Unexpected request. If the code here should ever
                // execute, it means the request is probably forged.
                default:
                    $ussdResponse->Message = 'Unexpected request.';
                    $ussdResponse->Type = 'Release';
                    break;
            }
            break;

        // Session cleanup.
        // Not much to do here.
        default:
            $ussdResponse->Message = 'Duh.';
            $ussdResponse->Type = 'Release';
            break;
    }
// An error has occured.
// Probably the request JSON could not be parsed.
else {
    $ussdResponse->Message = 'Invalid USSD request.';
    $ussdResponse->Type = 'Release';
}
// Let's set the HTTP content-type of our response, encode our
// USSD response object into JSON, and flush the output.

header('Content-type: application/json; charset=utf-8');
echo json_encode($ussdResponse);


