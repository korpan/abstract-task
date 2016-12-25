<?php

namespace Engine\Validators;

use Engine\Models\Model;


class Validator {

    
    public function validate(Model $model){
        $rules = $model->rules();
        
        if(empty($rules)){
            return true;
        }
        
        foreach ($rules as $rule) {
            $method = 'validate'.ucfirst($rule[0]);
            $modelAttribute = $rule[1];
            
            $allowEmpty = !isset($rule[2]) || $rule[2];

            if(method_exists($this, $method)){
                $value = $model->$modelAttribute;
                
                if((!$allowEmpty && empty($value)) || !$this->$method($value)){
                    $model->addError($modelAttribute);
                }
            }
        }
        
        return empty($model->getErrors());
    }
    
    public function validateEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) 
            && preg_match('/@.+\./', $email);
    }
    public function validateString($val){
        return filter_var($val);
    }
    
}
