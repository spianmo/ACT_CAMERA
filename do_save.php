<?php
/**
 * Created by PhpStorm.
 * User: Finger
 * Date: 2019/1/7
 * Time: 20:51
 */
base64_image_content($_POST["data"]);
function base64_image_content($base64_image_content){
    //$img为传入字符串
    $img = str_replace('data:image/png;base64,', '', $base64_image_content);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);

    $imgPath="data/".time().".png";
    if(@file_exists($imgPath)){
        @unlink($imgPath);
    }@clearstatcache();
    $fp=fopen($imgPath,'w');
    fwrite($fp,$data);
    fclose($fp);
    resizeImage($imgPath,$imgPath,500,500);
    $mail_url="http://".$_SERVER['HTTP_HOST']."/demo/mails/index.php?a=1626723275@qq.com&t=请查收，在".date("Y-m-d h:i")."捕获到一张图片&c="."图片地址：".$_SERVER['HTTP_HOST']."/demo/".$imgPath."&i=".$_SERVER['HTTP_HOST']."/demo/".$imgPath;
    echo file_get_contents($mail_url);
}

function resizeImage($im, $dest, $maxwidth, $maxheight) {
    $img = getimagesize($im);
    switch ($img[2]) {
        case 1:
            $im = @imagecreatefromgif($im);
            break;
        case 2:
            $im = @imagecreatefromjpeg($im);
            break;
        case 3:
            $im = @imagecreatefrompng($im);
            break;
    }

    $pic_width = imagesx($im);
    $pic_height = imagesy($im);
    $resizewidth_tag = false;
    $resizeheight_tag = false;
    if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
        if ($maxwidth && $pic_width > $maxwidth) {
            $widthratio = $maxwidth / $pic_width;
            $resizewidth_tag = true;
        }

        if ($maxheight && $pic_height > $maxheight) {
            $heightratio = $maxheight / $pic_height;
            $resizeheight_tag = true;
        }

        if ($resizewidth_tag && $resizeheight_tag) {
            if ($widthratio < $heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }


        if ($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if ($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;
        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;

        if (function_exists("imagecopyresampled")) {
            $newim = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
        } else {
            $newim = imagecreate($newwidth, $newheight);
            imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
        }

        imagejpeg($newim, $dest);
        imagedestroy($newim);
    } else {
        imagejpeg($im, $dest);
    }
}

/**
 * 图片压缩处理
 * @param string $sFile 源图片路径
 * @param int $iWidth 自定义图片宽度
 * @param int $iHeight 自定义图片高度
 * @return string  压缩后的图片路径
 */
function getThumb($sFile,$iWidth,$iHeight){
    //图片公共路径
    $public_path = '';
    //判断该图片是否存在
    if(!file_exists($public_path.$sFile)) return $sFile;
    //判断图片格式(图片文件后缀)
    $extend = explode("." , $sFile);
    $attach_fileext = strtolower($extend[count($extend) - 1]);
    if (!in_array($attach_fileext, array('jpg','png','jpeg'))){
        return '';
    }
    //压缩图片文件名称
    $sFileNameS = str_replace(".".$attach_fileext, "_".$iWidth.'_'.$iHeight.'.'.$attach_fileext, $sFile);
    //判断是否已压缩图片，若是则返回压缩图片路径
    if(file_exists($public_path.$sFileNameS)){
        return $sFileNameS;
    }

    //生成压缩图片，并存储到原图同路径下
    resizeImage($public_path.$sFile, $public_path.$sFileNameS, $iWidth, $iHeight);
    if(!file_exists($public_path.$sFileNameS)){
        return $sFile;
    }
    return $sFileNameS;
}
