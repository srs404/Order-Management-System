<?php

require_once "../Server/Controller/Database.php";

class Customer extends Database
{
    /**
     * TITLE: Constructor
     * ~ DESCRIPTION: This function will initialize the database connections
     * @return void
     */
    function __construct()
    {
        // Initialize new database connection
        parent::__construct();
    }

    /**
     * Title: POST
     * ~ DESCRIPTION: This function will create a new user
     * ~ PROTECTED Function
     * @exception EMAIL_ALREADY_EXISTS, DATABASE_ERROR
     *  
     * Perform a dynamic INSERT operation into a table.
     *  
     * @param string $table The name of the table to insert data into.
     * @param array $data An associative array of column names and values to insert.
     *
     * @return bool True if the insertion was successful; otherwise, false.
     */
    protected function post($data)
    {
        // Construct the SQL query
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO receipts ($columns) VALUES ($placeholders)";
        $status = false;

        try {
            // Prepare and execute the SQL statement
            $stmt = parent::getConnection()->prepare($sql);
            foreach ($data as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }
            $stmt->execute();
            $status = true;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
            $status = false;
        } finally {
            return $status;
        }
    }


    /**
     * Title: GET
     * ~ DESCRIPTION: This function will get user data
     * ~ PROTECTED Function
     * @param string $email
     * @return array $row
     */

    protected function get($receipt_id)
    {
        try {
            $sql = "SELECT * FROM receipts WHERE receipt_id = :receipt_id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(":receipt_id", $receipt_id, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            // Log the error to a file or send it to an error tracking system
            error_log('Database error: ' . $e->getMessage());
            // Display a user-friendly error message
            echo "An error occurred while retrieving data. Please try again later.";
            return false;
        }
    }

    /**
     * Title: GET All USERS
     * ~ DESCRIPTION: This function will get all users
     * ~ PROTECTED Function
     * 
     * @return array $row
     */
    protected function getAll()
    {
        try {
            $sql = "SELECT * FROM receipts";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
        }
    }

    /**
     * Title: PUT
     * ~ DESCRIPTION: This function will update a user
     * ~ PROTECTED Function
     * @param string $receipt_id
     * @param array $data
     * 
     * @return bool
     */
    protected function put($receipt_id, $data)
    {
        try {
            // Construct the SQL query
            $sql = "UPDATE receipts SET ";
            foreach ($data as $column => $value) {
                $sql .= "$column = :$column, ";
            }
            $sql = rtrim($sql, ', '); // Remove trailing comma and space
            $sql .= " WHERE receipt_id = :receipt_id";

            // Prepare the SQL statement
            $stmt = $this->getConnection()->prepare($sql);

            // Bind the receipt_id parameter
            $stmt->bindParam(":receipt_id", $receipt_id, PDO::PARAM_STR);

            // Bind the parameters based on their actual types
            foreach ($data as $column => $value) {
                $paramType = PDO::PARAM_STR;
                if (is_int($value)) {
                    $paramType = PDO::PARAM_INT;
                } elseif (is_float($value)) {
                    $value = (string)$value;
                } elseif ($column == 'item_list') {
                    $value = $value;
                }
                $stmt->bindValue(":$column", $value, $paramType);
            }

            // Execute the statement and check for errors
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo());
                return false; // Indicate failure
            }

            return true; // Indicate success
        } catch (PDOException $e) {
            // Handle database connection or query errors
            // Log the error or return a specific error response
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
            return false; // Indicate failure
        }
    }


    /**
     * Title: DELETE
     * ~ DESCRIPTION: This function will delete a user
     * ~ PROTECTED Function
     * @param string $receipt_id
     * 
     * @return bool
     */
    protected function delete($receipt_id)
    {
        try {
            $sql = "DELETE FROM receipts WHERE receipt_id = :receipt_id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(":receipt_id", $receipt_id, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
        }
    }

    /**
     * TITLE: Destructor
     * ~ DESCRIPTION: This function will destroy the database connection
     * @return void
     */
    function __destruct()
    {
        // Destroy the database connection
        parent::__destruct();
    }
}
