<?php
namespace app\models;
use app\core\Model;
use app\models\User;
use app\core\Application;

class LoginForm extends Model
{
    public string $email='';
    public string $password='';
   
    public function rules() :array
    {
        return[
            
            'email'=>[self::RULE_REQUIRED,self::RULE_EMAIL],
            'password'=>[self::RULE_REQUIRED]
            

        ];
    } 
    public function login()
    {
       // $user= User::findOne(['email'=>$this->email]);

      $user= new  User();
      $log_user=$user->findOne(['email'=>$this->email]);
     // echo json_encode(["message" => $log_user]);
        if(! $log_user)
        {
            $this->addError('email','User dosent exist with this email');
            return false;
        }
        if(!password_verify($this->password,$log_user->password))
        {
            $this->addError('password','password is incorrect');
            return false;
        }
      /* echo '<pre>';
                var_dump($user);
                echo '</pre>'; */
      // return  Application::$app->login($user);
      return  $log_user;
    }
    public function labels():array
    {
        return [
            
            'email'=>'Email',
            'password'=>'Password'
        ];
           
    }

}
