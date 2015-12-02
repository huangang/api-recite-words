<?php

class ErrorCode {

    const success = 200;

    const db_error = 300;

    const param_error = 400 ;

    const response_error = 401;

    const service_error = 402;

    const not_found = 404;

    const logic_error = 501;

    const API_ERROR =  -1;

    const LOGIN_REQUIRED = 0;

    const UNREGISTER_USER = 2;

    public function __construct(){

    }
}
