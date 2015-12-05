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
 * Base Model Class
 * 基础model不写任何方法
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      Base
 * @author        huangang
 * @link
 */
class Base_model extends MY_Model{

    //构造方法
    public function __construct(){
        parent::__construct();
    }

}