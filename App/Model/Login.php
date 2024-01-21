<?php

require_once "App/Controller/Database.php";

class Login extends Database
{
    /**
     * TITLE: Constructor
     * ~ DESCRIPTION: This function will initialize the database connection
     * @return void
     */
    function __construct()
    {
        // Initialize new database connection
        parent::__construct();
    }

    /**
     * TITLE: Validate Agent ID
     * ~ DESCRIPTION: This function will validate the agent ID
     * ~ PUBLIC Function
     * @param int $quantity
     * @return string $id
     */
    public function validate_Agent_ID($quantity)
    {
        while (true) {
            $id = $this->uniqID_generator($quantity);
            if ($this->validate_Unique_ID($id)) {
                return $id;
            }
        }
    }

    /**
     * TITLE: Unique ID Generator
     * ~ DESCRIPTION: This function will generate a unique ID
     * ~ PRIVATE Function
     * $quantity ~ Length of the string
     * @param int $quantity
     * @return string $randomString
     */
    private function uniqID_generator($quantity)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $quantity; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * TITLE: Validate Login Credentials
     * ~ DESCRIPTION: This function will validate the login credentials
     * ~ PUBLIC Function
     * @param string $email
     * @param string $password
     * @return redirect $this->redirect()
     * @exception EMAIL_NOT_FOUND, INVALID_PASSWORD, DATABASE_ERROR
     * @session $_SESSION['agent']['id'], $_SESSION['agent']['loggedin']
     */
    public function validate($email, $password)
    {
        try {
            // Check the database for the provided username
            $query = "SELECT email, password, agent_id FROM users WHERE email = :email";
            $stmt = $this->getConnection()->prepare($query);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Verify the password using password_verify
                if (password_verify($password, $row['password'])) {
                    $_SESSION['agent']['id'] = $row['agent_id'];
                    $_SESSION['agent']['loggedin'] = true;
                    $this->redirect("index.php"); // Redirect to a Index page
                    exit();
                } else {
                    // Invalid password
                    echo "<script>alert('Invalid password.');</script>";
                }
            } else {
                // Username not found
                echo "<script>alert('Email not found.');</script>";
            }
        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
        }
    }

    /**
     * TITLE: Register New User [MAIN]
     * ~ DESCRIPTION: This function will register a new user
     * ~ PRIVATE Function
     * @param string $email
     * @param string $password
     * @return void
     */
    private function register_new_user($email, $password)
    {
        $secure_password = password_hash($password, PASSWORD_DEFAULT);
        $agent_id = $this->validate_Agent_ID(10);
        $query = "INSERT INTO users (agent_id, email, password) VALUES (:agent_id, :email, :password)";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $secure_password, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * TITLE: Register New User [PLACEHOLDER]
     * ~ DESCRIPTION: This function will call the register_new_user function
     * ~ PUBLIC Function
     * @param string $email
     * @param string $password
     * @return void
     */
    public function register($email, $password)
    {
        $this->register_new_user($email, $password);
    }

    /**
     * TITLE: Redirect
     * ~ DESCRIPTION: This function will redirect the user to a specific page
     * ~ PUBLIC Function
     * @param string $url
     * @return redirect $this->redirect()
     */
    public function redirect($url)
    {
        // Redirect to dashboard
        parent::redirect($url);
    }

    /**
     * TITLE: Logout
     * ~ DESCRIPTION: This function will logout the user
     * ~ PUBLIC Function
     * @return void
     */
    public function logout()
    {
        // Logout
        $_SESSION = array();
        session_destroy();
        exit;
    }

    /**
     * TITLE: Destructor
     * ~ DESCRIPTION: This function will close the database connection
     * @return void
     */
    function __destruct()
    {
        // Close the database connection
        parent::__destruct();
    }
}