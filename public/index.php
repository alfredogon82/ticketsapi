<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';


$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello!");
    return $response;
});

/* Users */

$app->get('/users', function (Request $request, Response $response, $args) {


    require __DIR__ . '/../classes/users.class.php';
    $authorization = $request->getHeader('Authorization');

    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $users = New getUsers();
        $_users_list = $users->getAllUsers();
        
        $payload = json_encode($_users_list);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
     
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }
    
});

$app->get('/user/{id_user}', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/users.class.php';
    $authorization = $request->getHeader('Authorization');

    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_user = $args['id_user'];
        $user = New getUserInfo($id_user);
        $_user_info = $user->getUser();
        
        $payload = json_encode($_user_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});

$app->post('/deactivate/user', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/users.class.php';
    $postArr = $request->getParsedBody();

    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_user = $postArr['id_user'];

        $user = New OnOff($id_user,0);
        $_user_info = $user->statusUser();

        $payload = json_encode($_user_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }
});


$app->post('/activate/user', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/users.class.php';
    $postArr = $request->getParsedBody();
    
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_user = $postArr['id_user'];

        $user = New OnOff($id_user,1);
        $_user_info = $user->statusUser();

        $payload = json_encode($_user_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});


$app->post('/create/user', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/users.class.php';
    $postArr = $request->getParsedBody();

    $id_user_type = $postArr["id_user_type"];
    $name = $postArr["name"];
    $lastname = $postArr["lastname"];
    $email = $postArr["email"];
    $password = $postArr["password"];

    $user = New insertUser($id_user_type, $name, $lastname, $email, $password);
    $resp = $user->insert();

    $response->getBody()->write($resp);
    $answer = json_decode($resp);
    if($answer->code=="1"){
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
  
});


$app->post('/login', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/users.class.php';
    $postArr = $request->getParsedBody();

    $email = $postArr["email"];
    $password = $postArr["password"];

    $user = New userLogin($email, $password);
    $resp = $user->checkPassword();
    $response->getBody()->write($resp);
    $answer = json_decode($resp);
    if($answer->code=="1"){
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
    
});


$app->put('/update/user', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/users.class.php';
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){
        
        $postArr = $request->getQueryParams();

        $id_user = $postArr["id_user"];
        $id_user_type = $postArr["id_user_type"];
        $name = $postArr["name"];
        $lastname = $postArr["lastname"];
        $email = $postArr["email"];
        $password = $postArr["password"];

        $user = New updateUser($id_user, $id_user_type, $name, $lastname, $email, $password);
        $resp = $user->update();
        $response->getBody()->write($resp);

        $answer = json_decode($resp);
        if($answer->code=="1"){
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else if ($answer->code=="0"){
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }
  
});

/* End users */

/* Tickets */

$app->get('/tickets', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/tickets.class.php';
    $authorization = $request->getHeader('Authorization');

    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $tickets = New getTickets();
        $_tickets_list = $tickets->getAllTickets();
        
        $payload = json_encode($_tickets_list);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);

    }

});


$app->get('/ticket/{id_ticket}', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/tickets.class.php';
    $authorization = $request->getHeader('Authorization');

    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_ticket = $args['id_ticket'];
        $ticket = New getTicketInfo($id_ticket);
        $_ticket_info = $ticket->getTicket();
        
        $payload = json_encode($_ticket_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});

$app->post('/ticket/create', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/tickets.class.php';
    $postArr = $request->getParsedBody();

    $user_id = $postArr["user_id"];
    $state_id = $postArr["state_id"];
    $title = $postArr["title"];
    $text = $postArr["text"];
    $active = $postArr["active"];

    $ticket = New insertTicket($user_id, $state_id, $title, $text, $active);
    $resp = $ticket->insert();

    $response->getBody()->write($resp);
    $answer = json_decode($resp);
    if($answer->code=="1"){
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
  
});

$app->put('/update/ticket', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/tickets.class.php';
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){
        
        $postArr = $request->getQueryParams();

        $id_ticket = $postArr["id_ticket"];
        $user_id = $postArr["user_id"];
        $state_id = $postArr["state_id"];
        $title = $postArr["title"];
        $text = $postArr["text"];
        $active = $postArr["active"];

        $ticket = New updateTicket($id_ticket, $user_id, $state_id, $title, $text, $active);
        $resp = $ticket->update();
        $response->getBody()->write($resp);

        $answer = json_decode($resp);
        if($answer->code=="1"){
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else if ($answer->code=="0"){
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }
  
});




$app->post('/activate/ticket', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/tickets.class.php';
    $postArr = $request->getParsedBody();
    
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_ticket = $postArr['id_ticket'];

        $ticket = New OnOff($id_ticket,1);
        $_ticket_info = $user->statusTicket();

        $payload = json_encode($_ticket_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});

$app->post('/deactivate/ticket', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/tickets.class.php';
    $postArr = $request->getParsedBody();
    
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_ticket = $postArr['id_ticket'];

        $ticket = New OnOff($id_ticket,0);
        $_ticket_info = $user->statusTicket();

        $payload = json_encode($_ticket_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});

/* End Tickets */

/* Usertype */

$app->get('/usertypes', function (Request $request, Response $response, $args) {


    require __DIR__ . '/../classes/usertypes.class.php';
    $authorization = $request->getHeader('Authorization');

    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $usertypes = New getUserType();
        $_usertypes_list = $usertypes->getAllUserTypes();
        
        $payload = json_encode($_usertypes_list);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
     
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }
    
});

$app->post('/usertype/create', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/usertypes.class.php';
    $postArr = $request->getParsedBody();

    $name = $postArr["name"];

    $usertype = New insertUsertype($name);
    $resp = $usertype->insert();

    $response->getBody()->write($resp);
    $answer = json_decode($resp);
    if($answer->code=="1"){
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
  
});

$app->put('/update/usertype', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/usertypes.class.php';
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){
        
        $postArr = $request->getQueryParams();

        $id = $postArr["id"];
        $name = $postArr["name"];

        $user = New updateUserType($id, $name);
        $resp = $user->update();
        $response->getBody()->write($resp);

        $answer = json_decode($resp);
        if($answer->code=="1"){
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else if ($answer->code=="0"){
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }
  
});

$app->post('/activate/usertype', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/usertypes.class.php';
    $postArr = $request->getParsedBody();
    
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_usertype = $postArr['id_usertype'];

        $usertype = New OnOffUsertype($id_ticket,1);
        $_usertype_info = $usertype->statusUsertype();

        $payload = json_encode($_usertype_info);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});

$app->post('/deactivate/usertype', function (Request $request, Response $response, $args) {

    require __DIR__ . '/../classes/usertypes.class.php';
    $postArr = $request->getParsedBody();
    
    $authorization = $request->getHeader('Authorization');
    $validation = new TokenValidation($authorization[0]);
    $check = $validation->validate();

    $val = json_decode($check);

    if($val->code=="1"){

        $id_usertype = $postArr['id_usertype'];

        $usertype = New OnOffUsertype($id_ticket,0);
        $_usertype_info = $usertype->statusUsertype();

        $payload = json_encode($_usertype_info);


        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
    } else if($val->code=="0"){

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    
    }

});

/* End usertype*/

try {
    $app->run();     
} catch (Exception $e) {    
  // We display a error message
  die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
}