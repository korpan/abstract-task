<?php

namespace Engine\Auth\Traits;

trait IdentityTrait {

    protected $identifierField = 'login';
    protected $passwordField = 'password_hash';

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifierField() {
        return $this->identifierField;
    }

    /**
     * Get the password_hash for the user.
     *
     * @return string
     */
    public function getAuthPasswordHash() {
        return $this->{$this->passwordField};
    }

}
