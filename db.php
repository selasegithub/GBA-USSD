<?php
/**
 * Created by PhpStorm.
 * User: kqwameselase
 * Date: 2019-02-27
 * Time: 22:53
 */


//$driver = 'pgsql';
/*$host = 'ec2-54-197-232-203.compute-1.amazonaws.com';
$user = 'kdyztookkckndr';
$password = '29846ef8b03eecd8ac209177adab65a0c73f8b3c4b92de2276dcd010b4f69539';
$dbname = 'dfr4hedmq20d1m';
$port = '5432';*/

	class DB {
		//const DB_NAME = 'postgresql-spherical-25858';

        protected $db;



           /* $dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

            try{
                // create a PostgreSQL database connection
                $conn = new PDO($dsn);

                // display a message if connected to the PostgreSQL successfully
                if($conn){
                echo "Connected to the <strong>$db</strong> database successfully!";
                }
            }catch (PDOException $e){
                // report error message
                echo $e->getMessage();
            }*/


		function __construct() {

            $host = 'ec2-54-197-232-203.compute-1.amazonaws.com';
            $user = 'kdyztookkckndr';
            $password = '29846ef8b03eecd8ac209177adab65a0c73f8b3c4b92de2276dcd010b4f69539';
            $dbname = 'dfr4hedmq20d1m';
            $port = '5432';

            try{
                //Set data source name
                //$this->db = new PDO('sqlite:'.self::DB_NAME);
                //$dsn = "pgsql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname . ";user=" . $user . ";password=" . $password;

                //Create a pdo instance
                /*$this->db = new PDO($dsn, $user, $password);
                $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
                $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);*/


                $this->db = new PDO('pgsql:host=$host;dbname=$dbname;user=$user;port=$port;password=$password');


            }
            catch (PDOException $e){
                echo 'Connection failed: ' . $e->getMessage();
            }
			

		}

		function init() {
		    try{
                // Create two tables, one to store the brands being voted on and their vote counts (brands) and one to store the people that have voted (voters).
                $this->db->exec('CREATE TABLE IF NOT EXISTS brands (id INTEGER PRIMARY KEY, name TEXT, votes INTEGER);');
                $this->db->exec('CREATE TABLE IF NOT EXISTS voters (id INTEGER PRIMARY KEY, phone_number TEXT, voted_for INTEGER);');
                echo "Table 'brands and voters' added to the database";

                //Try catch exception to check connection to Database.
                try{
                    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //echo "Connected !";

                }  catch (PDOException $e)  {
                    echo $e;
                    die();
                }
                //Check to see if tables created
                try{
                    //Query voters table for all rows
                    $results = "SELECT phone_number, voted_for FROM voters";
                    $rslts = $this->db->prepare($results);

                    //Verify execution of query
                    if($rslts->execute()){
                        echo "You have successfully run select on voters";
                        while ($row = $rslts->fetch(PDO::FETCH_ASSOC)){
                            $names = $row['phone_number'];
                            $votes = $row['voted_for'];
                        }
                        echo $names;
                        echo $votes;
                    }
                    else {
                        echo "There is some problem in table query";
                    }

                }  catch (PDOException $e)  {
                    echo $e;
                    die();
                }


            } catch (PDOException $e){
                $errors = $this->db->errorInfo();
                echo $e;
                echo $errors;
                die();
            }

		}

		function add_brand($name) {
			// Check to make sure the brand name doesn't already exist
			$stmt = $this->db->prepare('SELECT COUNT(*) FROM brands WHERE name=?');
			$stmt->execute(array($name));

			// If not, insert it
			if ($stmt->fetchColumn() == 0)
			{
				$stmt = $this->db->prepare('INSERT INTO brands (name, votes) VALUES (?, 0)');
				$stmt->execute(array($name));
			}
		}

		function get_brands() {
			$result = $this->db->query('SELECT * FROM brands');

			/*foreach ($result as $row)
			{
				$brand['id'] = $row['id'];
				$brand['name'] = $row['name'];
				$brand['votes'] = $row['votes'];

				$brands[] = $brand;
			}

			return $brands;*/

            if (is_array($result) || is_object($result))
            {
                foreach ($result as $row)
                {
                    $brand['id'] = $row['id'];
                    $brand['name'] = $row['name'];
                    $brand['votes'] = $row['votes'];

                    $brands[] = $brand;
                }

                return $brands;
            }
            else{
                echo "Results not an array";
            }

            /**
             * Determine if a variable is iterable. i.e. can be used to loop over.
             *
             * @return bool
             */
            /*function is_iterable($var)
            {
                return $var !== null
                    && (is_array($var)
                        || $var instanceof Traversable
                        || $var instanceof Iterator
                        || $var instanceof IteratorAggregate
                    );
            }

            $values = get_values();

            if (is_iterable($values))
            {
                foreach ($values as $value)
                {
                    // do stuff...
                }
            }*/
		}

        /**
         * @param $phone_number
         * @param $voted_for
         * @return string
         */
        function save_vote($phone_number, $voted_for) {
            // Just the digits, please
            $phone_number = intval(preg_replace('/\D/', '', $phone_number));

            // Check to see if person has already voted
            //$stmt = $this->db->prepare("SELECT COUNT(*) FROM voters WHERE phone_number=?");
            //$stmt->bindValue(1, $phone_number, PDO::PARAM_INT);
            //$stmt->execute();

            //Try catch exception to check connection to Database.
            try{
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected !";
                //Check to see if person has already voted
                try{
                    $stmt = "SELECT COUNT(*) FROM voters WHERE phone_number=?";
                    $results = $this->db->prepare($stmt);
                    $results->bindParam(1, $phone_number, PDO::PARAM_INT);

                    //Verify execution of query
                    if($results->execute()){
                        // If number not already voted, save their vote
                        if ($results->fetchColumn() == 0)
                        {
                            // Save voter
                            $stmt2 = "INSERT INTO voters (phone_number, voted_for) VALUES (?, ?)";
                            $stmt2query = $this->db->prepare($stmt2);
                            $stmt2query->bindValue(1, $phone_number, PDO::PARAM_INT);
                            $stmt2query->bindValue(2, $voted_for, PDO::PARAM_INT);
                            $stmt2query->execute();

                            // Update vote count
                            $stmt3 = "UPDATE brands SET votes = votes + 1 WHERE id=?";
                            $stmt3query = $this->db->prepare($stmt3);
                            $stmt3query->bindValue(1,$voted_for, PDO::PARAM_INT);
                            $stmt3query->execute();

                            return true; //'Thank you, your vote has been recorded';
                        }
                        else {
                            return false; //'Sorry, you can only vote once.';
                        }
                    }
                    else {
                        return false; //"There is some problem in updating your profile. Please contact site admin";
                    }

                }  catch (PDOException $e)  {
                    echo $e;
                    die();
                }

                //$values = $results->fetchAll(PDO::FETCH_OBJ);
                //echo $values;


            }  catch (PDOException $e)  {
                echo $e;
                die();
            }


        }
/*        function save_vote($phone_number, $voted_for) {
            // Just the digits, please
            $phone_number = intval(preg_replace('/\D/', '', $phone_number));

            // Check to see if person has already voted
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM voters WHERE phone_number=?');
            $stmt->bindParam('i', $phone_number);
            $stmt->execute();

            // If not, save their vote
            if ($stmt->fetchColumn() == 0)
            {
                // Save voter
                $stmt = $this->db->prepare('INSERT INTO voters (phone_number, voted_for) VALUES (?, ?)');
                $stmt->bindParam('ii', $phone_number, $voted_for); // we suppose tha rhe $voted_for is integer if not use interval
                $stmt->execute();

                // Update vote count
                $stmt = $this->db->prepare('UPDATE brands SET votes = votes + 1 WHERE id=?');
                $stmt->bindParam('i',$voted_for);// we suppose tha rhe $voted_for is integer if not use intval
                $stmt->execute();

                return 'Thank you, your vote has been recorded';
            }
            else {
                return 'Sorry, you can only vote once.';
            }
        }*/

/*        function save_vote($phone_number, $voted_for) {
			// Just the digits, please
			$phone_number = preg_replace('/\D/', '', $phone_number);

			// Check to see if person has already voted
			$stmt = $this->db->prepare('SELECT COUNT(*) FROM voters WHERE phone_number=?');
            $stmt->bind_param(int, $phone_number);
            $stmt->execute();
			//$stmt->execute(array($phone_number));

            // If not, save their vote
			if ($stmt->fetchColumn() == 0)
			{
				// Save voter
				$stmt = $this->db->prepare('INSERT INTO voters (phone_number, voted_for) VALUES (?, ?)');
				$stmt->execute(array($phone_number, $voted_for));

				// Update vote count
				$stmt = $this->db->prepare('UPDATE brands SET votes = votes + 1 WHERE id=?');
				$stmt->execute(array($voted_for));

				return 'Thank you, your vote has been recorded';
			}
			else {
				return 'Sorry, you can only vote once.';
			}
		}*/
	}
