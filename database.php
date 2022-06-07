<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Database {
    
    private $host = 'localhost';
    private $user = 'root';
    private $password = 'root';
    private $db = 'budget';

    /**
     * Creates a simple database-connection.
     *
     * @return PDO
     */
    private function create_connection() {
        $conn = new PDO("mysql:host=$this->host;dbname=$this->budget", $this->user, $this->password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    private function check_if_table_exist($connection, $table) {
        try {
            $connection->query("SELECT 1 FROM $table");
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }


    /**
     * Create main Table
     * ---
     * Checks if "main" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE main:
     *  - ae_id
     *  - date
     *  - ae_title
     *  - ae
     *  - betrag
     *  - place
     *  - category

     */

    private function create_main_table() {
        // here: create table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, '`main`')) {
                // sql to create table
                $sql = "CREATE TABLE main (
                    ae_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    date VARCHAR(10) NOT NULL,
                    ae_title VARCHAR(100) NOT NULL,
                    ae VARCHAR(2) NOT NULL,
                    betrag VARCHAR(60) NOT NULL,
                    place VARCHAR(60) NOT NULL,
                    category VARCHAR(60) NOT NULL,
                    )";


                // Add connection between ae and main table.
                $sql = "
                    ALTER TABLE `ae`  
                    ADD CONSTRAINT `FK_ae_main` 
                        FOREIGN KEY (`ae`) REFERENCES `ae`(`ae_name_id`) 
                            ON UPDATE CASCADE 
                            ON DELETE CASCADE;
                    ";

                // Add connection between category and main table.
                $sql = "
                    ALTER TABLE `category`  
                    ADD CONSTRAINT `FK_category_main` 
                        FOREIGN KEY (`category`) REFERENCES `cateory`(`category_id`) 
                            ON UPDATE CASCADE 
                            ON DELETE CASCADE;
                    ";

                // Add connection between place and main table.
                $sql = "
                    ALTER TABLE `place`  
                    ADD CONSTRAINT `FK_place_main` 
                        FOREIGN KEY (`place`) REFERENCES `place`(`place_id`) 
                            ON UPDATE CASCADE 
                            ON DELETE CASCADE;
                    ";
                    
                // use exec() because no results are returned
                $conn->exec($sql);
                echo "main table created successfully.<br>";
            } else {
                echo "main table already exist.<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }


    /**
     * Create ae Table
     * ---
     * Checks if "ae" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE ae:
     *  - ae_name_id
     *  - art_a_or_e

     */
    private function create_ae_table() {
        // here: create ae table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, '`ae`')) {
                // sql to create table
                $sql = "CREATE TABLE ae (
                    ae_name_id INT(60) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    art_a_or_e VARCHAR(5) NOT NULL,
                    )";
                // use exec() because no results are returned
                $conn->exec($sql);
                echo "ae table created successfully.<br>";
            } else {
                echo "ae table already exist.<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    /**
     * Create category Table
     * ---
     * Checks if "category" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE category:
     *  - category_id
     *  - category_name
     *  - ae

     */
    private function create_category_table() {
        // here: create category table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, '`category`')) {
                // sql to create table
                $sql = "CREATE TABLE category (
                    category_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    category_name VARCHAR(2) NOT NULL,
                    ae VARCHAR(100) NOT NULL
                    )";

                // Add connection between category and main table.
                $sql = "
                    ALTER TABLE `categ_ae`  
                    ADD CONSTRAINT `FK_ae_category` 
                        FOREIGN KEY (`ae`) REFERENCES `ae`(`ae_name_id`) 
                            ON UPDATE CASCADE 
                            ON DELETE CASCADE;
                    ";


                // use exec() because no results are returned
                $conn->exec($sql);
                echo "category table created successfully.<br>";
            } else {
                echo "category table already exist.<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    
    /**
     * Create place Table
     * ---
     * Checks if "place" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE place:
     *  - place_id
     *  - place

     */
    private function create_place_table() {
        // here: create place table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, '`place`')) {
                // sql to create table
                $sql = "CREATE TABLE place (
                    place_id INT(60) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    place_name VARCHAR(5) NOT NULL,
                    )";
                // use exec() because no results are returned
                $conn->exec($sql);
                echo "place table created successfully.<br>";
            } else {
                echo "place table already exist.<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    public function prepare_tables() {
        $this->create_main_table();
        $this->create_ae_table();
        $this->create_category_table();
        $this->create_place_table();
        return true;
    }



 
 /* ___________________________funktioniert_nicht,_andere Variante________________________________________________*/
/*

 $result = mysqli_query($con,"SELECT * FROM main");

echo "<table border='1'>
<tr>
<th>ae_id</th>
<th>date</th>
<th>ae_title</th>
<th>ae</th>
<th>betrag</th>
<th>place</th>
<th>category</th>

</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['ae_id'] . "</td>";
echo "<td>" . $row['date'] . "</td>";
echo "<td>" . $row['ae_title'] . "</td>";
echo "<td>" . $row['ae'] . "</td>";
echo "<td>" . $row['betrag'] . "</td>";
echo "<td>" . $row['place'] . "</td>";
echo "<td>" . $row['category'] . "</td>";
echo "</tr>";
}
echo "</table>";

*/

    /*
    public function prepare_registration() {
        $this->create_main_table();
        return true;
    }

    public function register_data($ae_title, $date, $ae_title, $betrag, $place, $category) {
        // here: insert a new user into the database.
        try {
            $conn = $this->create_connection();
            $query = "SELECT * FROM `main` WHERE ae_title = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$ae_title]);

            $user = $statement->fetchAll(PDO::FETCH_CLASS);
            if (!empty($main)) {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        // now: save data.
        try {
            $conn = $this->create_connection();

            $sql = 'INSERT INTO `main`(`date`, ae_title, ae, betrag, place, category)
            VALUES(?, ?, ?, ?, ?, ?  NOW())';
            $statement = $conn->prepare($sql);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $statement->execute([$date, $password_hash, $email]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return false;
    }

    */

    public function drop_all() {
        try {
            $conn = $this->create_connection();

            $sql = 'ALTER TABLE `ae`
                DROP FOREIGN KEY `FK_ae_main`;';
            $conn->exec($sql);

            $sql = 'ALTER TABLE `category`
                DROP FOREIGN KEY `FK_category_main`;';
            $conn->exec($sql);

            $sql = 'ALTER TABLE `place`
                DROP FOREIGN KEY `FK_place_main`;';
            $conn->exec($sql);

            $sql = 'DROP TABLE `main`';
            $conn->exec($sql);

            $sql = 'DROP TABLE `ae`';
            $conn->exec($sql);

            $sql = 'DROP TABLE `category`';
            $conn->exec($sql);

            $sql = 'DROP TABLE `place`';
            $conn->exec($sql);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }
}