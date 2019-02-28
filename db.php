<?php
/**
 * Created by PhpStorm.
 * User: kqwameselase
 * Date: 2019-02-27
 * Time: 22:53
 */
	class DB {
		const DB_NAME = 'votes.sqlite';

		protected $db;

		function __construct() {
			$this->db = new PDO('sqlite:'.self::DB_NAME);
		}

		function init() {
			// Create two tables, one to store the brands being voted on and their vote counts (brands) and one to store the people that have voted (voters).
			$this->db->exec('CREATE TABLE IF NOT EXISTS brands (id INTEGER PRIMARY KEY, name TEXT, votes INTEGER);');
			$this->db->exec('CREATE TABLE IF NOT EXISTS voters (id INTEGER PRIMARY KEY, phone_number TEXT, voted_for INTEGER);');
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

			foreach ($result as $row)
			{
				$brand['id'] = $row['id'];
				$brand['name'] = $row['name'];
				$brand['votes'] = $row['votes'];

				$brands[] = $brand;
			}

			return $brands;
		}

		function save_vote($phone_number, $voted_for) {
			// Just the digits, please
			$phone_number = preg_replace('/\D/', '', $phone_number);

			// Check to see if person has already voted
			$stmt = $this->db->prepare('SELECT COUNT(*) FROM voters WHERE phone_number=?');
			$stmt->execute(array($phone_number));

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
		}
	}