<?php

class Database {
    private $host = "your_host"; // Replace with your MySQL host
    private $username = "your_username"; // Replace with your MySQL username
    private $password = "your_password"; // Replace with your MySQL password
    private $database = "your_database"; // Replace with your MySQL database name

    private $connection;

    // Constructor
    public function __construct() {
        $this->connect();
    }

    // Connect to the database
    private function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check for connection errors
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Execute a query
    public function query($sql) {
        return $this->connection->query($sql);
    }

    // Get the last inserted ID
    public function getLastInsertedId() {
        return $this->connection->insert_id;
    }

    // Close the database connection
    public function close() {
        $this->connection->close();
    }
}

// Example usage:
// $db = new Database();
// $result = $db->query("SELECT * FROM your_table");
// while ($row = $result->fetch_assoc()) {
//     // Process each row
// }
// $db->close();

?>
