<?php
namespace app\app\Requests;

use app\core\Request;

class BaseRequest extends Request 
{
    public const RULE_REQUIRED  = 'required';
    public const RULE_EMAIL  = 'email';
    public const RULE_MAX  = 'max';
    public const RULE_MIN  = 'min';
    public const RULE_MATCH  = 'match';
    public const RULE_UNIQUE  = 'unique';

    

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
       
    }
    public function validate()
    {
        return 'valide';
    }
    private function addErrorForRule(string $attribute,string $rule,$params=[])
    {
        $message=$this->errorMessages()[$rule]??'';
        foreach($params as $key=>$value)
        {
            $message=str_replace("{{$key}}",$value,$message);
        }
        $this->errors[$attribute][]=$message;
        
    }
    public function addError(string $attribute,string $rule)
    {
        $message=$this->errorMessages()[$rule]??'';
        $this->errors[$attribute][]=$message;
        
    }
    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED  => 'This field is required',
            self::RULE_EMAIL  => 'This field must be a valid email',
            self::RULE_MIN  => 'The length of this field must be min {min}',
            self::RULE_MAX  => 'The length of this field must be max {max}',
            self::RULE_MATCH  => 'The field must be the same {match}',
            self::RULE_UNIQUE  => 'Record with this {field} already exist',
        ];
    }
}