<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 【微信API】
 *
 * 1）、小程序码生成以及海报生成
 *
 * 创建时间：2019-04-09 22:03
 * 制作人：【老弟来了网制工作室】
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class WxController extends Controller
{
    protected $appid = 'wx1d7c43947d529191'; // 小程序的appid
    protected $appsecret = '2b8ff12ec190058a9fbc1e5d676808a5'; // 小程序的sessionKey

    /*****************************************************************************
     *                      ======= 生成小程序码 ======
     ****************************************************************************/

    /*
     * 【生成小程序码】
     * 目前有3个接口可以生成小程序码，开发者可以根据自己的需要选择合适的接口。
     * 第一步：获取   access_token
     * @return array|mixed
     */
    public function getWxAccessToken(){
//        session_start();
        $appid = $this->appid;
        $appsecret = $this->appsecret;
        if(isset($_SESSION['access_token_'.$appid]) && isset($_SESSION['expire_time_'.$appid]) && $_SESSION['expire_time_'.$appid]>time()){
            return $_SESSION['access_token_'.$appid];
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $access_token = $this->makeRequest($url);
            $access_token = json_decode($access_token['result'],true);
            $_SESSION['access_token_'.$appid] = $access_token;
            $_SESSION['expire_time_'.$appid] = time()+7000;
            return $access_token;
        }
    }


    // 第二步：调用接口生成微信二维码(这里以接口B为例)
    public function getWxcode(){
        $ACCESS_TOKEN=$this->getWxAccessToken();
        $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$ACCESS_TOKEN['access_token'];
        $post_data=
            array(
                'page'=>'pages/order/home/home',
                'scene'=>'123'//34%2CS853EE4QRP
        );
        $post_data=json_encode($post_data);
        $data=$this->send_post($url,$post_data);
        $result=$this->data_uri($data,'image/png');
        return $result;
//        return '<img src='.$result.'>';
    }
    /**
     * 消息推送http
     * @param $url
     * @param $post_data
     * @return bool|string
     */
    protected function send_post( $url, $post_data ) {
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/json',
                //header 需要设置为 JSON
                'content' => $post_data,
                'timeout' => 60
                //超时时间
            )
        );
        $context = stream_context_create( $options );
        $result = file_get_contents( $url, false, $context );
        return $result;
    }

    //二进制转图片image/png
    public function data_uri($contents, $mime)
    {
        $base64   = base64_encode($contents);
        return ('data:'.$mime.';base64,'.$base64);
    }


    /*****************************************************************************
     *                      ======= 生成海报 ======
     ****************************************************************************/

    /**
     * 生成宣传海报
     * @param array $config 参数,包括图片和文字
     * @param string $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
     * @return bool|string
     */
    function createPoster($config=array(),$filename=""){
        //如果要看报什么错，可以先注释调这个header
//        if(empty($filename)) header("content-type: image/png");

        $imageDefault = array(
            'left'=>0,
            'top'=>0,
            'right'=>0,
            'bottom'=>0,
            'width'=>100,
            'height'=>100,
            'opacity'=>100
        );
        $textDefault =  array(
            'text'=>'',
            'left'=>0,
            'top'=>0,
            'fontSize'=>32,             //字号
            'fontColor'=>'255,255,255', //字体颜色
            'angle'=>0,
        );
        $background = $config['background'];//海报最底层得背景
        //背景方法
        $backgroundInfo = getimagesize($background);
        $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($background);

        $backgroundWidth = imagesx($background);    //背景宽度
        $backgroundHeight = imagesy($background);   //背景高度

        $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);

        // imageColorTransparent($imageRes, $color);    //颜色透明

        imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));

        //处理了图片
        if(!empty($config['image'])){
            foreach ($config['image'] as $key => $val) {
                $val = array_merge($imageDefault,$val);

                $info = getimagesize($val['url']);
                $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
                if($val['stream']){        //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                //建立画板 ，缩放图片至指定尺寸
                $canvas=imagecreatetruecolor($val['width'], $val['height']);
                imagefill($canvas, 0, 0, $color);
                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
                //放置图像
                imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
            }
        }

        //处理文字
        if(!empty($config['text'])){
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault,$val);
                list($R,$G,$B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
                imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
            }
        }



        //生成图片
        if(!empty($filename)){
            $res = imagejpeg ($imageRes,$filename,90); //保存到本地
            imagedestroy($imageRes);
            if(!$res) return false;
            return $filename;
        }else{
            imagejpeg ($imageRes);            //在浏览器上显示
            imagedestroy($imageRes);
        }
    }







    /******************************************************************************
     *                   ===== 公共方法 =====
     *******************************************************************************/

    /*
     * 发起http请求
     * @param string $url 访问路径
     * @param array $params 参数，该数组多于1个，表示为POST
     * @param int $expire 请求超时时间
     * @param array $extend 请求伪造包头参数
     * @param string $hostIp HOST的地址
     * @return array    返回的为一个请求状态，一个内容
     */
    public function makeRequest($url, $params = array(), $expire = 0, $extend = array(), $hostIp = '')
    {
        if (empty($url)) {
            return array('code' => '100');
        }

        $_curl = curl_init();
        $_header = array(
            'Accept-Language: zh-CN',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache'
        );
        // 方便直接访问要设置host的地址
        if (!empty($hostIp)) {
            $urlInfo = parse_url($url);
            if (empty($urlInfo['host'])) {
                $urlInfo['host'] = substr(DOMAIN, 7, -1);
                $url = "http://{$hostIp}{$url}";
            } else {
                $url = str_replace($urlInfo['host'], $hostIp, $url);
            }
            $_header[] = "Host: {$urlInfo['host']}";
        }

        // 只要第二个参数传了值之后，就是POST的
        if (!empty($params)) {
            curl_setopt($_curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($_curl, CURLOPT_POST, true);
        }

        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($_curl, CURLOPT_URL, $url);
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_USERAGENT, 'API PHP CURL');
        curl_setopt($_curl, CURLOPT_HTTPHEADER, $_header);

        if ($expire > 0) {
            curl_setopt($_curl, CURLOPT_TIMEOUT, $expire); // 处理超时时间
            curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, $expire); // 建立连接超时时间
        }

        // 额外的配置
        if (!empty($extend)) {
            curl_setopt_array($_curl, $extend);
        }

        $result['result'] = curl_exec($_curl);
        $result['code'] = curl_getinfo($_curl, CURLINFO_HTTP_CODE);
        $result['info'] = curl_getinfo($_curl);
        if ($result['result'] === false) {
            $result['result'] = curl_error($_curl);
            $result['code'] = -curl_errno($_curl);
        }

        curl_close($_curl);
        return $result;
    }


    /**
     * [将Base64图片转换为本地图片并保存]
     * @param  [Base64] $base64_image_content [要保存的Base64]
     * @param  [目录] $path [要保存的路径]
     */
    function base64_image_content($base64_image_content,$path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path.date('Ymd', time()) . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
//                return '/' . $new_file;
                return $new_file;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
