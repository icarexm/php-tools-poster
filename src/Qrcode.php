<?php
// +----------------------------------------------------------------------
// | zaihukeji [ WE CAN DO IT MORE SIMPLE]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2020 http://icarexm.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: MrYe    <email：55585190@qq.com>
// +----------------------------------------------------------------------

namespace icarexm\poster;

use icarexm\helper\Str;
use Exception;

class Qrcode
{

    /**
     * 文件完整路径
     * @var string
     */
    protected $pathName;

    /**
     * 上传根路径
     * @var string
     */
     protected $rootPath;

    /**
     * 二维码容错级别
     * @var string
     */
    protected $level = "L";

    /**
     * 二维码大小
     * @var int
     */
    protected $size = 6;

    /**
     * 二维码边距
     * @var int
     */
    protected $margin = 1;

    /**
     * 是否直接输出二维码
     * @var bool
     */
    protected $saveandprint = false;


    /**
     * 初始化
     * Qrcode constructor.
     * @param string $rootPath
     * @param string $sdkPath
     * @throws Exception
     */
    public function __construct($rootPath = '', $sdkPath = '')
    {

        $this->setSdkPath($sdkPath);

        $this->setRootPath($rootPath);

    }

    /**
     * 设置根路径
     * @param $rootPath
     * @return $this
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = Str::endsWith($rootPath, '/') ? $rootPath : $rootPath.'/';

        return $this;
    }

    /**
     * 设置skd路径
     * @param $sdkPath
     * @return $this
     */
    public function setSdkPath($sdkPath)
    {
        if($sdkPath) {
            //加载自定义sdk
            if(!is_file($sdkPath)) {
                //sdk文件不存在，抛出异常

                throw new Exception('qrcode sdk file does not exist:'.$sdkPath);
            }

            include $sdkPath;

        } else {
            //加载自带二维码类库
            include __DIR__.'/tool/phpqrcode.php';

        }

        if(!class_exists('QRcode')) {
            //类不存在，抛出异常

            throw new Exception('QRcode class does not exist');
        }

        return $this;
    }

    /**
     * 设置二维码生成的路径
     * @param null $file
     * @return $this
     */
    public function setOutfile($file = null)
    {
        if($file !== null) {
            $this->outfile = $file;
        }

        return $this;
    }

    /**
     * 设置二维码的容错级别
     * @param null $level
     * @return $this
     */
    public function setLevel($level = null)
    {
        if($level !== null) {
            $this->level = $level;
        }

        return $this;
    }

    /**
     * 设置二维码的大小
     * @param int $size
     * @return $this
     */
    public function setSize($size = 0)
    {
        if($size !== 0) {
            $this->size = $size;
        }

        return $this;
    }

    /**
     * 设置二维码的边距
     * @param int $margin
     * @return $this
     */
    public function setMargin($margin = 0)
    {
        if($margin !== 0) {
            $this->margin = $margin;
        }

        return $this;
    }

    /**
     * 是否直接输出二维码
     * @param boolean $saveandprint
     * @return $this
     */
    public function saveandprint($saveandprint = false)
    {
        $this->saveandprint = $saveandprint;
        header("Content-Type:image/png");

        return $this;
    }

    /**
     * 生成二维码
     * @param $value
     * @param string $fileName
     * @return $this
     * @throws Exception
     */
    public function create($value, $fileName = '')
    {
        if(empty($value)) {
            //二维码值不能为空

            throw new Exception('The value of generating QR code cannot be empty');
        }

        if(empty($fileName)) {
            //自动获取上传文件名称
            $fileName = md5($value).'.png';

        } elseif(strpos($fileName, '.') === false) {
            //没有后缀，需要拼接
            $fileName = $fileName.'.png';
        }

        $pathReplace = $this->getPathReplace($value);
        $this->pathName = $this->rootPath.$fileName;
        //替换
        $this->pathName = str_replace(array_keys($pathReplace), array_values($pathReplace), $this->pathName);
        @mkdir(dirname($this->pathName), 0777, true);

       \QRcode::png($value, $this->pathName, $this->level, $this->size, $this->margin, $this->saveandprint);
       if($this->saveandprint == true) {
           //直接输出图片，并截断
           die();
       }

       if(!is_file($this->pathName)) {
           //生成二维码失败时，说明没有权限
           throw new Exception('qrcode Generation failure!');
       }

       return $this;
    }

    /**
     * 获取文件名称
     * @return string
     */
    public function getPathname()
    {
        return $this->pathName;
    }

    /**
     * 获取文件名称
     * @return string
     */
    public function getFilename()
    {

        $pathArr = pathinfo($this->pathName);

        return isset($pathArr['basename']) ? $pathArr['basename'] : $this->pathName;
    }

    /**
     * 获取访问路径
     * @param string|null $rootPath
     * @return string
     */
    public function getSrcname($rootPath = null)
    {

        $rootPath = !empty($rootPath) ? $rootPath : $this->rootPath;
        if(empty($rootPath)) {
            //未存在根目录
            return $this->pathName;
        }

        //解析path
        $pathArr = explode($this->rootPath, $this->pathName);
        list(, $srcName) = $pathArr;

        return '/'.$srcName;
    }

    /**
     * 获取目录替换
     * @param string $value
     * @return array
     */
    protected function getPathReplace($value)
    {

        return [
            '{type}'        => 'qrcodes',
            '{time}'        => time(),
            '{md5_time}'    => md5(time()),
            '{date}'        => date('Y-m-d', time()),
            '{md5}'         => md5($value),
        ];
    }

}


