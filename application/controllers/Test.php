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
 * Test Controller Class
 * 随意测试类
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      Test
 * @author        huangang
 * @link
 */
class Test extends MY_Controller{

    public function index(){
        $this->Model_bus->get_common_model()->test();
    }
}