<?php
class Post {
    private $database_connection;
    private $entries_id;
    private $message; 
    private $user_email;
    private $user_id;
    private $user_name;

    function __construct($db) {
        $this->database_connection = $db;
    }

    function CreatePost($user_name_IN, $user_email_IN, $message_IN) {
        
        if(!empty($user_name_IN) && !empty($user_email_IN) && !empty($message_IN)) {
        
        $sql = "INSERT INTO entries (name, email, message) VALUES (:name_IN, :email_IN, :message_IN)";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":name_IN", $user_name_IN);
        $statement->bindParam(":email_IN", $user_email_IN);
        $statement->bindParam(":message_IN", $message_IN);

        if(!$statement->execute()) {
            echo "Could not create post";
            die();
        }

        echo "Message posted";
        die();

        } else {
            echo "All arguments needs a value";
            die();
        }
    }

    function GetAllPosts() {
        $sql = "SELECT message FROM entries"; 
        $statement = $this->database_connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    function GetPost($entries_id) {
        $sql = "SELECT message FROM entries WHERE id=:entries_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":entries_id_IN", $entries_id);

        if(!$statement->execute() || $statement->rowCount() < 1) {
            $error = new stdClass();
            $error->message = "Message does not exist";
            $error->code = "0001";
            print_r(json_encode($error));
            die();
        }

        $row = $statement->fetch();

        $this->message = $row['message'];

        return $row;
    }

    function DeletePost($entries_id) {
        $sql = "DELETE FROM entries WHERE id=:entries_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":entries_id_IN", $entries_id);
        $statement->execute();

        $memo = new stdClass();

        if($statement->rowCount() > 0) {
            $memo->text = "Message with id $entries_id removed";
            return $memo;
        }

        $memo->text = "No message with id $entries_id was found";
        return $memo;
    }

    function UpdatePost($entries_id, $user_name = "", $user_email = "", $message = "") {
        $memo = new stdClass();

        if(!empty($user_name)) {
            $memo->message = $this->UpdateUserName($entries_id, $user_name);
        }

        if(!empty($user_email)) {
            $memo->message = $this->UpdateUserEmail($entries_id, $user_email);
        }

        if(!empty($message)) {
            $memo->message = $this->UpdateMessage($entries_id, $message);
        }

        $memo->message = "Updated message with id $entries_id";
        return $memo;
    }

    //vi hade kunnat sätta private på Update funktionerna så dem inte ska kommas åt utanför klassen utan enbart
    //i vår klass, vi har ju redan satt funktionerna i den gemensamma funktionen ovanför 
    function UpdateUserName($entries_id, $user_name) {
        $sql = "UPDATE entries SET name=:user_name_IN WHERE id=:entries_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":user_name_IN", $user_name);
        $statement->bindParam(":entries_id_IN", $entries_id);
        $statement->execute();

        if($statement->rowCount() < 1) {
            return "No message with id=$entries_id was found";
        }
    }

    function UpdateUserEmail($entries_id, $user_email) {
        $sql = "UPDATE entries SET email=:user_email_IN WHERE id=:entries_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":user_email_IN", $user_email);
        $statement->bindParam(":entries_id_IN", $entries_id);
        $statement->execute();

        if($statement->rowCount() < 1) {
            return "No message with id=$entries_id was found";
        }
    }

    function UpdateMessage($entries_id, $message) {
        $sql = "UPDATE entries SET message=:message_IN WHERE id=:entries_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":message_IN", $message);
        $statement->bindParam(":entries_id_IN", $entries_id);
        $statement->execute();

        if($statement->rowCount() < 1) {
            return "No message with id=$entries_id was found";
        }
    }

    function SearchPost($keyword) {
        $sql = "SELECT id, name, email, message FROM entries WHERE name LIKE :keyword_IN OR message LIKE :keyword_IN";
        $statement = $this->database_connection->prepare($sql);
        $keyword = '%'. $keyword .'%';
        $statement->bindParam(":keyword_IN", $keyword);
        $statement->execute();
        return json_encode($statement->fetchAll());
    }
}
?>