<?php 
    require_once ('database.class.php');
    require_once ('../config/config.php');
    require_once ('jwt.class.php');

    class getUserType{

        public function getAllUserTypes(){
    
            try {
    
                $db = database::getInstance();
    
                $sql = "SELECT * from user_type";  
                $stm = $db->prepare($sql);
                $stm->execute();
                $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            
            } catch (Exception $e) {
                print $e->getMessage();        
            }
    
        }
    
    }

    class updateUserType{

        private $id;
        private $name;
    
        public function __construct(int $id, string $name){
    
            $this->id = $id_;
            $this->name = $name;
            
        }
    
        public function update(){
    
            try {
    
                $db = database::getInstance();
    
                $sql = "UPDATE user_type SET name=? WHERE id=?";
                $stmt= $db->prepare($sql);
                $result = $stmt->execute([$this->name, $this->id]);
                
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
    
                return $res;
    
            
            } catch (Exception $e) {
                print $e->getMessage();        
            }
    
        }
    
    }

    class insertUsertype{

        private $name;
    
        public function __construct(string $name){
            $this->name = $name;
        }
    
        public function insert(){
    
            try {
    
                $db = database::getInstance();
    
                $sql = $db->prepare("INSERT INTO user_type (name) VALUES (?)");
    
                $sql->bindParam(1, $this->name);
    
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
    
                return $res;
    
            } catch (Exception $e) {
                print $e->getMessage();        
            }
    
        }
    
    }


class OnOffUsertype{

    public function __construct(int $id_usertype, int $actdeact){
        $this->id_usertype = $id_usertype;
        $this->actdeact = $actdeact;
    }

    public function statusUsertype(){

        try {

            $db = database::getInstance();

            $sql = "UPDATE user_type SET active=? WHERE id=?";
            $stmt= $db->prepare($sql);
            $result = $stmt->execute([$this->actdeact, $this->id_usertype]);

            return $result;
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}

    
?>