<?php

namespace Engine\Http;

class Http {
    
    
    /**
     * Redirects the browser to the specified URL.
     * @param string $url URL to be redirected to. Note that when URL is not
     * absolute (not starting with "/") it will be relative to current request URL.
     * @param boolean $terminate whether to terminate the current application
     * @param integer $statusCode the HTTP status code. Defaults to 302. 
     */
    public function redirect($url, $terminate = true, $statusCode = 302) {
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $url = $this->getHost() . $url;
        }
        header('Location: ' . $url, true, $statusCode);
        if ($terminate){
            die;
        }
    }

    public function getHost() {
        $host = null;
        if ($secure = $this->getIsSecureConnection()) {
            $http = 'https';
        } else {
            $http = 'http';
        }
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $http . '://' . $_SERVER['HTTP_HOST'];
        } 

        return $host;
    }

    /**
     * Return if the request is sent via secure channel (https).
     * @return boolean if the request is sent via secure channel (https)
     */
    public function getIsSecureConnection() {
        return isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1) || 
                isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
    }
    
    
    /**
     * send JSON.
     *
     * @param $data
     * @param $code
     */
    public function sendJSON($data = [], $code = 200) {
        $statusHeader = 'HTTP/1.1 ' . $code;
        header($statusHeader);
        header('Content-type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die;
    }

}
