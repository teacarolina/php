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

    function DeleteUser($user_id) {
        $sql = "DELETE FROM users WHERE id=:user_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":user_id_IN", $user_id);
        $statement->execute();

                    //ett sätt att skriva  
        $message = new stdClass();
        if($statement->rowCount() > 0){
            $message->text = "User with id $user_id removed";
            return $message;
        }

        $message->text = "No user with id $user_id was found";
        return $message;
    }
                            //tar det tomma värdet om man inte skickar in ett värde
    function UpdateUser($id, $username = "", $password = "") {
        //kolla om det funkar: $echo "$id, $username $password"; 
        $error = new stdClass();
        //kallar på funktionerna i funktionen för att uppdatera samtliga samtidigt 
        if(!empty($username)) {
            $error->message = $this->UpdateUsername($id, $username);
        }
            //kollar vilket värde variablen har 
        if(!empty($password)) {
            $error->message = $this->UpdatePassword($id, $password);
        }
        return $error;
    }

    function UpdateUsername($id, $username) {
        $sql = "UPDATE users SET username=:username_IN WHERE id=:user_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":username_IN", $username);
        $statement->bindParam(":user_id_IN", $id);
        $statement->execute();

        if($statement->rowCount() < 1) {
            return "No user with id=$id was found"; 
        }
    }

    function UpdatePassword($id, $password) {
        $sql = "UPDATE users SET password=:password_IN WHERE id=:user_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":password_IN", $username);
        $statement->bindParam(":user_id_IN", $id);
        $statement->execute();

        if($statement->rowCount() < 1) {
            return "No user with id=$id was found"; 
        }
    }
}
?>