<?php

class ManagerAction extends CommonAction
{

    function _initialize()
    {
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        
        // $this->_inject_check(0); // 调用过滤函数
        
        $this->_checkUser();
        
        header("Content-Type:text/html; charset=utf-8");
    }

    function get_navigation_list()
    {
        $map['id'] = $_SESSION[C('USER_AUTH_KEY')];
        $adminModel = M('fck')->where($map)->find(); // 获得当前登录管理员信息
        if ($adminModel == null) {
            return;
        }
        $roleModel = M('manager_role')->where('id=' . $adminModel['role_id'])->find(); // 获得管理角色信息
        if ($roleModel == null) {
            return;
        }
        $manager_role_values = M('manager_role_value')->where('role_id=' . $adminModel['role_id'])->select();
        
        $dt = M('navigation')->order('sort_id asc')->select();
        $html = '';
        get_navigation_childs($html, $dt, 0, $roleModel['role_type'], $manager_role_values);
        $this->ajaxReturn($html, 'EVAL');
    }

    function nav_list()
    {
        $list = M('navigation')->where(' nav_type="System" ')
            ->order('  sort_id asc ')
            ->select();
        
        foreach ($list as $key => $voo) {
            if ($voo['is_lock'] == 0) {
                $list[$key]['is_lock'] = '√';
            }
            if ($voo['is_lock'] == 1) {
                $list[$key]['is_lock'] = '×';
            }
            
            if ($voo['is_sys'] == 0) {
                $list[$key]['is_sys'] = '√';
            }
            if ($voo['is_sys'] == 1) {
                $list[$key]['is_sys'] = '×';
            }
            if ($voo['link_url'] == '') {
                $list[$key]['link_url'] = '';
            }
            if ($voo['link_url'] != '') {
                $list[$key]['link_url'] = '(链接：' . $voo['link_url'] . ')';
            }
            $list[$key]['class_layer'] = 1;
        }
        $newData = ARRAY();
        GetNavigationChilds($list, $newData, 0, 0);
        
        $this->assign('list', $newData);
        
        $this->display();
    }

    function nav_edit()
    {
        IF ($_POST) {
            $item = $_POST;
            $Id = I('id');
            $action_type = $item['action_type'];
            if ($item['is_lock'] == 'on') {
                $item['is_lock'] = 1;
            } else {
                $item['is_lock'] = 0;
            }
            
            $action_type = implode($action_type, ',');
            $item['action_type'] = $action_type;
            if ($Id > 0) {
                $navigation = M('navigation')->where(' id =' . $Id)->find();
                
                M('navigation')->where('id=' . $Id)->save($item);
            } else {
                M('navigation')->add($item);
            }
            
            $this->success('编辑成功!');
            exit();
        }
        $list = M('navigation')->order('parent_id asc ')->select();
        foreach ($list as $key => $voo) {
            
            $list[$key]['class_layer'] = 1;
        }
        $newData = ARRAY();
        GetNavigationChilds($list, $newData, 0, 0);
        
        $ListItem = ARRAY();
        foreach ($newData as $key => $voo) {
            
            $Id = $voo["id"];
            $parent_id = $voo["parent_id"];
            $ClassLayer = $voo["class_layer"];
            $Title = $voo["title"];
            $item = array();
            if ($ClassLayer == 0) {
                $item['title'] = $Title;
                $item['id'] = $Id;
                $item['parent_id'] = $parent_id;
                $ListItem[] = $item;
            } else {
                $Title = "├ " . $Title;
                $Title = StringOfChar($ClassLayer - 1, '&nbsp;&nbsp;') . $Title;
                $item['title'] = $Title;
                $item['id'] = $Id;
                $item['parent_id'] = $parent_id;
                $ListItem[] = $item;
            }
        }
        
        $this->assign('cate', $ListItem);
        
        $id = $_REQUEST['id'];
        $parent_id = $_REQUEST['parent_id'];
        $navigation = M('navigation');
        $res = $navigation->where('id=' . $id)->find();
        
        if ($parent_id > 0) {
            $res['parent_id'] = $parent_id;
        }
        if ($res == null) {
            $res['is_lock'] = 0;
        }
        
        $this->assign('rs', $res);
        $actionTypeArr = explode(",", $res['action_type']);
        
        $res = ActionType();
        for ($i = 0; $i < Count($res); $i ++) {
            $res[$i]['checked'] = '0';
            for ($n = 0; $n < Count($actionTypeArr); $n ++) {
                if ($actionTypeArr[$n] == $res[$i][0]) {
                    $res[$i]['checked'] = '1';
                }
            }
        }
        
        $this->assign('cblActionType', $res);
        
        $this->display();
    }

    function btnDelete_Click()
    {
        IF ($_POST) {
            
            $Id = I('ids');
            
            $navigation = M('navigation')->where(' id in(' . $Id . ')')->delete();
            
            $data = array();
            $data['msg'] = '删除数据成功';
            $data['url'] = U('Manager/nav_list');
            $data['status'] = 1;
            $this->ajaxReturn($data);
        }
    }

