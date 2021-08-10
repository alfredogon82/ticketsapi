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

            $data = JWT::decode($this->token, secret_key, array('HS256'));

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
                "iss" => issuer_claim,
                "aud" => audience_claim,
                "iat" => issuedat_claim,
                "nbf" => notbefore_claim,
                "exp" => expire_claim,
                "data" => array(
                    "id" => $id,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email,
                    "id_user_type" => $id_user_type,
                    "expireAt" => expire_claim
            ));

            $jwt = JWT::encode($token, secret_key);

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