<?php

class NewsAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        $this->_inject_check(0); // 调用过滤函数
        $this->_checkUser();
        header("Content-Type:text/html; charset=utf-8");
    }

    // 二级密码验证
    public function cody()
    {
        $UrlID = (int) $_GET['c_id'];
        if (empty($UrlID)) {
            $this->error('二级密码错误!');
            exit();
        }
        if (! empty($_SESSION['user_pwd2'])) {
            $url = __URL__ . "/codys/Urlsz/$UrlID";
            $this->_boxx($url);
            exit();
        }
        $fck = M('cody');
        $list = $fck->where("c_id=$UrlID")->getField('c_id');
        if (! empty($list)) {
            $this->assign('vo', $list);
            $this->display('Public:cody');
            exit();
        } else {
            $this->error('二级密码错误!');
            exit();
        }
    }

    // 二级验证后调转页面
    public function codys()
    {
        $Urlsz = $_POST['Urlsz'];
        if (empty($_SESSION['user_pwd2'])) {
            $pass = $_POST['oldpassword'];
            $fck = M('fck');
            if (! $fck->autoCheckToken($_POST)) {
                $this->error('页面过期请刷新页面!');
                exit();
            }
            if (empty($pass)) {
                $this->error('二级密码错误!');
                exit();
            }
            
            $where = array();
            $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
            $where['passopen'] = md5($pass);
            $list = $fck->where($where)
                ->field('id')
                ->find();
            if ($list == false) {
                $this->error('二级密码错误!');
                exit();
            }
            $_SESSION['user_pwd2'] = 1;
        } else {
            $Urlsz = $_GET['Urlsz'];
        }
        switch ($Urlsz) {
            case 1:
                $_SESSION['Urlszpass'] = 'Myssadminnews';
                $bUrl = __URL__ . '/adminnews';
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                break;
        }
    }

    public function adminnewsManage()
    {
        $this->assign('url', U('News/adminnews'));
        $this->display('Public/admincontainer');
    }

    public function form_detail()
    {
        $is_mobile = $_POST['is_mobile'];
        $user_id = $_POST['user_id'];
        $id = $_POST['id'];
        $rs = M('form')->where(array(
            'id' => $id
        ))
            ->field('*,FROM_UNIXTIME(create_time, "%Y-%m-%d %H:%i:%S") as time_str')
            ->find();
        $count = M('form_read')->where('user_id=' . $user_id . '  and form_id=' . $id)->count();
        IF ($count > 0) {
            M('form_read')->where('user_id=' . $user_id . '  and form_id=' . $id)->setField('is_read', 1);
            M('form_read')->where('user_id=' . $user_id . '  and form_id=' . $id)->setField('read_time', time());
        }
        
        if ($is_mobile == 1) {
            
            $rs['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $rs['img'];
            $read = M('form_read')->where('user_id=' . $user_id . ' AND form_id=' . $id . ' and is_read=1')->count();
            
            $rs['is_read'] = $read;
            $count = M('form_read')->where('form_id=' . $id)->count();
            $rs['read_count'] = $count;
            $rs['content'] = htmlspecialchars_decode( $rs['content']);
//             $rs['content'] = str_replace('<p>', '<p style="line-height:0px">', $rs['content']);
            $rs['content'] = str_replace('<img', '<img style="width:100%" ', $rs['content']);
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $rs;
            $this->ajaxReturn($data);
        }
    }

    // 新闻管理首页
    public function adminnews()
    {
        $user_id = I('post.user_id', 1);
        $page_index = I('post.page_index', 1);
        $page_num = I('post.page_num', 10);
        $notice_type = I('post.notice_type', 0);
        
        IF ($notice_type == 0) {
            $user_id = 0;
        }
        $form = M('form');
        $map = 'user_id=' . $user_id . ' and type=' . $notice_type;
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $is_mobile = $_POST['is_mobile'];
        
        if ($is_mobile == 1) {
            $fee = M('fee');
            $fee_rs = $fee->field('project_week')->find();
            
            $week_time = strtotime('-' . $fee_rs['project_week'] . ' week');
            
            $map = $map . ' and create_time>' . $week_time;
        }
        
        $count = $form->where($map)->count(); // 总页数
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        if ($is_mobile == 1) {
            $p = $page_index;
        }
        $list = $form->where($map)
            ->field($field)
            ->order('sort_id desc,create_time desc,id desc')
            ->page($p . ',' . $page_num)
            ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["create_time"]);
            
            $user_id = I('post.user_id');
            init_read($user_id, $value['id']);
            
            $read = M('form_read')->where('user_id=' . $user_id . ' AND form_id=' . $value['id'] . ' and is_read=1')->count();
            
            $list[$key]['is_read'] = $read;
            
            $list[$key]['is_read_str'] = '未读';
            if ($read > 0) {
                
                $list[$key]['is_read_str'] = '已读';
            }
        }
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            $category_id = I('category_id', 0);
            $category_item = M('article_category')->where(array(
                'id' => $category_id
            ))->find();
            if ($category_item != NULL) {
                $cate_slider = $category_item['slide'];
                $cate_slider = explode(",", $cate_slider);
            }
            
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['cate_slider'] = $cate_slider;
            // $data['user_list'] = $user_list;
            $data['current_count'] = count($list);
            // $data['no_read_notice_count'] = $userInfo['notice_no_read_count'];
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function adminactivity()
    {
        $user_id = $_POST['user_id'];
        
        $form = M('form');
        $map = 'user_id=0 and type=1';
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $is_mobile = $_POST['is_mobile'];
        
        if ($is_mobile == 1) {
            $fee = M('fee');
            $fee_rs = $fee->field('project_week')->find();
            
            $week_time = strtotime('-' . $fee_rs['project_week'] . ' week');
            
            $map = $map . ' and create_time>' . $week_time;
        }
        
        $count = $form->where($map)->count(); // 总页数
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->where($map)
            ->field($field)
            ->order('sort_id desc,create_time desc,id desc')
            ->page($p . ',10')
            ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["create_time"]);
        }
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            
            $map = 'user_id= ' . $user_id;
            $fee = M('fee');
            $fee_rs = $fee->field('project_week')->find();
            
            $week_time = strtotime('-' . $fee_rs['project_week'] . ' week');
            
            $map = $map . ' and create_time>' . $week_time;
            $user_list = $form->where($map)
                ->field($field)
                ->order('baile desc,create_time desc,id desc')
                ->select();
            foreach ($user_list as $key1 => $value1) {
                
                $user_list[$key1]['addtime_str'] = date("Y-m-d H:i:s", $value1["create_time"]);
            }
            
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['user_list'] = $user_list;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function adminnewslist()
    {
        $page_index = I('page_index', 1);
        $page_num = I('page_num', 10);
        $is_mobile = $_POST['is_mobile'];
        $user_id = $_POST['user_id'];
        $type = $_GET['type'];
        $this->assign('type', $type);
        if ($is_mobile == 1) {
            
            $type = $_POST['type'];
        }
        $form = M('form');
        $map = 'user_id=0 and type=' . $type;
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        
        if ($is_mobile == 1) {}
        
        $count = $form->where($map)->count(); // 总页数
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        IF ($is_mobile == 1) {
            $p = $page_index;
        }
        
        $list = $form->where($map)
            ->field($field)
            ->order('sort_id desc,create_time desc,id desc')
            ->page($p . ','.$page_num)
            ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["create_time"]);
            $list[$key]['icon'] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $value['img'];
        }
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            
            $category_id = I('category_id', 0);
            $category_item = M('article_category')->where(array(
                'id' => $category_id
            ))->find();
            if ($category_item != NULL) {
                $cate_slider = $category_item['slide'];
                $cate_slider = explode(",", $cate_slider);
            }
            
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['current_count'] = count($list);
            $data['cate_slider'] = $cate_slider;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function adminstudyManage()
    {
        $this->assign('url', U('News/adminstudy'));
        $this->display('Public/admincontainer');
    }

    // 新闻管理首页
    public function adminstudy()
    {
        $user_id = $_POST['user_id'];
        
        $form = M('form');
        $map = 'user_id=0 and type=1';
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $order = 'id desc';
        if ($_POST['is_mobile'] == 1) {
            
            $order = 'sort_id asc';
        }
        
        $list = $form->where($map)
            ->field($field)
            ->order($order)
            ->page($p . ',10')
            ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['addtime_str'] = date("Y-m-d H:i:s", $value["create_time"]);
            $list[$key]['content'] = htmlspecialchars_decode($value["content"]);
        }
        $this->assign('list', $list); // 数据输出到模板
                                      // =================================================
        
        if ($_POST['is_mobile'] == 1) {
            
            $map = 'user_id= ' . $user_id;
            $user_list = $form->where($map)
                ->field($field)
                ->order('baile desc,create_time desc,id desc')
                ->select();
            foreach ($user_list as $key1 => $value1) {
                
                $user_list[$key1]['addtime_str'] = date("Y-m-d H:i:s", $value1["create_time"]);
            }
            
            $data = array();
            $data['msg'] = '获取成功！';
            $data['status'] = 1;
            $data['data'] = $list;
            $data['user_list'] = $user_list;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function NewsAC()
    {
        $this->_Admin_checkUser(); // 后台权限检测
                                   // 处理提交按钮
        $action = $this->_get('action');
        $id = $this->_get('id');
        
        switch ($action) {
            case 'use':
                $this->News_Open($PTid);
                break;
            case 'forbidden':
                $this->News_Stop($PTid);
                break;
            case 'del':
                $this->News_Del($id);
                break;
            case 'top':
                $this->News_Top($PTid);
                break;
            case 'canceltop':
                $this->News_NoTop($PTid);
                break;
            default:
                $this->ajaxError('系统繁忙！');
        }
    }

    public function News_add()
    {
        if (! IS_POST) {
            
            $type = $this->_get('type');
            $this->assign('type', $type);
            
            $this->display('News_add');
        } else {
            $User = M('form');
            $data = array();
            $content = $this->_post('detail_content');
            $link_url = $this->_post('link_url');
            $title = $this->_post('title');
            $type = $this->_post('type');
            $ttime = strtotime($this->_post('addtime'));
            if ($ttime == 0) {
                $ttime = mktime();
            }
            if (empty($title) or empty($content)) {
                $this->error('请输入完整的信息！');
            }
            // dump($_POST['select']);exit;
            $data['link_url'] = $link_url;
            $data['title'] = $title;
            $data['content'] = $content;
            $data['title'] = $title;
            $data['user_id'] = '管理员';
            $data['create_time'] = $ttime;
            $data['status'] = 1;
            $data['type'] = $type;
            $data['img'] = $this->_post('img');
            // $data['img'] = get_img_url( $data['img'] );
            $data['video_img'] = $this->_post('video_img');
            // $data['video_img'] = get_img_url( $data['video_img'] );
            $rs = $User->add($data);
            if (! $rs) {
                $this->error('添加失败！');
                exit();
            }
            // if ($type == 1) {
            // $this->success('添加热门活动成功', U('News/adminactivity'));
            // exit();
            // }else if ($type == 2) {
            $url = U('News/adminnewslist', array(
                'type' => $type
            ));
            $this->success('添加成功');
            exit();
            // }else {
            // $this->success('添加公告成功', U('News/adminnews'));
            // exit();
            // }
        }
    }

    // 启用
    private function News_Open($PTid = 0)
    {
        $User = M('form');
        $where['id'] = array(
            'in',
            $PTid
        );
        $User->where($where)->setField('status', 1);
        $bUrl = __URL__ . '/adminnews';
        $this->_box(1, '启用成功！', $bUrl, 1);
        exit();
    }

    // 禁用
    private function News_Stop($PTid = 0)
    {
        $User = M('form');
        $where['id'] = array(
            'in',
            $PTid
        );
        $User->where($where)->setField('status', 0);
        $bUrl = __URL__ . '/adminnews';
        $this->_box(1, '禁用成功！', $bUrl, 1);
        exit();
    }

    // 删除
    private function News_Del($PTid = 0)
    {
        $User = M('form');
        $where['id'] = $PTid;
        $rs = $User->where($where)->delete();
        if ($rs) {
            $this->ajaxSuccess('删除成功！');
            exit();
        } else {
            $this->ajaxError('删除失败');
            exit();
        }
    }

    // 置顶
    private function News_Top($PTid = 0)
    {
        $User = M('form');
        $where['id'] = array(
            'in',
            $PTid
        );
        $User->where($where)->setField('baile', 1);
        $bUrl = __URL__ . '/adminnews';
        $this->_box(1, '置顶成功！', $bUrl, 1);
        exit();
    }

    // 取消置顶
    private function News_NoTop($PTid = 0)
    {
        $User = M('form');
        $where['id'] = array(
            'in',
            $PTid
        );
        $User->where($where)->setField('baile', 0);
        $bUrl = __URL__ . '/adminnews';
        $this->_box(1, '公告取消置顶成功！', $bUrl, 1);
        exit();
    }

    // 编辑
    public function News_edit()
    {
        $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            $id = $this->_get('id');
            $rs = M('form')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
                $type = $this->_get('type');
                $this->assign('type', $type);
            $this->assign('vo', $rs);
            
            $this->display('News_edit');
        } else {
            $form = M('form');
            $data['title'] = $this->_post('title');
            $data['content'] = $this->_post('detail');
            $data['status'] = 1;
            $data['id'] = $this->_post('id');
            $data['type'] = $_POST['type'];
            $data['img'] = $this->_post('img');
            // $data['img'] = get_img_url( $data['img'] );
            $data['video_img'] = $this->_post('video_img');
            // $data['video_img'] = get_img_url( $data['video_img'] );
            $data['link_url'] = $this->_post('link_url');
            if ($data['id'] > 0) {
                
                $data['update_time'] = time();
                $rs = M('form')->save($data);
            } else {
                
                $data['create_time'] = time();
                $rs = M('form')->add($data);
            }
            
            if (! $rs) {
                $this->error('编辑失败！');
                exit();
            } else {
                $url=U('News/adminnewslist',array('type'=> $data['type'] ));
                $this->success('编辑成功！',$url);
            }
        }
    }

    // 前台新闻
    public function News()
    {
        $map = array();
        $map['status'] = 1;
        $form = M('form');
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 10);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->where($map)
            ->field($field)
            ->order('baile desc,id desc')
            ->page($p . ',10')
            ->select();
        $this->assign('list', $list); // 数据输出到模板
        $this->display();
    }

    // 查询返回一条记录
    public function News_show()
    {
        $id = $this->_get('id');
        $vo = M('Form')->where(array(
            'id' => $id,
            'status' => 1
        ))->find();
        $this->assign('vo', $vo);
        unset($vo, $id);
        $this->display();
    }

    public function UpdateField()
    {
        $fck = M('Form');
        $id = I('post.id');
        $field = I('post.field');
        $value = I('post.value');
        
        $fck->where('id=' . $id)->setField($field, $value);
        
        $this->success('修改成功！');
    }

    public function upload_fengcai_bb()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_bb();
        }
    }

    protected function _upload_fengcai_bb()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2000; // TODO 50M 3M 3292200 1M 1048576
                                           
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,mp4');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.form1.img.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_aa()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_aa();
        }
    }

    protected function _upload_fengcai_aa()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2000; // TODO 50M 3M 3292200 1M 1048576
                                           
        // 设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,mp4');
        
        // 设置附件上传目录
        $upload->savePath = './Public/Uploads/';
        
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = false;
        
        // 设置需要生成缩略图的文件前缀
        $upload->thumbPrefix = 'm_'; // 生产2张缩略图
                                     
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = '800';
        
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = '600';
        
        // 设置上传文件规则
        $upload->saveRule = date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . rand(1, 100);
        
        // 删除原图
        $upload->thumbRemoveOrigin = true;
        
        if (! $upload->upload()) {
            // 捕获上传异常
            $error_p = $upload->getErrorMsg();
            echo "<script>alert('" . $error_p . "');history.back();</script>";
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path = $uploadList[0]['savepath'];
            $U_nname = $uploadList[0]['savename'];
            $U_inpath = (str_replace('./Public/', '__PUBLIC__/', $U_path)) . $U_nname;
            
            echo "<script>window.parent.form1.video_img.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.video_img.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }
}
?>