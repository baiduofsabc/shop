<?php
namespace clt;
class Imgcompress{  
           private $src;  
           private $image;  
           private $imageinfo;  
           private $percent = 0.5;  
      
           /** 
            * 图片压缩 
            * @param $src 源图 
            * @param float $percent  压缩比例 
            */  
           public function __construct($src, $percent=1)  
           {  
                  $this->src = $src;  
                  $this->percent = $percent;  
           }  
      
           /** 高清压缩图片 
            * @param string $saveName  提供图片名（可不带扩展名，用源图扩展名）用于保存。或不提供文件名直接显示 
            */  
           public function compressImg($saveName='')  
           {  
                  $this->_openImage();  
                  // dump($saveName);
                  // exit;
                  if(!empty($saveName)){
                     $path= $this->_saveImage($saveName);
                      
                     return $path;
                     // dump('baocun');
                    } //保存  
                  else {
                    $this->_showImage();
                    // dump('shuchu');
                  }  
           }  
      
           /** 
            * 内部：打开图片 
            */  
           private function _openImage()  
           {  
                  list($width, $height, $type, $attr) = getimagesize($this->src);  
                  $this->imageinfo = array(  
                         'width'=>$width,  
                         'height'=>$height,  
                         'type'=>image_type_to_extension($type,false),  
                         'attr'=>$attr  
                  );  
                  $fun = "imagecreatefrom".$this->imageinfo['type'];  
                  $this->image = $fun($this->src);  
                  $this->_thumpImage();  
           }  
           /** 
            * 内部：操作图片 
            */  
           private function _thumpImage()  
           {  
                  $new_width = $this->imageinfo['width'] * $this->percent;  
                  $new_height = $this->imageinfo['height'] * $this->percent;
                  if($this->imageinfo['width']>1500){
                     $new_width = $this->imageinfo['width'] * 0.75;  
                     $new_height = $this->imageinfo['height'] * 0.75;
                  }
                  if($this->imageinfo['width']>=2000){
                     $new_width = $this->imageinfo['width'] * 0.5;  
                     $new_height = $this->imageinfo['height'] * 0.5;
                  }  
                  $image_thump = imagecreatetruecolor($new_width,$new_height);  
                  //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度  
                  imagecopyresampled($image_thump,$this->image,0,0,0,0,$new_width,$new_height,$this->imageinfo['width'],$this->imageinfo['height']);  
                  imagedestroy($this->image);  
                  $this->image = $image_thump;  
           }  
           /** 
            * 输出图片:保存图片则用saveImage() 
            */  
           private function _showImage()  
           {  
                  header('Content-Type: image/'.$this->imageinfo['type']);  
                  $funcs = "image".$this->imageinfo['type'];  
                  $funcs($this->image);  
           }  
           /** 
            * 保存图片到硬盘： 
            * @param  string $dstImgName  1、可指定字符串不带后缀的名称，使用源图扩展名 。2、直接指定目标图片名带扩展名。 
            */  
           private function _saveImage($dstImgName)  
           {  
                  if(empty($dstImgName)) return false;  
                  $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp','.gif'];   //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名  
                  $dstExt =  strrchr($dstImgName ,".");  
                  $sourseExt = strrchr($this->src ,".");  
                  // dump($sourseExt);

                  if(!empty($dstExt)) $dstExt =strtolower($dstExt);  
                  if(!empty($sourseExt)) $sourseExt =strtolower($sourseExt);  
      
                  //有指定目标名扩展名  
                  if(!empty($dstExt) && in_array($dstExt,$allowImgs)){  
                         $dstName = $dstImgName;  
                  }elseif(!empty($sourseExt) && in_array($sourseExt,$allowImgs)){  
                         $dstName = $dstImgName.$sourseExt;  
                  }else{  
                         $dstName = $dstImgName.$this->imageinfo['type'];  
                  }  
                  $funcs = "image".$this->imageinfo['type'];  
                  $type=$this->imageinfo['type'];
                  
                  $path1="./public/upload_baiduo/".date('Ymd');
                  $path="./public/upload_baiduo/".date('Ymd')."/".$dstName;
                  is_dir($path1) OR mkdir($path1, 0777, true);
                  switch($type){
                      case 'jpg':
                      case 'jpeg':
                      case 'jpe':
                        imagejpeg($this->image,$path);
                        break;
                      case 'png':
                        imagepng($this->image,$path);
                        break;
                      case 'gif':
                        imagegif($this->image,$path);
                        break;
                      case 'bmp':
                      case 'wbmp':
                        imagewbmp($this->image,$path);
                        break;
                    }

                   // $this->__destruct();

                     // unlink($this->src);

                return str_replace("./", "/", $path);


           }  


      
           /** 
            * 销毁图片 
            */  
           public function __destruct(){  

                  // dump($this->src);
                  // imagedestroy($this->image);  
           }  
    }  
    ?>