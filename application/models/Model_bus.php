<?php

/**
 * ReciteWords API framework
 *
 * 此版本API为V1版本API
 *
 * @package	ReciteWords
 * @author	ReciteWords Dev Team
 * @copyright	Copyright (c) 2015 - 2016, ReciteWords Co,Ltd. (http://www.huangang.net/)
 * @link	http://www.huangang.net/
 * @since	Version 1.0.0
 * @filesource
 */


/**
 * Model_bus Class
 * 调用model bus
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      Model_bus
 * @author        huangang
 * @link
 */
class Model_bus{
    private static $_base_model       = null;
    private static $_common_model     = null;
    private static $_user_model       = null;
    private static $_config_model     = null;
    private static $_study_model      = null;


    /**
     * @param string $model
     * @return $model.App
     * @throws Exception
     */
    private static function _load($model){
        $ci = & get_instance();
        if( isset($ci->{ $model . '_model'}) ){
            return  $ci->{ $model . '_model'};
        }else {
            $ci->load->model( $model . '_model');
            if( isset($ci->{ $model . '_model'})){
                return $ci->{ $model . '_model'};
            }
            else{
                $ci->load->model( $model );
                if( isset($ci->{ $model }))
                    return  $ci->{ $model };
                else{
                    throw new Exception('model is not exist');
                }
            }
        }
    }

    /**
     * @return Base_model
     */
    public static function get_base_model(){
        if( self::$_base_model == null ){
            self::$_base_model = self::_load('Base');
        }
        return self::$_base_model;
    }


    /**
     * @return Common_model
     */
    public static function get_common_model(){
        if( self::$_common_model == null ){
            self::$_common_model = self::_load('Common');
        }
        return self::$_common_model;
    }


    /**
     * @return User_model
     */
    public static function get_user_model(){
        if( self::$_user_model == null ){
            self::$_user_model = self::_load('User');
        }
        return self::$_user_model;
    }

    /**
     * @return Config_model
     */
    public static function get_config_model(){
        if( self::$_config_model == null ){
            self::$_config_model = self::_load('Config');
        }
        return self::$_config_model;
    }

    /**
     * @return Study_model
     */
    public static function get_study_model(){
        if( self::$_study_model == null ){
            self::$_study_model = self::_load('Study');
        }
        return self::$_study_model;
    }
}