    function UpdateField()
    {
        $fck = M('navigation');
        $id = I('post.id');
        $field = I('post.field');
        $value = I('post.value');
        
        $fck->where('id=' . $id)->setField($field, $value);
        
        $this->success('修改成功！');
    }

    function role_list()
    {
        $list = M('manager_role')->select();
        
        foreach ($list as $key => $voo) {
            
            $list[$key]['role_type'] = GetTypeName($voo['role_type']);
        }
        
        $this->assign('list', $list);
        
        $this->display();
    }

    function role_edit()
    {
        IF ($_POST) {
            $item = $_POST;
            $Id = I('id');
            $role_name = I('role_name');
            if(EMPTY($role_name))
            {
                
                $this->error('请输入角色名称！');
                return;
            }
            $manager_role_value=$item['manager_role_value'];
            
            $manager_role_value=json_decode($manager_role_value,true);
            
            M('manager_role_value')->where('role_id='.$Id)->delete();
            foreach ($manager_role_value as $key => $voo) {
                
                M('manager_role_value')->add($voo);
            }
            
            
            
            
            
            
            
            
            
            
            
            if ($Id > 0) {
                $navigation = M('manager_role')->where(' id =' . $Id)->find();
                
                M('manager_role')->where('id=' . $Id)->save($item);
            } else {
                M('manager_role')->add($item);
            }
            
            $this->success('编辑成功!');
            exit();
        }
        $list = M('navigation')->order('parent_id asc ')->select();
        foreach ($list as $key => $voo) {
            
            $list[$key]['class_layer'] = 1;
        }
        $newData = ARRAY();
        GetNavigationChilds($list, $newData, 0, 0);
        
        $ListItem = ARRAY();
        foreach ($newData as $key => $voo) {
            
            $Id = $voo["id"];
            $parent_id = $voo["parent_id"];
            $ClassLayer = $voo["class_layer"];
            $action_type = $voo["action_type"];
            $nav_name = $voo["name"];
            
            $actionTypeArr = explode(",", $action_type);
            
            
            $Title = $voo["title"];
            $item = array();
            if ($ClassLayer == 0) {
                $item['title'] = $Title;
                $item['id'] = $Id;
                $item['parent_id'] = $parent_id;
                $item['class_layer'] = $ClassLayer;
                $item['action_type'] = $actionTypeArr;
                $item['nav_name'] = $nav_name;
                $ListItem[] = $item;
            } else {
                $Title = "├ " . $Title;
                // $Title = StringOfChar($ClassLayer - 1, '&nbsp;&nbsp;') . $Title;
                $item['title'] = $Title;
                $item['id'] = $Id;
                $item['parent_id'] = $parent_id;
                $item['class_layer'] = $ClassLayer;
                $item['action_type'] = $actionTypeArr;
                $item['nav_name'] = $nav_name;
                $ListItem[] = $item;
            }
        }
        
        
        $id = $_REQUEST['id'];
        $parent_id = $_REQUEST['parent_id'];
        $manager_role = M('manager_role');
        $res = $manager_role->where('id=' . $id)->find();
        
        
        $manager_role_value = M('manager_role_value');
        $manager_role_value_list = $manager_role_value->where('role_id=' . $id)->select();
        
        $ActionType = ActionType();
        foreach ($ListItem as $key => $voo) {
            $actionTypeArr = $voo["action_type"];
            $nav_name = $voo["nav_name"];
            
//             $actionTypeArr = explode(",", $action_type);
            
            $array = array();
            
            for ($i = 0; $i < Count($actionTypeArr); $i ++) {
              
                
                $Item = ARRAY();
                $Item['title'] = GetActionType($actionTypeArr[$i]);
                $Item['func'] = $actionTypeArr[$i];
                $Item['nav_name'] = $nav_name;
                
                
                for ($n = 0; $n < Count($manager_role_value_list); $n ++) {
                    if ($manager_role_value_list[$n]['action_type'] == $Item['func']&&$manager_role_value_list[$n]['nav_name']==$nav_name) {
                            $Item['checked'] = '1';
                       
                    }
                }
                $array[] = $Item;
            }
            
            $ListItem[$key]['action_type']=$array;
            
            
        }
        
        
        
        
        
        
        
        
        
        
        
        
        $this->assign('cate', $ListItem);
        $this->assign('rs', $res);
        $ListItem = array();
        $str = "";
        $Item = array();
        $Item['title'] = "系统用户";
        $Item['id'] = 2;
        $ListItem[] = $Item;
        $Item = array();
        $Item['title'] = "超级用户";
        $Item['id'] = 1;
        $ListItem[] = $Item;
        
        $this->assign('role_type_list', $ListItem);
        
        $this->display();
    }
}