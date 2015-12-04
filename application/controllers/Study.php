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
 * Study Controller Class
 * 学习接口
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      Study
 * @author        huangang
 * @link
 */
class Study extends MY_Controller{

    /**
     * 获取学习单词
     *
     *
     * @internal param int $uid `required` 用户ID
     *
     *
     * ------
     *
     * @return json
     *
     * ```
     * 返回结果
     *  {
     *  }
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    public function get_word(){
        $uid = (int)$this->input->get_post('uid', TRUE);
        $this->check_parameter(array('uid' => $uid));
        $ret = $this->Model_bus->get_study_model()->rand_unskilled_word($uid);
        $word_info = file_get_contents(QUERY_WORD_API.$ret['word']);
        $word_info = json_decode($word_info, TRUE);
        $word['word'] = $word_info['data']['content'];//单词
        $word['pronunciation'] = $word_info['data']['pronunciation'];//音标
        $word['definition'] = $word_info['data']['definition'];//中文释义
        $word['audio'] = $word_info['data']['audio'];//发音
        $word['en_definition'] = $word_info['data']['en_definition'];
        $word['example'] = $ret['example'];//例子
        $this->output($word);
    }



    public function record(){
        $uid = (int)$this->input->post_get('uid', TRUE);
        $this->check_parameter(array('uid' => $uid));
        $status = (int)$this->input->post_get('status', TRUE);
        $word = (string)$this->input->post_get('word', TRUE);
        $this->check_parameter(array('word' => $word));
        $this->Model_bus->get_study_model()->record_study($uid,$word,$status);
        $today_num = $this->Model_bus->get_study_model()->get_today_study_num($uid);
        $study_num = $this->Model_bus->get_config_model()->get_study_num($uid);
        $this->output(array('residue' => ($study_num - $today_num)));
    }



}