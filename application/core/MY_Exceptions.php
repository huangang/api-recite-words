<?php

/**
 * ReciteWords API framework
 *
 * 此版本API为V1版本API
 *
 * @package	ReciteWords
 * @author	ReciteWords Dev Team
 * @copyright	Copyright (c) 2015 - 2016, BombVote Co,Ltd. (http://www.huangang.net/)
 * @link	http://www.huangang.net/
 * @since	Version 1.0.0
 * @filesource
 */


/**
 * ReciteWords Exceptions Class
 *
 * 异常基类
 *
 * @package        ReciteWords
 * @subpackage    API
 * @category    Exceptions
 * @author        huangang
 * @link
 */


class MY_Exceptions extends CI_Exceptions {

    private $is_json = false;

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 404 Error Handler 资源未找到
     *
     * @uses	CI_Exceptions::show_error()
     *
     * @param	string	$page		Page URI
     * @param 	bool	$log_error	Whether to log the error
     * @return	void
     */
    public function show_404($page = '', $log_error = TRUE)
    {
        if (is_cli())
        {
            $heading = 'Not Found';
            $message = 'The method you requested was not found.';
        }
        else
        {
            $heading = 'Resources not found';
            $message = 'The method you requested was not found.';
        }

        // By default we log this, but allow a dev to skip it
        if ($log_error)
        {
            log_message('error', $heading.': '.$page);
        }
        echo $this->show_error($heading, $message, 'error_404', 404);
        exit(4); // EXIT_UNKNOWN_FILE
    }


    /**
     * General Error Page
     *
     * Takes an error message as input (either as a string or an array)
     * and displays it using the specified template.
     *
     * @param	string		$heading	Page heading
     * @param	string|string[]	$message	Error message
     * @param	string		$template	Template name
     * @param 	int		$status_code	(default: 500)
     *
     * @return	string	Error page output
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500){
        if( $this->is_json ){
            $ret = array(
                'result' => $status_code,
                'heading' => $heading,
                'message' => $message
            );
            return json_encode($ret);
        }else{
            $ret = parent::show_error($heading, $message, $template = 'error_general', $status_code = 500);
            return $ret;
        }

    }

    public function show_exception(Exception $exception)
    {
        if(  $this->is_json ){
            $buffer = $this->show_error('Server Error', $exception->getMessage(), 'Internal Error', $exception->getCode());
            echo $buffer;
        }else{
            parent::show_exception($exception);
        }
    }

    /**
     * Native PHP error handler
     *
     * @param	int	$severity	Error level
     * @param	string	$message	Error message
     * @param	string	$filepath	File path
     * @param	int	$line		Line number
     * @return	string	Error page output
     */
    public function show_php_error($severity, $message, $filepath, $line)
    {
        if( $this->is_json ){
            $buffer = $this->show_error("Server Error", 'Server_Error' , 'error_general' , 1);
            echo $buffer;
        }else{
            parent::show_php_error($severity, $message, $filepath, $line);
        }

    }

}