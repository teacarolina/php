<?php
class User {
    private $database_connection;
    private $user_id; 
    private $username; 
    //private $user_email;
    private $user_token; 

    function __construct($db) {
        $this->database_connection = $db; 
    }
                                    //$user_email_IN
    function CreateUser($username_IN, $user_password_IN) {
                            //&& !empty($user_email_IN)
        if(!empty($username_IN) && !empty($user_password_IN)) {
                                                                    //OR email = :email_IN
            $sql = "SELECT id FROM users WHERE username = :username_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);

            if(!$statement->execute()) {
                echo "Could not execute query!";
                die();
            }

            $num_rows = $statement->rowCount();
            if($num_rows > 0) {
                echo "The user is already registered";
                die();
            }
            //för att testa koden så här långt skriv så här:
            //echo $username_IN 
                            // . " </br> " . $user_email_IN
                                        //email, role                   //:email_IN, 'user'                                    
            $sql = "INSERT INTO users (username, password) VALUES (:username_IN, :password_IN)"; 
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":password_IN", $user_password_IN);

            if(!$statement->execute()) {
                echo "Could not create user";
                die();
            }

            $this->username = $username_IN; 
            
            echo "Username: $this->username";
            die();
        } else {
            echo "All arguments needs a value";
            die();
        }
    }

    function GetAllUsers() {
        $sql = "SELECT username FROM users";
        $statement = $this->database_connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    function GetUser($user_id) {
        $sql = "SELECT username FROM users WHERE id=:user_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":user_id_IN", $user_id);
        if(!$statement->execute() || $statement->rowCount() < 1){
            $error = new stdClass();
            $error->message = "User does not exist";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }
    

        $row = $statement->fetch();

        $this->username = $row['username'];

        return $row;
    }
}
?>