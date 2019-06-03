<?php

class ProjectAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        
        // $this->_inject_check(0); // 调用过滤函数
        
        // $this->_checkUser();
        
        header("Content-Type:text/html; charset=utf-8");
    }

    public function updata_open_share_status()
    {
        $id = $_POST['item_id'];
        $project_users = M('project_users');
        
        $project_users->where('id=' . $id)->setField('open_share_time', time());
        $project_users->where('id=' . $id)->setField('is_open_share', 1);
        $this->ajaxSuccess('更新成功');
        exit();
    }

    public function updata_share_status()
    {
        $id = $_POST['item_id'];
        $project_users = M('project_users');
        
        $project_users->where('id=' . $id)->setField('share_time', time());
        $project_users->where('id=' . $id)->setField('is_share', 1);
        $this->ajaxSuccess('更新成功');
        exit();
    }

    public function sys_lock()
    {
        $id = $_GET['id'];
        $project = M('project');
        
        $sort_id = $project->max('sort_id');
        if ($sort_id == NULL) {
            $sort_id = 0;
        }
        $rs = $project->where('id=' . $id)->find();
        if ($rs['is_sys_lock'] > 0) {} else {
            $project->where('id=' . $id)->setField('is_sys_lock', 1);
            $project->where('id=' . $id)->setField('sys_lock_time', time());
            $this->success('冻结成功');
            exit();
        }
    }

    public function sort()
    {
        $id = $_GET['id'];
        $project = M('project');
        
        $sort_id = $project->max('sort_id');
        if ($sort_id == NULL) {
            $sort_id = 0;
        }
        $rs = $project->where('id=' . $id)->find();
        if ($rs['sort_id'] > 0) {
            
            $project->where('id=' . $id)->setField('sort_id', 0);
            $this->success('取消置顶成功');
            exit();
        } else {
            $project->where('id=' . $id)->setField('sort_id', ($sort_id + 1));
            $this->success('置顶成功');
            exit();
        }
    }

    public function complain_func()
    {
        $User = M('project_users');
        $id = $_POST['id'];
        $type = $_POST['type'];
        $where['id'] = $id;
        $rs = $User->where($where)->find();
        if ($rs['is_lock'] == 1) {
            $this->ajaxError('任务已过期,不能操作');
            exit();
        }
        
        if ($type == 'win1') {
            
            user_award($rs['project_id'], $rs['user_id']);
            $User->where($where)->setField('check_complain_time', time());
            $User->where($where)->setField('win1', 1);
            $User->where($where)->setField('status', 0);
            $form = M('form');
            $data['title'] = '消息';
            $data['content'] = '尊敬的全民微客用户您好，您的ID为' . $rs['project_id'] . '的任务申诉成功，佣金已经发送到您账户，请查收。';
            $data['create_time'] = time();
            $data['status'] = 1;
            $data['user_id'] = $rs['user_id'];
            M('form')->add($data);
        }
        if ($type == 'win2') {
            
            fanhuan_money($rs['project_id']);
            $User->where($where)->setField('check_complain_time', time());
            $User->where($where)->setField('win2', 1);
            
            $form = M('form');
            $data['title'] = '消息';
            $data['content'] = '尊敬的全民微客用户您好，您的ID为' . $rs['project_id'] . '的任务申诉失败，如有疑问可以咨询平台客户，祝您生活愉快。';
            $data['create_time'] = time();
            $data['status'] = 1;
            $data['user_id'] = $rs['user_id'];
            M('form')->add($data);
        }
        
        if ($rs) {
            $this->ajaxSuccess('审核成功');
            exit();
        } else {
            $this->ajaxError('操作失败');
            exit();
        }
    }

    public function complain_project()
    {
        $id = $_POST['item_id'];
        $user_id = $_POST['user_id'];
        $User = M('project_users');
        $where['id'] = $id;
        
        $rs = $User->where($where)->find();
        if ($rs['status'] == 0) {
            $this->ajaxError('任务已完成,不能申诉');
            exit();
        }
        $complain_time = M('fee')->where(array(
            'id' => 1
        ))->getField('complain_time');
        
        $end = strtotime('+' . $complain_time . ' hours', $rs['check_time']);
        if ($end < time()) {
            $this->ajaxError('申述时间已过,不能申诉');
            exit();
        }
        
        $User->where($where)->setField('complain_time', time());
        $User->where($where)->setField('is_complain', 1);
        
        if ($rs) {
            $this->ajaxSuccess('申诉成功,请等待审核！');
            exit();
        } else {
            $this->ajaxError('申诉失败');
            exit();
        }
    }

    public function complain_list()
    {
        $is_mobile = $_POST['is_mobile'];
        $type = $_POST['type'];
        $user_id = $_POST['user_id'];
        
        $map = array();
        
        $map['is_complain'] = 1;
        $map['is_sys_lock'] = 0;
        $map['status'] = 2;
        $map['win1'] = 0;
        $map['win2'] = 0;
        $form = M('project_users');
        
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
            ->order('  id desc')
            ->page($p . ',10')
            ->select();
        
        foreach ($list as $i => $value) {
            $project = M('project')->where("id='" . $value["project_id"] . "'")->find();
            project_check($value['project_id']);
            $publish_user = M('fck')->where("id='" . $value["user_id"] . "'")->find();
            $list[$i]["title"] = $project['title'];
            $list[$i]["content"] = $project['remark'];
            $list[$i]["name"] = $publish_user['user_name'];
            $category = M('article_category')->where(array(
                'id' => $project['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['category_subtitle'] = $category['sub_title'];
            $list[$i]['category_icon'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['img_url']);
            
            $project_albums = M('project_albums')->where(array(
                'project_id' => $value["project_id"]
            ))->select();
            foreach ($project_albums as $ii => $item) {
                $project_albums[$ii]['original_path'] = '__ROOT__/' . '' . $project_albums[$ii]['original_path'];
            }
            $list[$i]['project_albums'] = $project_albums;
            $list[$i]['project_albums1'] = $project_albums[0]['original_path'];
            $list[$i]['project_albums2'] = $project_albums[1]['original_path'];
            
            $project_user_albums = M('project_user_albums')->where(array(
                'project_id' => $value["project_id"],
                'user_id' => $value['user_id']
            ))->select();
            
            foreach ($project_user_albums as $iii => $item1) {
                $project_user_albums[$iii]['original_path'] = '__ROOT__/' . '' . $project_user_albums[$iii]['original_path'];
            }
            
            $list[$i]['project_user_albums'] = $project_user_albums;
            $list[$i]['project_user_albums1'] = $project_user_albums[0]['original_path'];
            $list[$i]['project_user_albums2'] = $project_user_albums[1]['original_path'];
            
            $list[$i]['is_end'] = 0;
            if ($list[$i]["end_time"] < time()) {
                $list[$i]['is_end'] = 1;
            }
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function category_list()
    {
        $is_mobile = I('post.is_mobile');
        $parent_id = I('post.parent_id', 0);
        $channel_id = 1;
        $map = array();
        $map['channel_id'] = $channel_id;
        
        if ($is_mobile == 1) {}
        
        $form = M('article_category');
        
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, 1000);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        $list = $form->where($map)
            ->field($field)
            ->order('  sort_id asc,id desc')
            ->page($p . ',1000')
            ->select();
        
        $newData = array();
        GetChilds($list, $newData, $parent_id, $channel_id);
        if ($is_mobile == 1) {
            foreach ($list as $i => $goods) {
                $list[$i]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img_url']);
            }
        }
        $this->assign('list', $newData); // 数据输出到模板
        foreach ($list as $key => $item) {
            
            $list[$key]['label'] = $item['title'];
            $list[$key]['value'] = $item['title'];
            $list[$key]['id'] = $item['id'];
            
            $child = $form->where('parent_id=' . $item['id'])
                ->order('sort_id asc')
                ->select();
            foreach ($child as $i => $goods) {
                $child[$i]['img_url'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $child[$i]['img_url']);
            }
            $list[$key]['child'] = $child;
        }
        $slide = array();
        $slide[0]['path'] = 'Public/Images/slides/dnzq.png';
        $slide[1]['path'] = 'Public/Images/slides/qmwk.png';
        // $slide[2]['path'] = 'Public/Images/slides/s9.png';
        if ($is_mobile == 1) {
            $data = array();
            $data['slide'] = $slide;
            $data['data'] = $list;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function category_edit()
    {
        $channel_id = 1;
        $is_mobile = $_POST['is_mobile'];
        
        // $this->_Admin_checkUser(); // 后台权限检测
        if (! IS_POST) {
            
            $id = $this->_get('id');
            $action = $this->_get('action');
            
            $rs = M('article_category')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            
            $dt = M('article_category')->where(array(
                'channel_id' => 0
            ))->select();
            $parent_id = 0;
            $newData = array();
            GetChilds($dt, $newData, $parent_id, $channel_id);
            $category = array();
            $item = array();
            $item['id'] = 0;
            $item['title'] = '无父级分类';
            $category[] = $item;
            foreach ($newData as $dr) {
                $Id = $dr["id"];
                $ClassLayer = $dr["class_layer"];
                $Title = $dr["title"];
                if ($ClassLayer == 1) {
                    $category[] = $dr;
                } else {
                    $Title = "├ " . $Title;
                    $Title = StringOfChar($ClassLayer - 1, "　") . $Title;
                    $dr["title"] = $Title;
                    $category[] = $dr;
                }
            }
            
            $this->assign('category', $category);
            if ($rs) {
                $this->assign('vo', $rs);
                unset($id, $rs);
            }
            $this->display('category_edit');
        } else {
            
            $form = M('article_category');
            $data['title'] = $this->_post('title');
            
            $data['channel_id'] = $channel_id;
            $data['gap_price'] = $this->_post('gap_price');
            $data['parent_id'] = $this->_post('ddlParentId');
            $data['sort_id'] = $this->_post('sort_id');
            $data['img_url'] = $this->_post('img');
            $data['content'] = $_POST['detail'];
            $data['price'] = $_POST['price'];
            $data['user_price'] = $_POST['user_price'];
            $data['sub_title'] = $_POST['sub_title'];
            $data['id'] = $_POST['id'];
            $data['is_lock'] = 0;
            if ($data['id'] > 0) {
                $ContainNode = M('article_category')->where("class_list like '%," . $data['id'] . ",%' and id=" . $data['parent_id'])->find();
                if ($ContainNode != NULL) {
                    // 查找旧数据
                    $oldModel = M('article_category')->where(" id=" . $data['id'])->find();
                    // 查找旧父节点数据
                    $class_list = "," . $data['parent_id'] . ",";
                    $class_layer = 1;
                    if ($oldModel['parent_id'] > 0) {
                        $oldParentModel = M('article_category')->where(" id=" . $oldModel['parent_id'])->find();
                        $class_list = $oldParentModel['class_list'] . $data['parent_id'] . ",";
                        $class_layer = $oldParentModel['class_layer'] + 1;
                    }
                    // $data['parent_id'] = $oldModel['parent_id'];
                    $data['class_list'] = $class_list;
                    $data['class_layer'] = $class_layer;
                    $rs = M('article_category')->where('id=' . $data['parent_id'] . ' ')->setField('parent_id', $oldModel['parent_id']);
                    $rs = M('article_category')->where('id=' . $data['parent_id'] . ' ')->setField('class_list', $class_list);
                    $rs = M('article_category')->where('id=' . $data['parent_id'] . ' ')->setField('class_layer', $class_layer);
                    UpdateChilds($data['parent_id']); // 带事务
                }
                if ($data['parent_id'] > 0) {
                    $model2 = M('article_category')->where('id=' . $data['parent_id'])->find();
                    $data['class_list'] = $model2['class_list'] . $data['id'] . ",";
                    $data['class_layer'] = $model2['class_layer'] + 1;
                } else {
                    $data['class_list'] = "," . $data['id'] . ",";
                    
                    $data['class_layer'] = 1;
                }
                
                $rs = M('article_category')->where('id=' . $data['id'])->save($data);
                UpdateChilds($data['id']);
            } else {
                
                $data['addtime'] = time();
                $rs = M('article_category')->add($data);
                
                if ($data['parent_id'] > 0) {
                    $model2 = M('article_category')->where('id=' . $data['parent_id'])->find();
                    $data['class_list'] = $model2['class_list'] . $rs . ",";
                    $data['class_layer'] = $model2['class_layer'] + 1;
                } else {
                    $data['class_list'] = "," . $rs . ",";
                    
                    $data['class_layer'] = 1;
                }
                $data['id'] = $rs;
                $rs = M('article_category')->where('id=' . $rs)->save($data);
            }
            if (! $rs) {
                $this->error('编辑失败！');
                exit();
            } else {
                unset($form, $data);
                $url = __URL__ . '/category_list';
                $this->ajaxSuccess('编辑成功！', $url);
            }
        }
    }

    public function category_del()
    {
        $form = M('article_category');
        $channel_id = 1;
        $PTid = $this->_get('id');
        $where['id'] = $PTid;
        $rs = $form->where($where)->find();
        $list = $form->where('id>0')->select();
        
        $parent_id = $PTid;
        $newData = array();
        $newData[] = $rs;
        GetChilds($list, $newData, $parent_id, $channel_id);
        foreach ($newData as $i => $goods) {
            $form->where('id=' . $goods['id'])->delete();
        }
        
        $this->ajaxSuccess('删除成功');
    }

    public function projectDel()
    {
        $id = $this->_get('id');
        $User = M('project');
        $where['id'] = $id;
        $rs = $User->where($where)->delete();
        if ($rs) {
            $this->ajaxSuccess('删除成功！');
            exit();
        } else {
            $this->ajaxError('删除失败');
            exit();
        }
    }

    public function upload_imgs()
    {
        $id = $_POST['project_id'];
        $item_id = $_POST['item_id'];
        $user_id = $_POST['user_id'];
        $original_path = $_POST['original_path'];
        
        $original_paths = explode(",", $original_path);
        
        $remark = $_POST['remark'];
        $User = M('project');
        $where['id'] = $id;
        $User = M('project');
        
        $rs = $User->where($where)->find();
        $data = array();
        $data['project_id'] = $id;
        $data['item_id'] = $item_id;
        $data['user_id'] = $user_id;
        $data['add_time'] = time();
        $User = M('project_user_albums');
        
        foreach ($original_paths as $key => $value) {
            if (! EMPTY($value)) {
                $data['original_path'] = $value;
                $rs = $User->add($data);
            }
        }
        M('project_users')->where('id=' . $item_id)->setField('is_read', 0);
        M('project_users')->where('id=' . $item_id)->setField('is_upload', 1);
        M('project_users')->where('id=' . $item_id)->setField('upload_time', time());
        if ($rs) {
            $this->ajaxSuccess('上传成功！');
            exit();
        } else {
            $this->ajaxError('上传失败');
            exit();
        }
    }

    public function pause_project()
    {
        $id = $_POST['project_id'];
        
        $user_id = $_POST['user_id'];
        $status = $_POST['is_pause'];
        
        $project = M('project');
        $where['id'] = $id;
        
        $rs = $project->where($where)->find();
        if ($rs == null) {
            $this->error('任务不存在');
            exit();
        }
        if ($rs['is_pause'] == $status) {
            $this->error('操作错误');
            exit();
        }
        $project->where($where)->setField('pause_time', time());
        $project->where($where)->setField('is_pause', $status);
        
        get_project_info($id, $rs, $user_id);
        
        if ($status == 0) {
            
            $this->ajaxSuccess('继续成功！');
            exit();
        } else {
            
            
            
            $this->ajaxSuccess('暂停成功！');
            exit();
        }
    }
    
    // 审核投手的做任务
    public function check_user_join()
    {
        $id = $_POST['user_join_id'];
        
        $user_id = $_POST['user_id'];
        $status = $_POST['status'];
        
        $project_users = M('project_users');
        $where['id'] = $id;
        
        $rs = $project_users->where($where)->find();
        if ($rs == null) {
            
            $this->error('任务不存在');
            exit();
        }
        if ($rs['status'] == 0) {
            
            $this->error('任务已通过,不能重复判断');
            exit();
        }
        $project_users->where($where)->setField('check_time', time());
        $project_users->where($where)->setField('status', $status);
        
        // $user = M('fck')->where('id=' . $rs['user_id'])->find();
        // $project = M('project')->where('id=' . $rs['project_id'])->find();
        // $user_price = $project['user_price'];
        if ($status == 0) {
            
            user_award($rs['project_id'], $rs['user_id']);
            check_project_complete($rs['project_id'], 'check');
            $this->ajaxSuccess('判断正确,操作成功！');
            exit();
        } else {
            $project_users->where($where)->setField('is_read', 0);
            
            $this->ajaxSuccess('判断错误,操作成功！');
            exit();
        }
    }

    public function project_join()
    {
        $id = $_POST['project_id'];
        $User = M('project');
        $where['id'] = $id;
        $rs = $User->where($where)->find();
        $user_id = $_POST['user_id'];
        if ($rs['is_sys_lock'] == 1) {
            $this->error('任务已冻结,不能接单');
            exit();
        }
        if ($rs['user_id'] == $user_id) {
            $this->error('自己的任务,不能参与');
            exit();
        }
        $model = M('fck')->field('is_lock')
            ->where('id=' . $user_id)
            ->find();
        if ($model['is_lock'] == 1) {
            $this->error(C('LOCK_MSG'));
            exit();
        }
        $rs = M('project')->where(array(
            'id' => $id
        ))
            ->field('*')
            ->find();
        
        $day_project_count = M('project_users')->where("user_id=" . $user_id . " AND category_id=" . $rs['category_id'] . " AND FROM_UNIXTIME(add_time,'%Y-%m-%d')= FROM_UNIXTIME(unix_timestamp(now()),'%Y-%m-%d')  ")->count();
        $day_max_num = M('article_category')->where(array(
            'id' => $rs['category_id']
        ))->getField('day_max_num');
        // $this->error($user_id.'发布次数' . $day_project_count . '$'. $data['category_id'].'$' . $day_max_num);
        if ($day_max_num > - 1) {
            $category_title = M('article_category')->where(array(
                'id' => $rs['category_id']
            ))->getField('title');
            if ($day_max_num <= $day_project_count) {
                $this->error($category_title . '一天只能接' . $day_max_num . '次');
                exit();
            }
        }
        // $tree = project_tree($id);
        // $project_ids = '0';
        // foreach ($tree as $vo) {
        // $project_ids = $project_ids . ',' . $vo['id'];
        // }
        // $project_ids=$project_ids.'0';
        $project_user_count = M('project_users')->where('project_id in (' . $rs['id'] . ',' . $rs['pid'] . ') and user_id=' . $user_id)->count();
        if ($project_user_count > 0) {
            $this->error('您已参与,不能重复参与');
            exit();
        }
        
        $project_user_count = M('project_users')->where('category_id=' . $rs['category_id'] . '  and user_id=' . $user_id . ' AND project_link_url   like "%' . $rs['link_url'] . '%"')->count();
        if ($project_user_count > 0) {
            $this->error('您已参与同一链接,不能重复参与');
            exit();
        }
        // 接了别的任务,之前的任务自动终止
        $project_user_count = M('project_users')->where(' status=1 and  user_id=' . $user_id . ' AND    not EXISTS ( SELECT * FROM xt_project_user_albums WHERE item_id=xt_project_users.id ) ')->find();
        if ($project_user_count != null) {
            M('project_users')->where('id=' . $project_user_count['id'] . ' ')->setField('status', 4);
            M('project_users')->where('id=' . $project_user_count['id'] . ' ')->setField('is_lock', 1);
            M('project_users')->where('id=' . $project_user_count['id'] . ' ')->setField('lock_time', time());
        }
        
        if ($rs['is_pause'] == 1) {
            $this->error('任务已暂停,不能参与');
            exit();
        }
        
        if ($rs['limit_num'] > 0) {
            $now = time();
            $timediff = timediff($rs['add_time'], $now);
            
            $begin_str = date('Y-m-d H:i:s', strtotime('+' . $timediff['min'] . ' minute', $rs['add_time']));
            $begin_time = strtotime($begin_str);
            
            $end_str = date('Y-m-d H:i:s', strtotime('+60 second', $begin_time));
            $end_time = strtotime($end_str);
            $project_user_count = M('project_users')->where('project_id=' . $rs['id'] . '   AND (add_time >=' . $begin_time . ' AND add_time<' . $end_time . ')')->count();
            
            if ($project_user_count >= $rs['limit_num']) {
                $this->error('投手数量已达上限,请在下一分钟参与');
                exit();
            }
        }
        
        // 总数
        $user_count = 0;
        // 待审核数量
        $check_user_count = 0;
        // 进行中
        $being_user_count = 0;
        // 接了但是没做
        $join_user_count = 0;
        // 已审核完成
        $complete_user_count = 0;
        // 申述中
        $complain_user_count = 0;
        // 不合格
        $error_user_count = 0;
        // 已上传未审核
        $upload_user_count = 0;
        if ($rs['num'] > 0) {
            project_count($id, $user_count, $check_user_count, $join_user_count, $complete_user_count, $complain_user_count, $error_user_count, $being_user_count, $upload_user_count);
            if (($check_user_count + $join_user_count + $complete_user_count) >= $rs['num']) {
                $this->error('任务已被抢光');
                exit();
            }
            
            $project_user_count = M('project_users')->where('project_id=' . $id . ' AND  (status=0 OR status=1   ) ')->count();
            $rs['project_user_count'] = $project_user_count;
            $rs['remain'] = $rs['num'] - $project_user_count;
            if ($rs['remain'] == 0) {
                $this->error('任务已完成');
                exit();
            }
        }
        
        $data = array();
        $data['project_id'] = $id;
        $data['user_id'] = $user_id;
        $data['status'] = 1;
        $data['add_time'] = time();
        $data['money'] = $rs['user_price'];
        $data['project_link_url'] = $rs['link_url'];
        $data['category_id'] = $rs['category_id'];
        $User = M('project_users');
        $rs = $User->add($data);
        
        if ($rs) {
            $this->ajaxSuccess('领取成功！');
            exit();
        } else {
            $this->error('领取失败');
            exit();
        }
    }

    public function get_user_join_list()
    {
        $is_mobile = $_POST['is_mobile'];
        $id = $_POST['project_id'];
        $user_id = $_POST['user_id'];
        $type = $_POST['type'];
        $User = M('project');
        $status = ' 1=1 ';
        $status = 'status!=0 AND status!=2';
        if ($type == 'history') {
            $status = ' status=0 ';
        }
        $project_user = M('project_users')->where($status . '  AND  project_id=' . $id . ' AND EXISTS ( SELECT * FROM xt_project_user_albums WHERE item_id=xt_project_users.id ) ')->select();
        
        foreach ($project_user as $i => $value) {
            $project_user_images = M('project_user_albums')->where('item_id=' . $value['id'])->select();
            
            foreach ($project_user_images as $key => $goods) {
                $project_user_images[$key]['original_path'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $project_user_images[$key]['original_path']);
            }
            $project_user[$i]['user_images'] = $project_user_images;
            
            $project_user[$i]["check_time_str"] = date("Y-m-d H:i", $project_user[$i]["check_time"]);
            $project_user[$i]["add_time_str"] = date("Y-m-d H:i", $project_user[$i]["add_time"]);
            $publish_user = M('fck')->where("id='" . $value["user_id"] . "'")->find();
            $project_user[$i]['user_name'] = $publish_user['user_id'];
        }
        $project = M('project')->where('     id=' . $id)->find();
        $project = get_project_info($id, $project, $user_id);
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $project_user;
            $data['project'] = $project;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        }
    }

    public function my_join_project()
    {
        $form = M('project_users');
        $order_str = 'id desc';
        $is_mobile = $_POST['is_mobile'];
        $user_id = $_POST['user_id'];
        $type = $_POST['type'];
        $page_index =$this->_post('page_index', true, 1);
        $page_num = $this->_post('page_num', true, 10);
        $map = array();
        $map['user_id'] = $user_id;
        
        // 获取审核中数量
        $map = get_my_join_project_map($map, $user_id, 2);
        $map['is_read'] = 0;
        $check_count = $form->where($map)->count();
        $map = get_my_join_project_map($map, $user_id, 3);
        $map['is_read'] = 0;
        $error_count = $form->where($map)->count();
        
        $map = array();
        $map['user_id'] = $user_id;
        
        // 接收了任务未完成
        if ($type == 1) {
            $map['status'] = 1;
            $join_ids = '';
            $join_id = M('project_user_albums')->where('user_id=' . $user_id)
                ->field('item_id')
                ->select();
            IF (COUNT($join_id) > 0) {
                FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                    
                    $join_ids = $join_ids . $join_id[$i]['item_id'] . ',';
                }
                $join_ids = $join_ids . '0';
                $map['id'] = array(
                    'not in',
                    $join_ids
                );
            }
            $order_str = '  add_time desc  ';
        }
        // 任务已完成
        if ($type == 4) {
            $map['status'] = 0;
            $order_str = '  check_time desc  ';
        }
        // 做完了任务在审核中
        if ($type == 2) {
            $map = get_my_join_project_map($map, $user_id, $type);
        }
        // 做完了任务被打回
        if ($type == 3) {
            $map = get_my_join_project_map($map, $user_id, $type);
            // $map['status'] = 2;
            // $join_ids = '';
            // $join_id = M('project_user_albums')->where('user_id=' . $user_id)
            // ->field('item_id')
            // ->select();
            // IF (COUNT($join_id) > 0) {
            // FOR ($i = 0; $i < COUNT($join_id); $i ++) {
            
            // $join_ids = $join_ids . $join_id[$i]['item_id'] . ',';
            // }
            // $join_ids = $join_ids . '0';
            // $map['id'] = array(
            // 'in',
            // $join_ids
            // );
            // }
            $order_str = '  complain_time desc  ';
        }

        $is_test = I('post.is_test');
        if($is_test==0)
        { 
//             $page_num = 1000;
        }
        
        $fee = M('fee');
        $fee_rs = $fee->field('project_week')->find();
        
        $week_time=strtotime('-'.$fee_rs['project_week'].' week');
        
        
        $map['add_time'] = array(
            'gt',
            $week_time
        );
        
        
        $list = $form->where($map)
            ->order($order_str)
              ->page($page_index . ', ' . $page_num)
            ->select();
        foreach ($list as $i => $value) {
            $project = M('project')->where("id=" . $value['project_id'] . "")->find();
            $list[$i]["title"] = $project['title'];
            $list[$i]["id"] = $project['id'];
            $list[$i]["user_name"] = $project['user_name'];
            $list[$i]["user_price"] = $project['user_price'];
            
            $category = M('article_category')->where(array(
                'id' => $project['category_id']
            ))->find();
            $list[$i]['category'] = $category['title'];
            $list[$i]['category_subtitle'] = $category['sub_title'];
            $list[$i]['category_seo_title'] = $category['seo_title'];
            $list[$i]['category_icon'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $category['img_url']);
            
            $list[$i]["per_money"] = $project["money"] / $project["num"];
            
            $list[$i]["status_str"] = project_status($project['id']);
            
            $project_albums = M('project_albums')->where(array(
                'project_id' => $project["id"]
            ))->select();
            $list[$i]['project_albums'] = $project_albums;
            $list[$i]['is_end'] = 0;
            if ($project["end_time"] < time()) {
                $list[$i]['is_end'] = 1;
            }
            $list[$i]["add_time_str"] = date("Y-m-d H:i", $value["add_time"]);
            
            $list[$i] = get_project_info($value['project_id'], $project, $user_id);
        }
        
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['check_count'] = $check_count;
            $data['error_count'] = $error_count;
            $data['status'] = 1;
            $this->ajaxReturn($data);
        }
    }

    public function project()
    {
        $page_index = $this->_post('page_index', true, 1);
        $page_num = $this->_post('page_num', true, 10);
        $form = M('project');
        $field = '*';
        $is_mobile = $_POST['is_mobile'];
        $type = $_POST['type'];
        
        $user_id = $_POST['user_id'];
//         $type = 1;
//         $is_mobile = 1;
//         $user_id = 10016;
        $mylist = array();
        $map = array();
        $map['status'] = 0;
        $order = ' sort_id desc,add_time DESC ';
        if ($is_mobile == 1) {
            $map['is_sys_lock'] = 0;
            $map['is_lock'] = 0;
            if ($type == 0) {
                $map['end_time'] = array(
                    'gt',
                    time()
                );
                $map['is_pause'] = array(
                    'eq',
                    0
                );
                $map['is_sys_lock'] = array(
                    'eq',
                    0
                );
                // $map['is_exit'] = array(
                // 'eq',
                // 0
                // );
                $map['is_complete'] = array(
                    'eq',
                    0
                );
                $join_id = M('project_users')->where('user_id=' . $user_id . ' AND status=1 and is_upload=0')
                    ->field('project_id')
                    ->select();
                IF (COUNT($join_id) > 0) {
                    FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                        $join_ids = $join_ids . $join_id[$i]['project_id'] . ',';
                    }
                    $join_ids = $join_ids . '0';
                    $map['id'] = array(
                        'in',
                        $join_ids
                    );
                    if ($page_index == 1) {
                        $mylist = $form->where($map)
                            ->field($field)
                            ->select();
                    }
                }
                
                $join_ids = '';
                $join_id = M('project_users')->where('user_id=' . $user_id . ' AND (STATUS=4 OR STATUS=0 or status=1)')
                    ->field('project_id')
                    ->select();
                IF (COUNT($join_id) > 0) {
                    FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                        $join_ids = $join_ids . $join_id[$i]['project_id'] . ',';
                    }
                    $join_ids = $join_ids . '0';
                    $map['id'] = array(
                        'not in',
                        $join_ids
                    );
                }
                
                // if($user_id==10016)
                // {
                $map['_string'] = '  num >(SELECT count(id) From xt_project_users S Where  S.project_id =xt_project.id AND ((S.status=0 OR S.status=1 OR S.status=2) AND  S.is_sys_lock=0 ))  ';
                // }
                
                $order = ' sort_id desc ,zd_money desc, id desc';
            }
            
            if ($type == 1) {
                $map['user_id'] = array(
                    'eq',
                    $user_id
                );
                $order = '  id desc';
                
                $keywords = $_POST['keywords'];
                
                $map['public_number'] = array(
                    'like',
                    '%' . $keywords . '%'
                );
                


                $fee = M('fee');
                $fee_rs = $fee->field('project_week')->find();
                
                $week_time=strtotime('-'.$fee_rs['project_week'].' week');
                
                
                $map['add_time'] = array(
                    'gt',
                    $week_time
                );
                
                
                
                
                
                
                
                
                
                
                
                $map['_logic'] = 'or';
                $final['_complex'] = $map;
                $final['user_name'] = array(
                    'like',
                    '%' . $keywords . '%'
                );
                $final['user_id'] = array(
                    'eq',
                    $user_id
                );
                
                $final['add_time'] = array(
                    'gt',
                    $week_time
                );
                
                
                
                $map = $final;
                // $page_num = 1000;
            }
        }
        
        $field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->where($map)->count(); // 总页数
        
        $Page = new Page($count, $page_num);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
        
        if ($is_mobile == 1) {
            $p = $page_index;
        }
        $list = $form->where($map)
            ->field($field)
            ->order($order)
            ->page($p . ', ' . $page_num)
            ->select();
        if ($is_mobile == 1) {
            if (count($mylist) > 0) {
                
                if (count($list) == 0) {
                    $list = array();
                }
                $list = array_merge($mylist, $list);
            }
            foreach ($list as $i => $goods) {
                $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
            }
        }
        
        foreach ($list as $i => $value) {
            // project_check($value['id']);
            $list[$i] = get_project_info($value['id'], $value, $user_id);
            if ($value['is_complete'] == 0) {
                check_project_complete($value['id'], '');
            }
            $user_project_status = 0;
            $count = M('project_users')->where('project_id=' . $value["id"] . ' AND user_id=' . $user_id . ' AND status=1 AND     not EXISTS ( SELECT * FROM xt_project_user_albums WHERE item_id=xt_project_users.id ) ')->count();
            if ($count > 0) {
                $user_project_status_str = 'continue';
                $list[$i]["user_project_status_str"] = $user_project_status_str;
                $list[$i]["user_project_status"] = 2;
            }
            
            $count = M('project_users')->where('project_id=' . $value["id"] . ' AND user_id=' . $user_id . ' AND status=1 AND     EXISTS ( SELECT * FROM xt_project_user_albums WHERE item_id=xt_project_users.id ) ')->count();
            if ($count > 0) {
                $user_project_status_str = 'check';
                $list[$i]["user_project_status_str"] = $user_project_status_str;
                $list[$i]["user_project_status"] = 1;
            }
        }
        if ($type == 0) {
            // $flag = array();
            // foreach ($list as $arr2) {
            // $flag[] = $arr2["user_project_status"];
            // }
            // array_multisort($flag, SORT_DESC, $list);
        }
        $this->assign('list', $list); // 数据输出到模板
        
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $data['count'] = count($list);
            $this->ajaxReturn($data);
        } else {
            
            $this->display();
        }
    }

    public function task()
    {
        $page_index = $this->_post('page_index', true, 1);
        $page_num = $this->_post('page_num', true, 10);
        $form = M('project');
        $field = 't.*,1 AS is_show,g.seo_title as  category_seo_title,g.title as  category_title,g.call_index ';
        $is_mobile = $this->_post('is_mobile', true, 1);
        $type = $_POST['type'];
    
        $user_id = $_POST['user_id'];
//         $type = 0;
//         $is_mobile = 1;
//           $user_id = 10020;
        $mylist = array();
        $map = array();
        $map=' status=0 ';
        $order = ' sort_id desc,add_time DESC ';
        if ($is_mobile == 1) {
//             $map['is_sys_lock'] = 0;
//             $map['is_lock'] = 0;
            if ($type == 0) {
                
                $map=$map.' and end_time > '. time();
                 
                 $map=$map.' and is_pause =0 ';
                $map=$map.' and is_sys_lock =0';
                
                // $map['is_exit'] = array(
                // 'eq',
                // 0
                // );
                $map=$map.' and is_complete =0';
               
                $join_id = M('project_users')->where('user_id=' . $user_id . ' AND status=1 and is_upload=0')
                ->field('project_id')
                ->select();
                IF (COUNT($join_id) > 0) {
                    FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                        $join_ids = $join_ids . $join_id[$i]['project_id'] . ',';
                    }
                    $join_ids = $join_ids . '0';

                    //$map=$map.' and t.id in ('.$join_ids.')';
                    
                    if ($page_index == 1) {
                        $mylist = $form->alias('t')->join("xt_article_category AS g ON   g.id = t.category_id ", 'LEFT')->where('t.id in ('.$join_ids.')')
                        ->field($field)
                        ->select();
                    }
                }
    
                $join_ids = '';
                $join_id = M('project_users')->where('user_id=' . $user_id . ' AND (STATUS=4 OR STATUS=0  OR STATUS=1 )')
                ->field('project_id')
                ->select();
                IF (COUNT($join_id) > 0) {
                    FOR ($i = 0; $i < COUNT($join_id); $i ++) {
                        $join_ids = $join_ids . $join_id[$i]['project_id'] . ',';
                    }
                    $join_ids = $join_ids . '0';
                    $map=$map.' and t.id not in ('.$join_ids.')';
                    
                }
    
                // if($user_id==10016)
                // {
                $map=$map.' and   t.num >(SELECT count(t.id) From xt_project_users S Where  S.project_id =t.id AND ((S.status=0 OR (S.status=1 ) OR S.status=2) AND  S.is_sys_lock=0 ))  ';
                // }
    
                $order = ' sort_id desc ,zd_money desc, t.id desc';
            }
    
             
        }
        $field = 't.*,1 AS is_show,g.seo_title as  category_seo_title,g.title as  category_title,g.call_index ';
        //$field = '*';
        // =====================分页开始==============================================
        import("@.ORG.Page"); // 导入分页类
        $count = $form->alias('t')
            ->join("xt_article_category AS g ON   g.id = t.category_id ", 'LEFT')
            ->where($map)
            ->count();  // 总页数
    
        $Page = new Page($count, $page_num);
        // ===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show(); // 分页变量
        $this->assign('page', $show); // 分页变量输出到模板
        $p = $this->_get('p', true, '1');
    
        if ($is_mobile == 1) {
            $p = $page_index;
        }
        $list = $form->alias('t')
            ->join("xt_article_category AS g ON   g.id = t.category_id ", 'LEFT')
        ->field($field)->where($map)
        ->order($order)
        ->page($p . ', ' . $page_num)
        ->select();
        if ($is_mobile == 1) {
            if (count($mylist) > 0) {
    
                if (count($list) == 0) {
                    $list = array();
                }
                $list = array_merge($mylist, $list);
            }
//             foreach ($list as $i => $goods) {
//                 $list[$i]['img'] = str_replace('__PUBLIC__/', __ROOT__ . '/Public/', $list[$i]['img']);
//             }
        }
    
        foreach ($list as $i => $value) {
            // project_check($value['id']);
//             $list[$i] = get_project_info($value['id'], $value, $user_id);
//             if ($value['is_complete'] == 0) {
//                 check_project_complete($value['id'], '');
//             }
            $user_project_status = 0;
            $count = M('project_users')->where('project_id=' . $value["id"] . ' AND user_id=' . $user_id . ' AND status=1 AND     not EXISTS ( SELECT * FROM xt_project_user_albums WHERE item_id=xt_project_users.id ) ')->count();
            if ($count > 0) {
                $user_project_status_str = 'continue';
                $list[$i]["user_project_status_str"] = $user_project_status_str;
                $list[$i]["user_project_status"] = 2;
            }
    
            $count = M('project_users')->where('project_id=' . $value["id"] . ' AND user_id=' . $user_id . ' AND status=1 AND     EXISTS ( SELECT * FROM xt_project_user_albums WHERE item_id=xt_project_users.id ) ')->count();
            if ($count > 0) {
                $user_project_status_str = 'check';
                $list[$i]["user_project_status_str"] = $user_project_status_str;
                $list[$i]["user_project_status"] = 1;
            }
        }
        if ($type == 0) {
            // $flag = array();
            // foreach ($list as $arr2) {
            // $flag[] = $arr2["user_project_status"];
            // }
            // array_multisort($flag, SORT_DESC, $list);
        }
        $this->assign('list', $list); // 数据输出到模板
    
        if ($is_mobile == 1) {
            $data = array();
            $data['data'] = $list;
            $data['status'] = 1;
            $data['count'] = count($list);
            $this->ajaxReturn($data);
        } else {
    
            $this->display();
        }
    }
    public function project_detail()
    {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        
        $rs = M('project')->where(array(
            'id' => $id
        ))
            ->field('*')
            ->find();
        $rs = get_project_info($id, $rs, $user_id);
        $user_count = M('project_users')->where(array(
            'project_id' => $id,
            'user_id' => $user_id
        ))->count();
        $user_join = M('project_users')->where(array(
            'project_id' => $id,
            'user_id' => $user_id
        ))->find();
        $data = array();
        $complain_time = M('fee')->where(array(
            'id' => 1
        ))->getField('complain_time');
        if ($user_join != NULL) {
            $user_join["check_time_str"] = date("Y-m-d H:i:s", $user_join["check_time"]);
            $user_join['can_complain'] = 1;
            $end = strtotime('+' . $complain_time . ' hours', $user_join['check_time']);
            if ($end < time()) {
                $user_join['can_complain'] = 0;
            }
            
            $user_project_user_albums = M('project_user_albums')->where(array(
                'project_id' => $id,
                'user_id' => $user_id
            ))->select();
            $user_join['user_project_user_albums'] = $user_project_user_albums;
            
            // 已读
            M('project_users')->where(array(
                'project_id' => $id,
                'user_id' => $user_id
            ))->setField('is_read', 1);
        }
        
        $data['user_count'] = $user_count;
        $data['user_join'] = $user_join;
        $data['data'] = $rs;
        $data['info'] = '获取成功';
        $data['status'] = 1;
        $this->ajaxReturn($data);
        return;
    }
    
    // 编辑
    public function project_edit()
    {
        $channel_id = 1;
        if (! IS_POST) {
            
            $id = $this->_get('id');
            
            $rs = M('project')->where(array(
                'id' => $id
            ))
                ->field('*')
                ->find();
            $dt = M('article_category')->where(array(
                'channel_id' => $channel_id
            ))->select();
            
            $parent_id = 0;
            $newData = array();
            GetChilds($dt, $newData, $parent_id, $channel_id);
            $category = array();
            
            $item['id'] = 0;
            $item['title'] = '请选择类别...';
            $category[] = $item;
            foreach ($newData as $dr) {
                $Id = $dr["id"];
                $ClassLayer = $dr["class_layer"];
                $Title = $dr["title"];
                if ($ClassLayer == 1) {
                    $category[] = $dr;
                } else {
                    $Title = "├ " . $Title;
                    $Title = StringOfChar($ClassLayer - 1, "　") . $Title;
                    $dr["title"] = $Title;
                    $category[] = $dr;
                }
            }
            
            $this->assign('category', $category);
            $project_albums = M('project_albums')->where(array(
                'project_id' => $id
            ))->select();
            
            foreach ($project_albums as $i => $value) {
                $project_albums[$i]['path'] = __ROOT__ . '/' . $value['original_path'];
            }
            
            $project_user_albums = M('project_user_albums')->where(array(
                'project_id' => $id
            ))->select();
            
            foreach ($project_user_albums as $i => $value) {
                $project_user_albums[$i]['path'] = __ROOT__ . '/' . $value['original_path'];
                $user = M('fck')->where('id=' . $value['user_id'])
                    ->field('user_id')
                    ->find();
                
                $project_user_albums[$i]['user_name'] = $user['user_id'];
                
                $project_user_albums[$i]["add_time_str"] = date("Y-m-d H:i", $project_user_albums[$i]["add_time"]);
            }
            
            $rs['project_albums'] = $project_albums;
            
            $rs['project_user_albums'] = $project_user_albums;
            $rs['project_user_albums_count'] = count($project_user_albums);
            if ($rs) {
                $this->assign('vo', $rs);
                unset($id, $rs);
                $this->display('project_edit');
            } else {
                $rs['add_time'] = time();
                $rs['project_no'] = 'BD' . rand(1000000000, 2000000000);
                
                $rs["start_time"] = time();
                ;
                $rs["end_time"] = time();
                
                $this->assign('vo', $rs);
                // $this->error('没有该新闻！');
                // exit;
                if ($is_mobile == 1) {
                    $data = array();
                    $data['data'] = $rs;
                    $data['status'] = 1;
                    $this->ajaxReturn($data);
                } else {
                    
                    $this->display('project_edit');
                }
            }
        } else {
            
            $project_time = M('fee')->where(array(
                'id' => 1
            ))->getField('project_time');
            $form = M('project');
            
            $data = $_POST;
            
            $data['category_id'] = $this->_post('ddlParentId');
            
            if ($data['category_id'] == 0) {
                
                $this->error('请选择类别！');
                exit();
            }
            
            $user_price = M('article_category')->where(array(
                'id' => $data['category_id']
            ))->getField('user_price');
            
            $data["start_time"] = time();
            ;
            $data["end_time"] = strtotime('+' . $project_time . ' hours', $data["start_time"]);
            
            if ($data["end_time"] < $data["start_time"]) {
                $this->error('结束时间不可以小于开始时间！');
                exit();
            }
            
            $data["startover_time"] = strtotime('+1 day', $data["end_time"]);
            $data["over_time"] = strtotime('+' . $data['project_day'] . ' day', $data["startover_time"]);
            
            if ($data['type_id'] == 1) {
                $data["cloth"] = $data['lest_finance'];
            }
            if ($data['type_id'] == 2) {
                $data["credit"] = $data['lest_finance'];
            }
            
            $data['goods_id'] = I('post.goods_id');
            $data['profit_id'] = I('post.profit_id');
            $data['type_id'] = I('post.type_id');
            $data["pid"] = I('post.pid');
            
            $user_id = $_POST['user_id'];
            if ($data["pid"] > 0) {
                $jiapiao_time = M('fee')->where(array(
                    'id' => 1
                ))->getField('jiapiao_time');
                
                $my_project = M('project')->field('add_time')
                    ->where('user_id=' . $user_id . ' AND pid=' . $data["pid"])
                    ->order(' add_time DESC ')
                    ->find();
                if ($my_project != NULL) {
                    $end_time = strtotime('+' . $jiapiao_time . ' minute', $my_project["add_time"]);
                    
                    if ($end_time > time()) {
                        $ret = timediff(time(), $end_time);
                        $this->error('加票间隔为' . $jiapiao_time . '分钟，请稍后加票');
                        exit();
                    }
                }
            }
            
            $data["province"] = I('post.province');
            
            $data['img_url'] = I('post.cover_path');
            $data['limit_num'] = I('post.limit_num');
            
            if (EMPTY($data['profit'])) {
                // $this->error('请输入分红');
            }
            
            if (! chkNum($data['num'])) {
                $this->error('请输入正确的数量');
            }
            if (EMPTY($data['money'])) {
                $this->error('请输入单价');
            }
            if (! chkNum($data['money'])) {
                $this->error('请输入正确的单价');
            }
            if ($data['cate_money'] > $data['money']) {
                $this->error('单价不能小于' . $data['cate_money']);
            }
            
            if (EMPTY($data['title'])) {
                $this->error('请输入标题');
            }
            if (EMPTY($data['public_number'])) {
                $this->error('请输入公众号');
            }
            
            $call_index = M('article_category')->where(array(
                'id' => $data['category_id']
            ))->getField('call_index');
            if ($call_index == 'pdd') {
                if (EMPTY($data['goods_id'])) {
                    // $this->error('请选择产品类型');
                }
            }
            if (EMPTY($data['link_url'])) {
                $this->error('请输入链接');
            }
            $link_url = $data['link_url'];
            
            $link_url = str_replace("https", 'http', $link_url);
            if ($user_id == 10016) {
                
                // $this->error(2222);
                $status = httpcode($link_url);
                if ($status) {} else {
                    $error = array();
                    $error['user_id'] = $user_id;
                    $error['add_time'] = time();
                    $error['link_url'] = $link_url;
                    $error['http_code'] = $status;
                    M('error_url')->add($error);
                    $this->error('链接格式不正确');
                    return;
                }
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            }
            $status = httpcode($link_url);
            // $curl = curl_init($link_url);
            // curl_setopt($curl, CURLOPT_NOBODY, true);
            // $result = curl_exec($curl);
            if ($status) {} else {
                $error = array();
                $error['user_id'] = $user_id;
                $error['add_time'] = time();
                $error['link_url'] = $link_url;
                $error['http_code'] = $status;
                M('error_url')->add($error);
                
                $this->error('链接格式不正确');
                return;
            }
            
            if ($call_index == 'wzzf' || $call_index == 'wzdz' || $call_index == 'wzyd' || $call_index == 'mptp' || $call_index == 'wzly' || $call_index == 'wzlydz') {
                
                if (strpos($data['link_url'], 'ishangtong') !== false) {
                    
                    $this->error('此任务为拼多多活动,请前往拼多多发布任务页面');
                } else {}
                
                $pattern_url = "/^((?!mp.weixin.qq.com).)*$/is";
                if (preg_match($pattern_url, $data['link_url'])) {
                    
                    $this->error('公众号链接格式不正确');
                }
            }
            file_put_contents("pdd.txt", $data, FILE_APPEND);
            // 拼多多砍价链接检测
            if ($data['goods_id'] == 88) {
                // $pattern_url = "/^((?!cutprice).)*$/is";
                // if (preg_match($pattern_url, $data['link_url'])) {
                // $this->error('拼多多砍价链接格式不正确');
                // } else {}
            }
            if ($data['goods_id'] == 89) {
                // $pattern_url = "/^((?!luckybag).)*$/is";
                // if (preg_match($pattern_url, $data['link_url'])) {
                // $this->error('拼多多拆红包链接格式不正确');
                // } else {}
            }
            
            if (EMPTY($data['user_name'])) {
                $this->error('请输入被投人');
            }
            if (EMPTY($data['num'])) {
                $this->error('请输入数量');
            }
            
            $original_pathJsonStr = $_POST['original_path'];
            if (EMPTY($original_pathJsonStr)) {
                $this->error('请上传样图');
            }
            if ($user_id == 10016) {
                
                
                
                $this->error($original_pathJsonStr);
                
            }
            
            
            
            
            
            
            
            
            if ($data["pid"] > 0) {
                // $this->error($original_pathJsonStr);
                // return;
            }
            if (EMPTY($data['remark'])) {
                $this->error('请输入备注');
            }
            $length = strlen($data['remark']);
            if ($length < 6) {
                $this->error('备注不能小于6个字');
            }
            
            $all = $data['zd_money'] * 1 + $data['num'] * $data['money'];
            
            $fck = M('fck');
            $user = $fck->field('id,user_id,agent_use,is_lock')
                ->where(array(
                'id' => $user_id
            ))
                ->find();
            if ($user['is_lock'] == 1) {
                $this->error(C('LOCK_MSG'));
                exit();
            }
            if ($user['agent_use'] < $all) {
                $this->error('对不起,您的' . C('agent_use') . '不足,请充值');
                return;
            }
            
            $data['all_money'] = $all;
            $data['user_price'] = $user_price;
            $gap_price = M('article_category')->where(array(
                'id' => $data['category_id']
            ))->getField('gap_price');
            
            $data['real_money'] = $data['money'] - $gap_price;
            
            $data['is_pause'] = 0;
            
            $id = $data['id'];
            if ($id > 0) {
                $ret = M('Project')->where(array(
                    'id' => array(
                        'eq',
                        $data['id']
                    )
                ))->save($data);
            } else {
                // $data['user_id'] = $_SESSION[C('USER_AUTH_KEY')];
                $data['add_time'] = time();
                $data['status'] = 0;
                $ret = M('Project')->add($data);
                $id = $ret;
                
                $result = $fck->where(array(
                    'id' => $user['id']
                ))->setDec('agent_use', $all);
                $kt_cont = "发布任务";
                $fck = D('Fck');
                $fck->addencAdd($user['id'], $user['user_id'], - $all, 19, 0, 0, 0, $kt_cont . '-扣除' . C('agent_use')); // 激活积分扣除历史记录
            }
            
            M('project_albums')->where('project_id=' . $id)->delete();
            for ($i = 0; $i < count($original_pathJsonStr); $i++) {
                $goods_spec = ARRAY();
                $goods_spec['project_id'] = $id;
                $goods_spec['add_time'] = time();
                $goods_spec['original_path'] = $original_pathJsonStr[$i];
                M('project_albums')->add($goods_spec);
            }
            if ($ret) {
                $txt = '发布成功';
                if ($data["pid"] > 0) {
                    $txt = '加票成功';
                }
                
                $data = array(
                    'status' => 1,
                    'info' => $txt,
                    'msg' => $txt,
                    'url' => U('Project/project')
                );
                
                $this->ajaxReturn($data);
            } else {
                $data = array(
                    'status' => 0,
                    'info' => '修改失败！'
                );
                
                $this->ajaxReturn($data);
            }
        }
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
        $upload->maxSize = 1048576 * 2; // TODO 50M 3M 3292200 1M 1048576
                                        
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

    public function upload_fengcai_img1()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_img1();
        }
    }

    protected function _upload_fengcai_img1()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2; // TODO 50M 3M 3292200 1M 1048576
                                        
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
            
            echo "<script>window.parent.form1.img1.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img1.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_img2()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_img2();
        }
    }

    protected function _upload_fengcai_img2()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2; // TODO 50M 3M 3292200 1M 1048576
                                        
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
            
            echo "<script>window.parent.form1.img2.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img2.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }

    public function upload_fengcai_img3()
    {
        if (! empty($_FILES)) {
            // 如果有文件上传 上传附件
            $this->_upload_fengcai_img3();
        }
    }

    protected function _upload_fengcai_img3()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数
        
        // 载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        
        // 设置上传文件大小
        $upload->maxSize = 1048576 * 2; // TODO 50M 3M 3292200 1M 1048576
                                        
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
            
            echo "<script>window.parent.form1.img3.value='" . $U_inpath . "';</script>";
            echo "<script>window.parent.form1.img3.src='" . $U_inpath . "';</script>";
            
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit();
        }
    }
}
?>