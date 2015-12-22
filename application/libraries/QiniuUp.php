<?php

/**
 * Created by PhpStorm.
 * User: zhg
 * Date: 15/12/21
 * Time: 12:50
 */
function classLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/' . $path . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader');

require_once  __DIR__ . '/Qiniu/functions.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class QiniuUp
{
    private static $accessKey = QINIU_ACCESS_KEY;
    private static $secretKey = QINIU_SECRET_KEY;
    private static $bucket = QINIU_BUCKET;
    private static $auth = null;

    /**
     * QiniuUp constructor.
     */
    public function __construct()
    {
        if(empty(self::$auth)){
            self::$auth = new Auth(self::$accessKey, self::$secretKey);
        }
    }


    /**
     * 上传图片
     * @param $file
     * @return string
     * @throws Exception
     */
    public static function uploadImg($file){
        // 生成上传 Token
        $token = self::$auth->uploadToken(self::$bucket);
        // 上传到七牛后保存的文件名
        $key = 'img/'. time() . '.' .self::getExtension($file);
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        $uploadMgr->putFile($token, $key, $file);
        return QINIU_DOMAIN_NAME . $key;

    }


    /**
     * 获取文件扩展名
     * @param $file
     * @return string
     */
    public static function getExtension($file)
    {
        $suffix = mime_content_type($file);
        $name = substr(strrchr($suffix, '/'), 1);
        if($name == 'jpeg'){
            return 'jpg';
        }
        return $name;
    }





}