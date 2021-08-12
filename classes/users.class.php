<?php 

require_once ('database.class.php');
require_once ('../config/config.php');
require_once ('jwt.class.php');

trait EncryptPassword{
    
    public function encrypt(string $password){
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        return $encrypted_password;
    }

}


class checkIfUserExists{

    private $email;

    public function __construct(string $email){
        $this->email = $email;
    }

    public function checkIfExists(){

        try {

            $db = database::getInstance();
          
            $sql = "SELECT * FROM users where email=:email";
            $stm = $db->prepare($sql);
            $stm->bindParam(':email', $this->email, PDO::PARAM_INT);
            $stm->execute();
            $result = $stm->fetch();

            if(empty($result)){
                return "1";
            } else  {
                return "0";
            }
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }
}


class userLogin{

    use TokenCreation;
    use EncryptPassword;
    
    private $email;
    private $password;


    public function __construct(string $email, string $password){

        $this->email = $email;
        $this->password = $password;
        
    }

    public function checkPassword(){

        try {

            $db = database::getInstance();
            
            $sql = "SELECT * FROM users where active='1' and email=:email";
            $stm = $db->prepare($sql);
            $stm->bindParam(':email', $this->email, PDO::PARAM_INT);
            $stm->execute();
            $result = $stm->fetch();

            if(password_verify($this->password, $result["password"])){
                $result_token = $this->createToken($result["id"], $result["id_user_type"],$result["email"],$result["name"],$result["lastname"]);
                return $result_token;
            } else {
                $res = json_encode(
                    array(
                        "code" => "0",
                        "message" => "Sorry dude!, credentials are incorrect."
                    ));
                return $res;
            }
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}

class insertUser extends checkIfUserExists{

    use EncryptPassword;

    private $id_user_type;
    private $name;
    private $lastname;
    private $email;
    private $password;
    

    public function __construct(int $id_user_type, string $name, string $lastname, string $email, string $password){

        $this->id_user_type = $id_user_type;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        
    }

    public function insert(){

        try {

            $db = database::getInstance();
            $password = $this->encrypt($this->password);

            $check = $password = parent::checkIfExists($this->email);

            if($check=="1"){

                $sql = $db->prepare("INSERT INTO users (id_user_type, name, lastname, email, password) VALUES (?, ?, ?, ?, ?)");

                $sql->bindParam(1, $this->id_user_type);
                $sql->bindParam(2, $this->name);
                $sql->bindParam(3, $this->lastname);
                $sql->bindParam(4, $this->email);
                $sql->bindParam(5, $password);

                $result = $sql->execute();

                if($result==true){
                    $res = json_encode(
                        array(
                            "code" => "1",
                            "message" => "Inserted!."
                        ));
                    
                } else {
                    $res = json_encode(
                        array(
                            "code" => "0",
                            "message" => "Sorry dude!, not inserted."
                        ));
                }

            } else {
                $res = json_encode(
                    array(
                        "code" => "0",
                        "message" => "Email already registered!"
                    ));
            }

            return $res;

        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}

class updateUser extends checkIfUserExists{

    use EncryptPassword;

    private $id_user;
    private $id_user_type;
    private $name;
    private $lastname;
    private $email;
    private $password;
    

    public function __construct(int $id_user, int $id_user_type, string $name, string $lastname, string $email, string $password=''){

        $this->id_user = $id_user;
        $this->id_user_type = $id_user_type;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        
    }

    public function update(){

        try {

            $db = database::getInstance();
            $password = $this->encrypt($this->password);
            
            $check = parent::checkIfExists($this->email);

            if($check=="1"){

                if(!empty($password)){

                    $sql = "UPDATE users SET id_user_type=?, name=?, lastname=?, email=?, password=? WHERE id=?";
                    $stmt= $db->prepare($sql);
                    $result = $stmt->execute([$this->id_user_type, $this->name, $this->lastname, $this->email, $password, $this->id_user]);
                    
                } else {

                    $sql = "UPDATE users SET id_user_type=?, name=?, lastname=?, email=? WHERE id=?";
                    $stmt= $db->prepare($sql);
                    $result = $stmt->execute([$this->id_user_type, $this->name, $this->lastname, $this->email, $this->id_user]);
                    
                }

                
                if($result==true){
                    $res = json_encode(
                        array(
                            "code" => "1",
                            "message" => "Updated!"
                        ));
                    
                } else {
                    $res = json_encode(
                        array(
                            "code" => "0",
                            "message" => "Sorry dude!, not updated."
                        ));
                }

            } else {

                $res = json_encode(
                    array(
                        "code" => "0",
                        "message" => "Email already registered!"
                    ));

            }

            return $res;

        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}


class getUsers{

    public function getAllUsers(){

        try {

            $db = database::getInstance();

            $sql = 'SELECT id, name, lastname, email, active, date FROM users';
            $stm = $db->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}

class getUserInfo{

    public function __construct(int $id_user){
        $this->id_user = $id_user;
    }

    public function getUser(){

        try {

            $db = database::getInstance();

            $sql = "SELECT id, name, lastname, email, active, date FROM users where id=:id";
            $stm = $db->prepare($sql);
            $stm->bindParam(':id', $this->id_user, PDO::PARAM_INT);
            $stm->execute();
            $result = $stm->fetch();

            return $result;
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}

class OnOff{

    public function __construct(int $id_user, int $actdeact){
        $this->id_user = $id_user;
        $this->actdeact = $actdeact;
    }

    public function statusUser(){

        try {

            $db = database::getInstance();

            $sql = "UPDATE users SET active=? WHERE id=?";
            $stmt= $db->prepare($sql);
            $result = $stmt->execute([$this->actdeact, $this->id_user]);

            return $result;
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}

