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
     * @param int $uid `required` 用户ID
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


    /**
     * 记录学习单词
     *
     *
     * @param int $uid `required` 用户ID
     * @param int $status `required` 学习的状态
     * @param string $word `required` 单词
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
    public function record(){
        $uid = (int)$this->input->post_get('uid', TRUE);
        $word = (string)$this->input->post_get('word', TRUE);
        $this->check_parameter(array('word' => $word,'uid' => $uid));
        $status = (int)$this->input->post_get('status', TRUE);
        $Study_model = $this->Model_bus->get_study_model();
        $Study_model->record_study($uid,$word,$status);
        if($status == $Study_model::STATUS_UNSKILLED){
            $this->Model_bus->get_vocabulary_model()->add($uid, $word);
        }elseif($status == $Study_model::STATUS_SKILLED){
            $this->Model_bus->get_vocabulary_model()->remove($uid, $word);
        }
        $today_num = $Study_model->get_today_study_num($uid);
        $this->output(array('nowStudyNum' => (int)$today_num));//今天学习的总数
    }



    /**
     * 获取用户当天学习数量
     *
     *
     * @param int $uid `required` 用户ID
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
    public function get_study_num(){
        $uid = (int)$this->input->get_post('uid', true);
        $this->check_parameter(array('uid' => $uid));
        $num = $this->Model_bus->get_study_model()->get_today_study_num($uid);
        $this->output(array('nowStudyNum' => $num));
    }

    /**
     * 单词搜索
     *
     *
     * @param string $word `required` 单词
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
    public function search_word(){
        $word = $this->input->get_post('word', true);
        $word = trim($word);
        $data = file_get_contents(SEARCH_WORD_API . $word);
        $data = json_decode($data, true);
        if(!empty($data['mark'])){
            $example = '';
            if(!empty($data['es'])){
                foreach($data['es'] as $k => $v){
                    $example = $example . $v['sentence'] . "\n" . $v['translate'];
                }
            }
            $this->Model_bus->get_study_model()->add_word($data['word'], $data['explain'], $example);
            $this->output($data);
        }else{
            $this->output(array('msg' => '没有查询到'), ErrorCodes::logic_error);
        }
    }

    /**
     * 每日一句
     *
     *
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
    public function daily_sentence(){
        $data = file_get_contents(DAILY_SENTENCE_API);
        $data = json_decode($data, TRUE);
        $ret['sentence'] = $data['content'];
        $ret['translate'] = $data['note'];
        $this->output($ret);
    }

    /**
     * 翻译
     *
     *
     * @param string $content `required` 翻译内容
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
    public function translation(){
        $content = $this->input->get_post('content', true);
        $content = trim($content);
        $content = urlencode($content);
        $data = file_get_contents(TRANSLATION_API. $content);
        $data = json_decode($data, true);
        $this->output($data);
    }

    /**
     * 学习统计
     *
     *
     * @param int $uid `required` 用户ID
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
    public function statistics(){
        $uid = $this->input->get_post('uid', true);
        $this->check_parameter(array('uid' => $uid));
        $data = $this->Model_bus->get_study_model()->statistics($uid);
        $this->output($data);
    }
}