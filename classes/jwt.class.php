<?php

use \Firebase\JWT\JWT;

class TokenValidation{
    
    protected $token;

    public function __construct(string $token){

        $this->token = $token;
    
    }
    
    public function validate(){

        if(empty($this->token)){
            $res = json_encode(
            array(
                "code" => "0",
                "message" => "Sorry dude!, Token doesn't exist."
            ));
        
        } else {

            $data = JWT::decode($this->token, SECRET_KEY, array('HS256'));

            if(!empty($data->data->id)){

                $res = json_encode(
                array(
                    "code" => "1",
                    "message" => "Valid token.",
                    "jwt" => $this->token,
                    "name" => $data->data->firstname." ".$data->data->lastname,
                    "email" => $data->data->email
                ));
            
            } else {

                $res = json_encode(
                array(
                    "code" => "0",
                    "message" => "Sorry dude!, credentials are incorrect."
                ));
                
            }
        }
        
        return $res;

    }
}

trait TokenCreation{

    public function createToken(int $id, int $id_user_type, string $email, string $firstname, string $lastname){

        try {

            $token = array(
                "iss" => ISSUER_CLAIM,
                "aud" => AUDIENCE_CLAIM,
                "iat" => ISSUEDAT_CLAIM,
                "nbf" => NOTBEFORE_CLAIM,
                "exp" => EXPIRE_CLAIM,
                "data" => array(
                    "id" => $id,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email,
                    "id_user_type" => $id_user_type,
                    "expireAt" => EXPIRE_CLAIM
            ));

            $jwt = JWT::encode($token, SECRET_KEY);

            $res = json_encode(
            array(
                "code" => "1",
                "message" => "Successful login.",
                "jwt" => $jwt,
                "name" => $firstname." ".$lastname,
                "email" => $email,
                "id_user_type" => $id_user_type,
                "expireAt" => $expire_claim
            ));

            return $res;

        } catch (Exception $e) {
            print $e->getMessage();        
        }
    }
}

?>