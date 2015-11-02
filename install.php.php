<?php

/**
 * Nirph Online
 *
 * @package  Card Collector installer
 * @author   Tshepo Tema <tshepo927@gmail.com>
 */


$servername = "localhost";
$username = "zetaikhw_nirph";
$password = "nirph4321";
$database = "zetaikhw_nirph";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS `".$database."` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";

    $sql = "USE `".$database."`;";
	if ($conn->query($sql) === TRUE) {
		echo "Successfully switched to database ";		

		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` smallint(4) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(64) DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `downloads` mediumint(8) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4;
SQL;
		$conn->query($sql);

		$sql = <<<SQL
INSERT INTO `products` (`product_id`, `product_name`, `price`, `downloads`, `description`) VALUES
(1, 'Basic', 200.00, 100, 'Designed for the start up'),
(2, 'Standard', 300.00, 250, 'Designed for SMMEs'),
(3, 'Awesome', 400.00, 600, 'For the Corporates');
SQL;
		$conn->query($sql);

		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` smallint(5) NOT NULL,
  `subscribe_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
SQL;
		$conn->query($sql);



	} else {
		echo "Failed to swith to the database [".$database."]";
	}

} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

?>