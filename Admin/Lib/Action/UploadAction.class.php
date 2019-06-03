<?php

class UploadAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        header("Content-Type:text/html; charset=utf-8");
    }

    public function Upload()
    {
        $user_id = I('post.user_id',0);
        $fp = '';
        $fs = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $ret = array(
                'strings' => $_POST,
                'error' => '0'
            );
            file_put_contents("Upload.txt", count($_FILES));
            
            foreach ($_FILES as $name => $file) {
                
                $fn = $file['name'];
                
                $ft = strrpos($fn, '.', 0);
                $fm = substr($fn, 0, $ft);
                $fe = substr($fn, $ft);
                // 文件夹日期
                $ymd = date("Ymd");
                // 图片路径地址
                $basedir = 'Public/Public/upload/' . $ymd . '/';
                $fullpath = $basedir;
                if (! is_dir($fullpath)) {
                    mkdir($fullpath, 0777, true);
                }
                $fp = $fullpath . $fn;
                $fi = 1;
                while (file_exists($fp)) {
                    $fn = $fm . '[' . $fi . ']' . $fe;
                    $fp = $fullpath . $fn;
                    $fi ++;
                }
                
                $fp = iconv("UTF-8", "gb2312", $fp);
                move_uploaded_file($file['tmp_name'], $fp);
                file_put_contents("Imgcompress.txt", $user_id);
                if($user_id>0){
                    $fck = M('fck');
                    $fck->where('id=' . $user_id)->setField('avatar', $fp);
                }
                
                import("@.ORG.Imgcompress");
                $source = $fp; // 原图片名称
                $dst_img = $fp; // 压缩后图片的名称
                $percent = 1; // 原图压缩，不缩放，但体积大大降低
                if (isImage($fp)) {
                    $image = (new Imgcompress($source, $percent))->compressImg($dst_img);
                    
                   
                    
                }
                $fs[$name] = array(
                    'name' => $fn,
                    'url' => $fp,
                    'type' => $file['type'],
                    'size' => $file['size']
                );
            }
            
            $fp = iconv("gb2312", "UTF-8", $fp);
            $data = array();
            $data['info'] = '上传成功';
            $data['msg'] = '上传成功';
            $data['url'] ='/'. $fp;
            $data['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $fp;
            $data['status'] = 1;
           
            $this->ajaxReturn($data);
            exit();
        } else {
            echo "{'error':'Unsupport GET request!'}";
        }
    }

    /* base64格式编码转换为图片并保存对应文件夹 */
    function upload_base64_image()
    {
        $base64_image_content = I('post.base64');
        $user_id = I('post.user_id',0);
        $ymd = date("Ymd");
        $path = 'Public/Public/upload/' . $ymd . '/';
        // 匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path . "/" ;
            if (! file_exists($new_file)) {
                // 检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                if($user_id>0){
                    $fck = M('fck');
                    $fck->where('id=' . $user_id)->setField('avatar', $new_file);
                }
                $data = array();
                $data['info'] = '上传成功';
                $data['msg'] = '上传成功';
                $data['url'] = $new_file;
                $data['status'] = 1;
                echo json_encode($data);
                exit();
                
                
            } else {
                $data = array();
                $data['info'] = '上传成功';
                $data['msg'] = '上传成功';
                $data['status'] = 0;
                echo json_encode($data);
                exit();
            }
        } else {
            $data = array();
            $data['info'] = '上传失败';
            $data['msg'] = '上传失败';
            $data['status'] = 0;
            echo json_encode($data);
            exit();
        }
    }
}
?>