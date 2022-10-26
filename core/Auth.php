<?php
namespace app\core;


use PDO;
use Exception;
use app\models\User;
use app\core\JWTCode;
use app\models\UserGateway;
use app\core\exception\TokenExpiredException;
use app\core\exception\InvalidSignatureException;


class Auth
{
    private int $user_id;
    public static ?int $userId;
    public static ?array $user_permissions=[];
    public function __construct(private \app\models\UserGateway $user_gateway,
                                private JWTCode $codec)
    {
    }
        
    public function authenticateAPIKey(): bool
    {
        if (empty($_SERVER["HTTP_X_API_KEY"])) {
            
            http_response_code(400);
            echo json_encode(["message" => "missing API key"]);
            return false;
        }

        $api_key = $_SERVER["HTTP_X_API_KEY"];  
        
        $user = $this->user_gateway->getByAPIKey($api_key);
        
        if ($user === false) {
            
            http_response_code(401);
            echo json_encode(["message" => "invalid API key"]);
            return false;
        }          
        
        $this->user_id = $user["id"];
        
        return true;    
    }
    
    public function getUserID(): int
    {
        return $this->user_id;
    }
    
    public function authenticateAccessToken(): bool
    {
        // echo json_encode(["message" => 'here']);
        // exit;
      // self::$userId=13;

        if(isset($_SERVER["HTTP_AUTHORIZATION"]))
        {
           
                if ( ! preg_match("/^Bearer\s+(.*)$/", $_SERVER["HTTP_AUTHORIZATION"], $matches)) {
            //   http_response_code(400);
            //   echo json_encode(["message" => "incomplete authorization header"]);
                return false;
            }
        }
        else
            return false;
        
        
        try {
            $data = $this->codec->decode($matches[1]);
            
        } catch (InvalidSignatureException) {
        
         //   http_response_code(401);
          //  echo json_encode(["message" => "invalid signature"]);
            return false;
            
        } 
        catch (\app\core\exception\TokenExpiredException) {
            
          //  http_response_code(401);
          //  echo json_encode(["message" => "token has expired"]);
            return false;
        
        }
        catch (Exception $e) {
            
           // http_response_code(400);
           // echo json_encode(["message" => $e->getMessage()]);
            return false;
        }
       
        $this->user_id = $data["sub"];
        self::$userId=$data["sub"];
        self::$user_permissions=$this->get_user_permissions($data["sub"]);
        //  echo json_encode(["message" => self::$userId]);
        // exit;
        return true;
    }
    public function get_user_permissions($user_id)
    {
        $sql = "select name from permissions where id=:id";
        $database=new Database();
        $stmt = $database->pdo->prepare($sql);
        
        $stmt->bindValue(":id", $user_id, PDO::PARAM_STR);
        
        $stmt->execute();
      
        $data = [];
                // fetch all rows
        $publishers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // display the permission name
        foreach ($publishers as $publisher) {
            $data[] = $publisher['name'];
        }
            

        return $data;
    }
}












