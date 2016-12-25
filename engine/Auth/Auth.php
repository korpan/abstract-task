<?php

namespace Engine\Auth;

use Engine\Models\Model;

class Auth {
    
    
    public static function startSession(){
        session_start();
    }
    
    public static function hashPassword($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public static function verifyPassword($password, $hash){
        return password_verify($password, $hash);
    }
    
    /**
     * 
     * check if user is authenticated
     * if model is passed - check if authenticated identity has same model class
     * 
     * @param type $model - model to compare with current identity
     * @return bool
     */
    public static function check($model = null){
        return isset($_SESSION['identity']) &&
        ($model == null || (isset($_SESSION['identity']['modelClass']) && $_SESSION['identity']['modelClass'] == get_class($model)));
    }
    
    /**
     * 
     * @param Model $model - model with IdentityTrait
     * @param string $identifier - unique value which identifies user
     * @param string $password
     * @return boolean - whether authentication is succesfull
     */
    public static function authenticate(Model $model, $identifier, $password){
        
        $identifierField = $model->getAuthIdentifierField();

        if($identifierField){
            
            $attributes = [
                $identifierField=>$identifier,
            ];
            
            $identityModel = $model->findByAttributes($attributes);

            if($identityModel!=null && self::verifyPassword($password, $identityModel->getAuthPasswordHash())){
                $identity = [
                    'id'=>$identityModel->id,
                    'modelClass'=>get_class($identityModel),
                ];
                $_SESSION['identity'] = $identity;
                return true;
            }else{
                return false;
            }
        }
    }
}
