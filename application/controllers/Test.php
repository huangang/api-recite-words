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


    public function test_type(){
        $a = "nce";
        if(gettype($a) == "string"){
            echo 1;
        }

    }

    public function test_for(){
        $must_array = array('nickname' => 'rkmcke','mobile' => 15168412460);
        foreach($must_array as $k => $v ){
            var_dump($k);
            var_dump($v);
        }
    }



    public function rand_word(){
        $ret = $this->Model_bus->get_study_model()->rand_unskilled_word(1);
        echo $this->db->last_query();
        dump($ret);
    }

    public function today_num(){
        $num =  $this->Model_bus->get_study_model()->get_today_study_num(7);
        echo $this->db->last_query();
        dump($num);
    }

    public function get_lock_value(){
        $ret = $this->Model_bus->get_common_model()->get_lock_value('oBo4ouOkwqzZ0F3gdDoSizebrHAw');
        var_dump($ret);
    }

    public function test_root(){
        $file_handle = fopen( FCPATH."static/true.txt", "r" );
        $lines = array();
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            array_push($lines, $line);
        }
        fclose($file_handle);
        $random = rand(0, count($lines) - 1);
        $text = $lines[$random];
        echo $text;
    }

}