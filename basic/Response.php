<?php

class Response extends \yii\web\Response
{
    private $data;  //Будем хранить данные ответа.
    private $code;  //HTTP код ответа сервера .
    private $message;  //Сообщение сервера.

    const MSG_OK = 'ok';
    const MSG_NO_DATA = 'no data';
    const MSG_WRONG_INPUT = 'wrong input data';
    const MSG_UNKNOWN_ERROR = 'Unknown error';
    const MSG_METHOD_NOT_FOUND = 'unknown method';
    const CODE_OK = 200;
    const CODE_ERROR = 400;
    const CODE_NOT_FOUND = 404;

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : ”;
    }
    public function __isset($name) {
        return isset($this->data[$name]);
    }
    public function __unset($name) {
        if(isset($this->data[$name])){
            unset($this->data[$name]);
        }
    }

    public function setCode($code, $message){
        $this->code = $code;
        $this->message = $message;
    }

    public function send(){
        switch($this->code){
            case self::CODE_NOT_FOUND:
                header('HTTP/1.0 404 Not Found');
                                                 break;
            case self::CODE_ERROR:
                header('HTTP/1.0 400 Internal Error');
                                                 break;
            case self::CODE_OK:
                header('HTTP/1.0 200 OK');
                                                 break;
        }
        $array = array(
            'code' => $this->code,
            'message' => $this->message,
        );
        if(!empty($this->data)){
            $array = array_merge($array, array('data' => $this->data));
        }
        echo json_encode($array);
    }

}