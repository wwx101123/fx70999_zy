<?php
class UplevelAction extends CommonAction{
	
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		$this->_inject_check(0);//调用过滤函数
		$this->_Config_name();//调用参数
		$this->_checkUser();
	}
	
	public function cody(){
		//===================================二级验证
		$UrlID = (int) $_GET['c_id'];
		if (empty($UrlID)){
			$this->error('二级密码错误!');
			exit;
		}
		if(!empty($_SESSION['user_pwd2'])){
			$url = __URL__."/codys/Urlsz/$UrlID";
			$this->_boxx($url);
			exit;
		}
		$cody   =  M ('cody');
		$list	=  $cody->where("c_id=$UrlID")->field('c_id')->find();
		if ($list){
			$this->assign('vo',$list);
			$this->display('Public:cody');
			exit;
		}else{
			$this->error('二级密码错误!');
			exit;
		}
	}
	public function codys(){
		//=============================二级验证后调转页面
		$Urlsz = (int) $_POST['Urlsz'];
		if(empty($_SESSION['user_pwd2'])){
			$pass  = $_POST['oldpassword'];
			$fck   =  M ('fck');
			if (!$fck->autoCheckToken($_POST)){
				$this->error('页面过期请刷新页面!');
				exit();
			}
			if (empty($pass)){
				$this->error('二级密码错误!');
				exit();
			}
	
			$where = array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['passopen'] = md5($pass);
			$list = $fck->where($where)->field('id,is_agent')->find();
			if($list == false){
				$this->error('二级密码错误!');
				exit();
			}
			$_SESSION['user_pwd2'] = 1;
		}else{
			$Urlsz = $_GET['Urlsz'];
		}
		switch ($Urlsz){
			case 1;
			$_SESSION['Urlszpass'] = 'Myssjinji';
			$bUrl = __URL__.'/MenberJinji';//会员晋级
			$this->_boxx($bUrl);
			break;
			case 2;
			$_SESSION['Urlszpass'] = 'Myssadminjinji';
			$bUrl = __URL__.'/adminmemberJJ';
			$this->_boxx($bUrl);
			break;
			default;
			$this->error('二级密码错误!');
			exit;
		}
	}
	
	//前台会员晋级--未完成
	public function MenberJinji(){
			$fck = M('fck');
	    	$uid = $_SESSION[C('USER_AUTH_KEY')];

	    	$frs = $fck->field('user_id,re_nums,u_level')->find($uid);
			$re_num=$frs['re_nums'];
            // $fx_num=$fck->where(array('re_path'=>array('like','%,'.$frs['id'].',%'),'re_level'=>array('gt',$f['re_level']+1)))->count();

	    	$fee =M ('fee')->field('s9,s10')->find();
		
			$isUp = "";
			if($isUp<6){
				$isUp = "true";
			}
			$this->assign('isUp',$isUp);
		
			$list = M('promo')->where(array('uid' => $uid))->field("*")->order('id desc')->select();
			$this->assign('list',$list);//数据输出到模板
	    	$this->assign('frs',$frs);
	    	$this->assign('level',explode('|', $fee['s10']));
	    	$this->assign('sx1',explode('|', $fee['s9']));
			$this->display();
	}

	//前台晋级处理
	public function MenberJinjiConfirm(){
	
			$ulevel = $this->_post('ulevel');
			$uid = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $uid;
			$promo = M('promo');
			$fck = D('Fck');
			$shouru = M ('shouru');
			$fck_rs = $fck->where($where)->find();
			if($fck_rs['is_pay'] == 0){
				$this->error('您是临时会员不能申请晋级，请先开通！');
				exit;
			}
			
			$fee = M ('fee');
			$fee_rs =$fee->field('s1,s2,s9,str6')->find();
			$s1 = explode('|', $fee_rs['s1']);
			$s2 = explode('|', $fee_rs['s2']);
			$s9 = explode('|', $fee_rs['s9']);
		
			if($fck_rs['get_level']>=$ulevel){
				$this->error('升级参数不正确！');
			}

 			if($fck_rs['get_level'] >=7){
				$this->error('已经是最高级，无法再升级！');
			}
		
			$newlv = $ulevel-1;
			$oldlv  = $fck_rs['get_level']-1;
			$oldulv  = $fck_rs['u_level'] ;
		
			$m=$newlv-$oldlv;

			$newulv =$oldulv+$m;
			if($newulv>count($s9))
			{
			    $newulv=count($s9);
			}
			$new_m = $s9[$newlv];
			$old_m = $s9[$oldlv];
			$need_m = $new_m-$old_m;
		
			$new_dl = $s9[$newlv];
			$old_dl = $s9[$oldlv];
			$need_dl = $new_dl-$old_dl;
		
			$in_gp = $s1[$newlv]*$new_m/100-$s1[$oldlv]*$old_m/100;
					
			$str6 = $fee_rs['str6'] / 100;
			$money_a = $need_dl * $str6;
			$money_b = $need_dl * (1 - $str6);
			
			
			
			if ($fck_rs['agent_cash']<$money_b ){
				$this->error('您的注册积分余额不足!');
				exit;
			}
			if ($fck_rs['agent_kt']<$money_a){
			    $this->error('您的激活积分余额不足!');
			    exit;
			}
			$result = $fck->execute("UPDATE __TABLE__ set agent_cash=agent_cash-".$money_b." where `id`=".$uid);
			$result = $fck->execute("UPDATE __TABLE__ set agent_kt=agent_kt-".$money_a." where `id`=".$uid);
			
			
			
			
			

			$result = $fck->execute("UPDATE __TABLE__ set buy_point=buy_point+".$need_dl." where `id`=".$uid);
			$fck->addencAdd($uid, $fck_rs['user_id'],$need_dl, 19, 0, 0, 0,  '升级送差额购物点值');
			
			
			
			
			
			
			
			
			
			
			if($result) {
				$time=time();
				// 写入帐号数据
				$data = array();
				$data['uid']       			= $uid;
				$data['user_id']			= $fck_rs['user_id'];
				$data['money']				= $need_dl;//补差额
				$data['u_level']			= $fck_rs['get_level'];//旧的
				$data['up_level']			= $ulevel;//新的
				$data['create_time']		= time();
				$data['pdt']				= time();
				$data['danshu']				= $need_dl;
				$data['is_pay']				= 1;
				$data['user_name']			= "<font color=red>前台晋级</font>";;
				$data['u_bank_name']		= $fck_rs['bank_name'];
				$data['type']				= 0;
	            $promo->add($data);
// 	            $data = array();
// 	            $data['uid']       			= $uid;
// 	            $data['user_id']			= $fck_rs['user_id'];
// 	            $data['money']				= $money_a;//补差额
// 	            $data['u_level']			= $fck_rs['get_level'];//旧的
// 	            $data['up_level']			= $ulevel;//新的
// 	            $data['create_time']		= time();
// 	            $data['pdt']				= time();
// 	            $data['danshu']				= $money_a;
// 	            $data['is_pay']				= 1;
// 	            $data['user_name']			= "<font color=red>前台晋级【扣除激活积分】</font>";;
// 	            $data['u_bank_name']		= $fck_rs['bank_name'];
// 	            $data['type']				= 0;
// 	            $promo->add($data);
				unset($data);
				$data = array();
				$data['uid'] = $uid;
				$data['user_id'] = $fck_rs['user_id'];
				$data['in_money'] = $need_m;
				$data['in_time'] = time();
				$data['in_bz'] = "会员申请升级";
				$shouru->add($data);
				
				//统计单数
				$fck->xiangJiao($fck_rs['id'],$ulevel);
				//算出奖金
  				$fck->getusjj($fck_rs['id'],1,$need_m);
  				
				$fck->query("update __TABLE__ set is_xf=0,get_level=".$ulevel.",u_level=".$newulv.",cpzj=".$new_m.",f4=".$new_dl.",agent_gp=agent_gp+".$in_gp." where `id`=".$uid);
				// 算出注册积分奖励
				$fck_rs = $fck->find($uid);
			    $money=	$need_dl;
				$fck->add_register_award($fck_rs,'升级赠送',$money);
                 $fck->add_peisong($fck_rs['id'], $in_gp);
				$fck->getduipeng($fck_rs['id'], 1, $fck_rs['cpzj']);
				
				unset($fck,$fee,$promo,$shouru,$data);
				$this->ajaxSuccess('升级申请已提交，请耐心等待！');
			}else{
				$this->error('升级申请失败！');
				exit;
			}
	}

	public function MenberJinjishow(){
		//查看详细信息
		$promo = M('promo');
		$ID = (int) $_GET['Sid'];
		$where = array();
		$where['id'] = $ID;
		$srs = $promo->where($where)->field('user_name')->find();
		$this->assign('srs',$srs);
		unset($promo,$where,$srs);
		$this->display ('MenberJinjishow');
	}
	
	//会员晋级管理
	public function adminmemberJJ2($GPid=0){
		$this->_Admin_checkUser();
			$fck = M('fck');
			$UserID = $_REQUEST['UserID'];
			$u_sd = $_REQUEST['u_sd'];
			$uulv = (int)$_REQUEST['ulevel'];
			$ss_type = (int) $_REQUEST['type'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);

				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			if(!empty($u_sd)){
				$map['is_lock'] =1;
            }
            if(!empty($uulv)){
            	$map['u_level'] =$uulv;
            }
			$map['is_pay'] = array('egt',1);
			$renshu = $fck->where($map)->count();//总人数
            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID . '&type=' . $ss_type. '&ulevel=' . $uulv;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc,id desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
            $level = array();
			for($i=0;$i<count($HYJJ) ;$i++){
				$level[$i] = $HYJJ[$i+1];
			}
			$this->assign('level',$level);
            $this->assign('count',$renshu);
            $this->assign('list',$list);//数据输出到模板
            //=================================================
			$this->display ();
	}
	
	//后台会员晋级
	public function adminmemberJJ(){
			$where = array();
			$fck = M('fck');
	    	$uid = $_SESSION[C('USER_AUTH_KEY')];
			$frs = $fck->find($uid);
			if(!$frs){
				$this->error('数据错误!');
				exit;
			}
			$promo = M('promo');
			$field  = '*';
			$map['type'] = 0;
            $list = $promo->where($map)->field($field)->order('id desc')->select();
            $this->assign('list',$list);//数据输出到模板
			$this->display();
	}

	//后台晋级处理
	public function adminMenberJinjiConfirm(){
		$this->_Admin_checkUser();
			$id = (int)$this->_get('id');
			$action=$this->_get('action');
			switch ($action) {
				case 'confrim':
					$promo = M('promo');
					$fck=M('fck');
					$pro=$promo->where(array('id'=>$id,'type'=>0))->find();
					unset($data);
					if($pro) {
						$promo->where(array('id'=>$id,'type'=>0))->setField(array('pdt'=>time(),'is_pay'=>'1'));
						$fck->where(array('id'=>$pro['uid']))->setField('get_level',$pro['up_level']);
						unset($fck,$fee,$promo);
						$this->ajaxSuccess('确认晋级！');
					}else{
						$this->error('晋级失败！');
						exit;
					}
					break;
				case 'deny':
					$promo = M('promo');
					$fck=M('fck');
					$pro=$promo->where(array('id'=>$id,'type'=>0))->find();
					unset($data);
					if($pro) {
						$promo->where(array('id'=>$id,'type'=>0))->setField(array('pdt'=>time(),'is_pay'=>'2'));
						// $fck->where('id'=>$pro['uid'])->save(array('get_level',$pro['up_level']));
						unset($fck,$fee,$promo);
						$this->ajaxSuccess('拒绝晋级！');
					}else{
						$this->error('晋级失败！');
						exit;
					}
					break;
				default:
					$this->error('系统繁忙！');
					break;
			}
		}

}
?>