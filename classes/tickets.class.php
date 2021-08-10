<?php 
    require_once ('database.class.php');
    require_once ('../config/config.php');
    require_once ('jwt.class.php');

    class getTickets{

        public function getAllTickets(){
    
            try {
    
                $db = database::getInstance();
    
                $sql = "SELECT usr.name, lastname, email, tick.title, tick.text, st.name, tick.date FROM users AS usr INNER JOIN tickets AS tick ON usr.id = tick.user_id INNER JOIN state AS st ON st.id = tick.state_id";  
                $stm = $db->prepare($sql);
                $stm->execute();
                $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            
            } catch (Exception $e) {
                print $e->getMessage();        
            }
    
        }
    
    }

    class getTicketInfo{

        public function __construct(int $id_ticket){
            $this->id_ticket = $id_ticket;
        }
    
        public function getTicket(){
    
            try {
    
                $db = database::getInstance();
    
                $sql = "SELECT usr.name, lastname, email, tick.title, tick.text, st.name, tick.date FROM users AS usr INNER JOIN tickets AS tick ON usr.id = tick.user_id INNER JOIN state AS st ON st.id = tick.state_id where tick.id=:id";

                $stm = $db->prepare($sql);
                $stm->bindParam(':id', $this->id_ticket, PDO::PARAM_INT);
                $stm->execute();
                $result = $stm->fetch();
    
                return $result;
            
            } catch (Exception $e) {
                print $e->getMessage();        
            }
    
        }
    
    }


class insertTicket{

    private $user_id;
    private $state_id;
    private $title;
    private $text;
    private $active;
    

    public function __construct(int $user_id, int $state_id, string $title, string $text, string $active){

        $this->user_id = $user_id;
        $this->state_id = $state_id;
        $this->title = $title;
        $this->text = $text;
        $this->active = $active;
        
    }

    public function insert(){

        try {

            $db = database::getInstance();

            $sql = $db->prepare("INSERT INTO tickets (user_id, state_id, title, text, active) VALUES (?, ?, ?, ?, ?)");

            $sql->bindParam(1, $this->user_id);
            $sql->bindParam(2, $this->state_id);
            $sql->bindParam(3, $this->title);
            $sql->bindParam(4, $this->text);
            $sql->bindParam(5, $this->active);

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


class updateTicket{

    private $id_ticket;
    private $user_id;
    private $state_id;
    private $title;
    private $text;
    private $active;
    

    public function __construct(int $id_ticket, int $user_id, int $state_id, string $title, string $text, string $active){

        $this->id_ticket = $id_ticket;
        $this->user_id = $user_id;
        $this->state_id = $state_id;
        $this->title = $title;
        $this->text = $text;
        $this->active = $active;
        
    }

    public function update(){

        try {

            $db = database::getInstance();

            $sql = "UPDATE tickets SET user_id=?, state_id=?, title=?, text=?, active=? WHERE id=?";
            $stmt= $db->prepare($sql);
            $result = $stmt->execute([$this->user_id, $this->state_id, $this->title, $this->text, $this->active, $this->id_ticket]);
            
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

class OnOffTicket{

    public function __construct(int $id_ticket, int $actdeact){
        $this->id_ticket = $id_ticket;
        $this->actdeact = $actdeact;
    }

    public function statusTicket(){

        try {

            $db = database::getInstance();

            $sql = "UPDATE tickets SET active=? WHERE id=?";
            $stmt= $db->prepare($sql);
            $result = $stmt->execute([$this->actdeact, $this->id_ticket]);

            return $result;
        
        } catch (Exception $e) {
            print $e->getMessage();        
        }

    }

}


?>