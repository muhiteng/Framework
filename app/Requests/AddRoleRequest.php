<?php
namespace app\app\Requests;

use app\core\Request;
use app\app\Requests\BaseRequest;

class AddRoleRequest extends BaseRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'=>['required'],
        ];
    }
}