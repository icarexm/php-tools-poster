使用`Composer`安装`icarexm`的海报生成类库：

~~~
composer require icarexm/poster:dev-master

~~~
## 生成二维码
> 在生成海报之前，我们需要优先生成二维码

假设当前需要生成的二维码值为`https://www.mrye.xin`网址，我们在控制器中添加如下代码：
~~~
$qrcode = new icarexm\poster\Qrcode(ROOT_PATH);
$qrcode = $qrcode->create('https://www.mrye.xin');
//绝对路径
echo $qrcode->getPathname();
//相对路径
echo $qrcode->getSrcname();

~~~
生成如下二维码：

![](https://git.kancloud.cn/repos/yhl18/wq_frame/raw/c9bf3d6417c8076f066c57cd15ac56564833cad1/images/3f27a28bbd06a7b782c2606114c2001b.png?access-token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTA1NDEyODMsImlhdCI6MTU5MDQ5ODA4MywicmVwb3NpdG9yeSI6InlobDE4XC93cV9mcmFtZSIsInVzZXIiOnsidXNlcm5hbWUiOiJ5aGwxOCIsIm5hbWUiOiJNclllNTg2OSIsImVtYWlsIjoiNTU1ODUxOTBAcXEuY29tIiwidG9rZW4iOiI3MTUxN2RiMzc4MGQyMjA2Mjc1OTllMjM0ZTRhOGY5NiIsImF1dGhvcml6ZSI6eyJwdWxsIjp0cnVlLCJwdXNoIjp0cnVlLCJhZG1pbiI6dHJ1ZX19fQ.Rrt9AqXrbmbeHt-EvLsR-11U0lwyHgBoFFN5ehkKpaU)

## 生成海报
下面来看下海报操作类的基础方法。

控制器中添加如下的代码：

~~~

$qrcode = new icarexm\poster\Qrcode(ROOT_PATH);
$qrcodePath = $qrcode->create('https://www.mrye.xin')->getPathname();
$config = array(
            'image' => array(
                //二维码资源
                array(
                    //资源路径
                    'url'       => $qrcodePath,
                    //相当于x
                    'left'      => 904,
                    //相当于y
                    'top'       => 1816,
                    'right'     => 0,
                    'bottom'    => 0,
                    //宽度
                    'width'     => 279,
                    //高度
                    'height'    => 275,
                    //删除临时文件
                    'isUnlink'  => true,
                ),
                //用户头像
                array(
                    'url'       => 'mryelogo.jpg',
                    'left'      => 554,
                    'top'       => 1078,
                    'right'     => 0,
                    'bottom'    => 0,
                    'width'     => 197.25,
                    'height'    => 194.25,
                    'isUnlink'  => true,
                ),
            ),
            //用户昵称
            'text' => array(
                array(
                    'text'      => 'MrYe',
                    'left'      => 551,
                    'top'       => 336,
                    //字号
                    'fontSize'  => 38,
                    //字体颜色
                    'fontColor' => '#000000',
                )
            ),
        );

        try {

            //生成海报
            $poster = new icarexm\poster\Poster(ROOT_PATH);
            $poster->createPoster('haibao.jpg', $config);
            //绝对路径
            echo $poster->getPathname();
            //相对路径
            echo $poster->getSrcname();

        } catch (\Exception $exception) {

            exit('error:'.$exception->getMessage());
        }

~~~
生成后的海报效果如下：
![](https://git.kancloud.cn/repos/yhl18/wq_frame/raw/c9bf3d6417c8076f066c57cd15ac56564833cad1/images/hb.jpg?access-token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTA1NDEyODMsImlhdCI6MTU5MDQ5ODA4MywicmVwb3NpdG9yeSI6InlobDE4XC93cV9mcmFtZSIsInVzZXIiOnsidXNlcm5hbWUiOiJ5aGwxOCIsIm5hbWUiOiJNclllNTg2OSIsImVtYWlsIjoiNTU1ODUxOTBAcXEuY29tIiwidG9rZW4iOiI3MTUxN2RiMzc4MGQyMjA2Mjc1OTllMjM0ZTRhOGY5NiIsImF1dGhvcml6ZSI6eyJwdWxsIjp0cnVlLCJwdXNoIjp0cnVlLCJhZG1pbiI6dHJ1ZX19fQ.Rrt9AqXrbmbeHt-EvLsR-11U0lwyHgBoFFN5ehkKpaU)