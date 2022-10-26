<?php
namespace app\controllers;

use Exception;
use app\models\User;
use app\core\JWTCode;
use app\core\Request;
use app\core\Database;
use app\core\Response;
use app\core\Controller;
use app\core\Application;
use app\models\LoginForm;
use app\models\UserGateway;
use app\app\resources\UserResource;
use app\models\RefreshTokenGateway;

use app\core\middlewares\PermissionMiddleware;


class AuthController extends Controller
{
      public Request $request;
      
    public function __construct()
    {
     
      $this->request=new Request();

      // make a array of action=>permission 
      $this->registerMiddleware(new PermissionMiddleware([
        'profile'=>'read-user',
        
      ]));
      
    }
    
    public function login(Request $request,Response $response)
    {
       
       $loginForm = new LoginForm();
       $errors=[];
       if($request->isPost())
       {
         
        $loginForm->loadData($request->getBody());
        if($loginForm->validate() )
        { 
          $user= $loginForm->login();
          if(! $user)
          {
             //error
          $response->setSuccess(false);
          $response->setHttpStatusCode(200);
          $response->addMessage('invalid email or password');
        
        $response->send();
        exit;
          }
          else
          {
            $tokens=$this->createToken($user);
            // login successfully 
            $user1= new UserResource($user);
            $user2=$user1->toArray($user);
            $response->setSuccess(true);
            $response->setHttpStatusCode(200);
            $response->setData($user2);
            $response->addMessage($tokens);
            $response->send();
            exit;
          }
        
        
       
        }
        else{
          // validation error
          
          $response->setSuccess(false);
          $response->setHttpStatusCode(200);
          $response->addMessage($loginForm->errors);
          
          $response->send();
          exit;
        }
       
           
       }// end of isPost()
       else
       {
         // if not post method
        $response->setSuccess(false);
        $response->setHttpStatusCode(200);
        $response->addMessage('Wrong Method type');
        $response->send();
        exit;
       }
       $this->setLayout('auth');
        return $this->render('login',[
            'model'=> $loginForm
        ]);
        
    }
    public function createToken(User $user):array
    {
      $payload=[
        'sub'=>$user->id,
        'name'=>$user->firstname,
        "exp" => time() + 4300000  // 5 minutes
    ];

      $code=new JWTCode($_ENV['SECRET_KEY']);
      $access_token=$code->encode($payload);

      $refresh_token_expiry=$refresh_token_expiry = time() + 432000;
      $refresh_token = $code->encode([
        "sub" => $user->id,
        "exp" => $refresh_token_expiry
        ]);
    
           
       $database=new Database();
       $refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);
      $refresh_token_gateway->create($refresh_token, $refresh_token_expiry);
     
       $tokens=[
         'access_token'=>$access_token,
         'refresh_token'=>$refresh_token,
       ];
       return $tokens;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function refresh_token()
    {
      if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    
        http_response_code(405);
        header("Allow: POST");
        exit;
        }
           
        $data = (array) json_decode(file_get_contents("php://input"), true);

          if ( ! array_key_exists("token", $data)) {

              http_response_code(400);
              echo json_encode(["message" => "missing token"]);
              exit;
          }
         
          $code = new JWTCode($_ENV["SECRET_KEY"]);

          try {
              $payload = $code->decode($data["token"]);
              
          } catch (Exception) {
              
              http_response_code(400);
              echo json_encode(["message" => "invalid token"]);
              exit;
          }
       
                $user_id = $payload["sub"];

                $database = new Database();
                 $refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);
                // check if token found in db
                $refresh_token=$refresh_token_gateway->getByToken($data["token"])                     ;
                if($refresh_token===false)
                {
                  http_response_code(400);
                  echo json_encode(["message" => "not a valid token"]);
                  exit;
                }
                $user_gateway = new UserGateway($database);

                $user = $user_gateway->getByID($user_id);
                //echo json_encode(["message" => $data["token"]]);

                if ($user === false) {
                    
                  // http_response_code(401);
                    echo json_encode(["message" => "invalid authentication"]);
                    exit;
                }
                //===========tokens========

                $payload = [
                  "sub" => $user["id"],
                  "name" => $user["firstname"],
                  "exp" => time() + 432000
                ];

                $access_token = $code->encode($payload);

                $refresh_token_expiry = time() + 432000;

                $refresh_token = $code->encode([
                  "sub" => $user["id"],
                  "exp" => $refresh_token_expiry
                ]);

                echo json_encode([
                  "access_token" => $access_token,
                  "refresh_token" => $refresh_token
                ]);
                //===================
      
        $refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);

        $refresh_token_gateway->delete($data["token"]);

        $refresh_token_gateway->create($refresh_token, $refresh_token_expiry);
 
    }
    public function log_out()
    {
      if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    
        http_response_code(405);
        header("Allow: POST");
        exit;
        }
           
        $data = (array) json_decode(file_get_contents("php://input"), true);

        if ( ! array_key_exists("token", $data)) {

            http_response_code(400);
            echo json_encode(["message" => "missing token"]);
            exit;
        }

        $code = new JWTCode($_ENV["SECRET_KEY"]);

        try {
            $payload = $code->decode($data["token"]);
            
        } catch (Exception) {
            
            http_response_code(400);
            echo json_encode(["message" => "invalid token"]);
            exit;
        }

        $user_id = $payload["sub"];

        $database = new Database();
        $refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);
        $refresh_token=$refresh_token_gateway->getByToken($data["token"])                     ;
        if($refresh_token===false)
        {
          http_response_code(400);
          echo json_encode(["message" => "not a valid token"]);
          exit;
        }
        $user_gateway = new UserGateway($database);

        $user = $user_gateway->getByID($user_id);
        //echo json_encode(["message" => $data["token"]]);

        if ($user === false) {
            
          // http_response_code(401);
            echo json_encode(["message" => "invalid authentication"]);
            exit;
        }
        //===================


              
            
        // delete old token 
        $refresh_token_gateway->delete($data["token"]);

            //  var_dump($user_id);
        /**/

    }
    public function deleteExpired()
    {
      $database=new Database();
     $refreshTokenGateway= new RefreshTokenGateway($database,$_ENV['SECRET_KEY']);
     //$refreshTokenGateway->deleteExpired();
    }

    public   function register(Request $request)
    {
        
        $user=new User();
        $errors=[];
       if($request->isPost())
       {
           
           
           $user->loadData($request->getBody());
           if($user->validate() && $user->save())
           {
           
               //  add user to data base 
                return 'success';
           }
      
         
           
          
       }
      
        
    }
    public function logout(Request $request,Response $response)
    {
       
          // echo 'logout';
           Application::$app->logout();
           $response->redirect('/');
       
    }
    public function profile(Request $request,Response $response)
    {
      /*
      $database=new Database();
      $user_gateway=new UserGateway($database);
      $jwt_code=new JWTCode($_ENV['SECRET_KEY']);
      $auth=new Auth($user_gateway,$jwt_code);
      */
      $response=new Response();
      $response->setSuccess(true);
      $response->setHttpStatusCode(200);
      $response->addMessage('pass you can get data');
      
      $response->send();
      
      
    }
   
    

     
    
}