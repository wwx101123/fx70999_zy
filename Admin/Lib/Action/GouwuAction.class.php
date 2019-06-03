<?php
class GouwuAction extends CommonAction{

    function _initialize() {
		// $this->_inject_check(0); //调用过滤函数
		// $this->_checkUser();
		// $this->check_us_gq();
		header("Content-Type:text/html; charset=utf-8");
		$ctype=M('cptype')->limit(10)->select();
		$this->assign('type',$ctype);
		unset($ctype);
		
		$imglist = M('indeximg')->where('is_index=0')->order('id asc')->select();
		$this->assign('imglist',$imglist);//数据输出到模板
		unset($imglist);
	}

	//商城首页
	public function shop()
	{
		$condition['yc_cp'] = array('eq',0);
		$i5=M('fee')->where(array('id'=>1))->getField('i5');
		if ($i5==0) {
			$condition['is_reg'] = array('eq',0);
		}
		$list = M('product')->where($condition)->order('id asc')->page('1,12')->select();
		$this->assign('list',$list);//数据输出到模板
		$this->display();
	}
	public function shopAll()
	{
		$type=$this->_get('type');
		import("@.ORG.Page");// 导入分页类
		$count      = M('product')->where(array('cptype'=>$type,'yc_cp'=>0))->count();
		$Page       = new Page($count,24);
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		
		$list=M('product')->where(array('cptype'=>$type,'yc_cp'=>0))->page($this->_get('p',true,1).',24')->select();
		$this->assign('list',$list);
		$other=M('product')->where(array('yc_cp'=>0))->order('rand()')->limit(5)->select();
		$this->assign('other',$other);
		
		if($type==1){
			$tyname = "精品区";
		}else{
			$tyname = "消费区";
		}
		$this->assign('tyname',$tyname);
		$this->display();

	}
	
	public function shopGoods()
	{
		$fck = M('fck');
		$product = M ('product');
		$PID = (int) $this->_get('id');
		if (empty($PID)){
			$this->error('错误!');
			exit;
		}
		$map['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$f_rs = $fck->where($map)->find();

		$where = array();
		$where['id'] = $PID;
		$where['yc_cp'] = array('eq',0);
		$pro = $product->where($where)->field('*')->find();
		if ($pro){
			$this->assign('pro',$pro);
			$this->display();
		}

	}
	//显示产品信息
    public function Cpcontent() {
		$fck = M('fck');
		$product = M ('product');
		$PID = (int) $this->_get('id');
		if (empty($PID)){
			$this->error('错误!');
			exit;
		}
		$map['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$f_rs = $fck->where($map)->find();

		$where = array();
		$where['id'] = $PID;
		$where['yc_cp'] = array('eq',0);
		$pro = $product->where($where)->field('*')->find();
		if ($pro){
			$this->assign('pro',$pro);
			$this->display('Cpcontent');
		}
	}
	/**
	 * 会员进入产品订购的页面
	 */
    public function Buycp() { //购买产品页
		$product = M('product');

		$ss_type = (int) $this->_get('tp');
		if($ss_type>0){
			$condition['cptype'] = array('eq',$ss_type);
		}
		$this->assign('tp',$ss_type);
		$condition['yc_cp'] = array('eq',0);
		$i5=M('fee')->where(array('id'=>1))->getField('i5');
		if ($i5==0) {
			$condition['is_reg'] = array('eq',0);
		}
		$cptype = M('cptype');
		$tplist = $cptype->where('status=0')->order('id asc')->select();
		$this->assign('tplist',$tplist);
        //=====================分页开始==============================================
		import("@.ORG.Page");// 导入分页类
		$count      = $product->where($condition)->count();
		$Page       = new Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		
		$Page->setConfig('header','个商品');
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		
		$p=$this->_get('p',true,'1');
        $list = $product->where($condition)->order('id asc')->page($p.',12')->select();

		$this->assign('list',$list);//数据输出到模板
		$this->display('Buycp');
	}
	/**
	 * 购物车的计算
	 * @return [type] [description]
	 */
	public function shopCar(){
		$pora = M('product');
		$fck = M('fck');

		$map['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$f_rs = $fck->where($map)->find();
		$agent_cf = $f_rs['agent_cf'];

		$id = $_REQUEST['id'];

		$arr = $_SESSION["shopping"];
		// if(empty($arr)){
		// 	$url = __URL__.'/Buycp';
		// 	$this->_box(0,'您的购物车里没有商品！',$url,1);
		// 	exit;
		// }
		$rs = explode('|',$arr);
		$path = ',';
		foreach ($rs as $vo){
			$str = explode(',',$vo);
			$path .= $str[0].',';
			$ids[$str[0]] = $str[1];
		}

		$where['id'] = array('in','0'. $path .'0');
		$list = $pora -> where($where) ->select();
		foreach ($list as $lvo){
			$w_money = $lvo['money'];
			//物品总价

			$ep[$lvo['id']] = $ids[$lvo['id']] * $w_money;
			$num[$lvo['id']] = $ids[$lvo['id']];

			//所有商品总价
			$eps += $ids[$lvo['id']] * $w_money;
			$sum += $ids[$lvo['id']];

			$cc[$lvo['id']] = $w_money;
		}

		$bza = $_SESSION["shopping_bz"];
		$blrs = explode("|",$bza);
		$bzz = array();
		foreach($blrs as $vvv){
			$vava = explode(",",$vvv);
			$bzz[$vava[0]] = $vava[1];
		}
		$this->assign('agent_cf',$agent_cf);
		$this->assign('bzz',$bzz);
		$this->assign('cc',$cc);
		$this->assign('list',$list);
		$this->assign('path',$path);
		$this->assign('ids',$ids);
		$this->assign('num',$num);
		$this->assign('sum',$sum);
		$this->assign('eps',$eps);
		$this->assign('ep',$ep);
		
		$str25=M('fee')->where(array('id'=>1))->getField('str25');
		$this->assign('str25',$str25);
		
		$this->display('shopCar');

	}
	/**
	 * 删除购物车商品
	 * @return [type] [description]
	 */
	public function delBuyList(){
		$ID = $_REQUEST['id'];
		$shopping_id ='';
		$arr = $_SESSION["shopping"];

		$rs = explode('|',$arr);
		$path = ',';
		foreach ($rs as $key=>$vo){
			$str = explode(',',$vo);
			if($str[0] == $ID){
				unset($rs[$key]);
			}else{
				if(empty($shopping_id)){
					$shopping_id = $vo;
				}else{
					$shopping_id .= '|'.$vo;
				}
			}
		}
		$_SESSION["shopping"] = $shopping_id;
		$this->success("删除成功！");
	}
	public function reset(){
		//清空购物车
		$_SESSION["shopping"] = array();
		$_SESSION["shopping_bz"] = array();
		$url = __URL__.'/Buycp';
		$this->success("清空完成！");
	}
	/**
	 * 购物车里的商品数量的添加和修改
	 * @return [type] [description]
	 */
	public function chang(){
		$ID = $this->_get('DID');
		$nums = $this->_get('nums');
		$arr = $_SESSION["shopping"];
		$rs = explode('|',$arr);
		$shopping_id = '';
		foreach ($rs as $key=>$vo){
			$str = explode(',',$vo);
			if($str[0] == $ID){
				$str[1] = $nums;
			}
			$s_id = $str[0].','.$str[1];
			if(empty($shopping_id)){
				$shopping_id = $s_id;
			}else{
				$shopping_id .= '|'.$s_id;
			}
		}
		$_SESSION["shopping"] = $shopping_id;
		$this->ajaxSuccess('操作成功！');
	}

	public function chang_bz(){
		$ID = $_GET['DID'];
		$nums = trim($_GET['bzz']);

		if (!empty($nums)){
			import ( "@.ORG.KuoZhan" );  //导入扩展类
            $KuoZhan = new KuoZhan();
            if ($KuoZhan->is_utf8($nums) == false){
                $nums = iconv('GB2312','UTF-8',$nums);
            }
            unset($KuoZhan);
		}
		if(empty($_SESSION["shopping_bz"])){
			$_SESSION["shopping_bz"] = $ID.",".$nums;
		}
		$arr = $_SESSION["shopping_bz"];

		$rs = explode('|',$arr);
		$shopping_id = '';
		$tong = 0;
		foreach ($rs as $key=>$vo){
			$str = explode(',',$vo);
			if($str[0] == $ID){
				$tong = 1;
				$str[1] = $nums;
			}
			$s_id = $str[0].','.$str[1];
			if(empty($shopping_id)){
				$shopping_id = $s_id;
			}else{
				$shopping_id .= '|'.$s_id;
			}
		}
		if($tong==0){
			$shopping_id .= "|".$ID.",".$nums;
		}
		$_SESSION["shopping_bz"] = $shopping_id;
	}
	/**
	 * 进入到付款页面
	 */
	public function ShoppingListAdd(){
		$address = M('address');

		$id = $_SESSION[C('USER_AUTH_KEY')];
		$aList = $address->where('uid='.$id)->select();
		$this->assign('aList',$aList);

		$fck = M('fck');
		$fck_rs = $fck->where('id='.$id)->find();
		$this->assign('fck_rs',$fck_rs);

		$pora = M('product');
		$arr = $_SESSION["shopping"];
		$rs = explode('|',$arr);
		$path = ',';
		foreach ($rs as $vo){
			$str = explode(',',$vo);
			$ids[$str[0]] = $str[1];
			$path .= $str[0].',';
		}

		$where['id'] = array('in','0'. $path .'0');
		$list = $pora -> where($where) ->select();
		foreach ($list as $lvo){
			$w_money = $lvo['money'];
			//物品总价
			$ep[$lvo['id']] = $ids[$lvo['id']] * $w_money;

			//所有商品总价
			$eps += $ids[$lvo['id']] * $w_money;
			$sum += $ids[$lvo['id']];

			$cc[$lvo['id']] = $w_money;
		}
		$bza = $_SESSION["shopping_bz"];
		$blrs = explode("|",$bza);
		$bzz = array();
		foreach($blrs as $vvv){
			$vava = explode(",",$vvv);
			$bzz[$vava[0]] = $vava[1];
		}
		$this->assign('bzz',$bzz);

		$this->assign('cc',$cc);

		$this->assign('list',$list);
		$this->assign('path',$path);
		$this->assign('ids',$ids);
		$this->assign('sum',$sum);
		$this->assign('eps',$eps);
		$this->assign('ep',$ep);
		// var_dump($list);
		// var_dump($path);
		// var_dump($ids);
		// var_dump($sum);
		// var_dump($eps);
		// var_dump($ep);
		// $this -> display('ShoppingListAdd');
		$this->ShopingSave();
	}

	/**
	 * 保存订单
	 */
	public function ShopingSave(){
		$Id = (int) $_SESSION[C('USER_AUTH_KEY')];
		$pora = M('product');
		$address = M('address');
		$fck = D('Fck');

		$prices = $_POST['prices'];
		$arr = $_SESSION["shopping"];
		if(empty($arr)){
			$this->error("您的购物车里面没有商品！");
			exit;
		}
		$rs = explode('|',$arr);
		foreach ($rs as $vo){
			$str = explode(',',$vo);
			$p_rs = $pora->where('id='.$str[0].'')->find();
			if(!$p_rs){
				$this->error("您所购买的产品暂时没货！");
				exit;
			}
		}
		$rs = explode('|',$arr);
		$path = ',';
		foreach ($rs as $vo){
			$str = explode(',',$vo);
			$path .= $str[0].',';
			$ids[$str[0]] = $str[1];
		}

		$fck_rs = $fck->where('id='.$Id) ->find();

		$gwd = array();
		$gwd['uid'] = $Id;
		$gwd['user_id'] = $fck_rs['user_id'];
		$gwd['ispay'] = 0;
		$gwd['pdt'] = mktime();
		$gwd['us_name'] = $fck_rs['nickname'];;
		$gwd['us_address'] = $fck_rs['user_address'];
		$gwd['us_tel'] = $fck_rs['user_tel'];
		
		if (empty($gwd['us_name'])||empty($gwd['us_address'])||empty($gwd['us_tel'])) {
			$this->ajaxError('请先完善收货信息！');
		}
		$where = array();
		$where['id'] = array('in','0'. $path .'0');

		$prs = $pora->where($where)->select();
		$gouwu = M('gouwu');
		
		$jpmoney = 0;
		$xfmoney = 0;
		foreach ($prs as $vo){
			if($vo['is_reg']==1){
				$jpmoney += $ids[$vo['id']]*$vo['money'];
			}else{
				$xfmoney += $ids[$vo['id']]*$vo['money'];
			}
		}
		unset($vo);
		
		$s9=M('fee')->getField('s9');
		$st9=explode('|', $s9);
		
		if($jpmoney>0){
			if($fck_rs['u_level']==1){
			   if($jpmoney != $st9[1]){
				$this->error("所购产品金额与升级VIP金额不相符!");
				exit;
			   }else{
				$gwd['lx'] = 1;
			   }
			}
			if($fck_rs['u_level']==2){
				if($jpmoney==$st9[1]){
					$gwd['lx'] = 2;
				}else{
					$this->error("所购产品金额与续费金额不相符!");
					exit;
				}
			}
			
			if($jpmoney>0 && $xfmoney>0){
				$this->error("升级或续费的同时不能选购消费区产品!");
				exit;
			}
		}else{
			$gwd['lx'] = 3;
		}
		
		$gwd['orderfield']=$this->createOrder();
		foreach ($prs as $vo){

			$gwd['did'] = $vo['id'];
			$gwd['money'] = $vo['money'];
			$gwd['shu'] = $ids[$vo['id']];
			$gwd['cprice'] = $ids[$vo['id']]*$vo['money'];
			
			if(!empty($vo['countid'])){
				$gwd['countid'] = $vo['countid'];
			}
			$gouwu->add($gwd);
		}
		$_SESSION["shopping"]='';
		$_SESSION["shopping_bz"]='';
		$this->ajaxSuccess('创建订单成功！',U('Gouwu/BuycpInfo'));

	}
	/**
	 * 创建订单号的方法
	 * @return [type] [description]
	 */
	public function createOrder()	
	{
		return date('YmdHis').'-'.str_pad(rand(1,1000),4,'0',STR_PAD_LEFT);
	}

	public function pay()
	{
		$fee=M('fee')->where('id=1')->field('str6,s15,str12')->find();
		$str6 = explode("|",$fee['str6']);
		$str12 = explode("|",$fee['str12']);
		$s15 = $fee['s15']/100; 
		
		$id =  $_SESSION[C('USER_AUTH_KEY')];
		$order=$this->_get('order');
		$zflx=$this->_get('zflx');
	
		if (!$order) {
			$this->ajaxError('系统繁忙，请稍后再试');
		}else{
			$money=M('gouwu')->where(array('ispay'=>0,'uid'=>$id,'orderfield'=>$order))->sum('cprice');
			//这里应有用户地址检查
			
			$agent_cf=M('fck')->where(array('id'=>$id))->getField('agent_cf');
			$agent_use=M('fck')->where(array('id'=>$id))->getField('agent_use');
			$xflx = M('gouwu')->where(array('ispay'=>0,'uid'=>$id,'orderfield'=>$order))->getField('lx');
	
			if($zflx==0){
				$money_a = $money ;
				if ($agent_use<$money) {
					$this->ajaxError('您的现金点值余额不足，请先充值！');
				}	
			}else{
				$money_a = $money ;
				if ($agent_cf<$money) {
					$this->ajaxError('您的鱼丸账户余额不足，请先充值！');
				}
			}
			
			if ($money<=0) {
				$this->ajaxError('系统繁忙，请稍后再试');
			}else{
				if($zflx==0){
					$re=M('fck')->where(array('id'=>$id))->setDec('agent_use',$money);
					
				}else{
					$re=M('fck')->where(array('id'=>$id))->setDec('agent_cf',$money);
				}
				if ($re) {
					//M('fck')->where(array('id'=>$id))->setInc('gp_num',$money);
					$pay=M('gouwu')->where(array('ispay'=>0,'uid'=>$id,'orderfield'=>$order))->setField('ispay','1');
					if (!$pay) {
						$this->ajaxError('系统繁忙，请稍后再试');
					}else{
						D('Fck')->addencAdd($id,'null', $money,90);
						if($xflx==1){
							//$reid = M('fck')->where(array('id'=>$id))->getField('re_id');
							
							//M('fck')->where(array('id'=>$id))->setField('u_level','2');
							//M('fck')->where(array('id'=>$reid))->setInc('re_nums',1);
							//M('fck')->where(array('id'=>$id))->setField('fanli_time',time());
							//M('fck')->where(array('id'=>$id))->setInc('cpzj',$money);
							//M('fck')->where(array('id'=>$id,'is_lockfh'=>1))->setField('is_lockfh',0);
							
//							$data = array();
//							$data['uid'] = $this->fck['id'];
//							$data['user_id'] = $this->fck['user_id'];
//							$data['in_money'] = $money;
//							$data['in_time'] = time();
//							$data['in_bz'] = "升级VIP";
//							M('shouru')->add($data);
//							unset($data);
														
						}
						if($xflx==2){
							//$renums = M('fck')->where(array('id'=>$id))->getField('re_nums');
							//$futounum = M('gouwu')->where(array('uid'=>$id,'lx'=>array('elt',2)));
							//$allnum = $renums*2+2;
							//if($futounum<=$allnum){
								//M('fck')->where(array('id'=>$id,'is_lockfh'=>1))->setField('is_lockfh',0);
							//}
						}
						
						//D('Fck')->getusjj($id,'0',$money);//购物计算业绩
						//if($xflx==1 || $xflx==2){
							//D('Fck')->gongpaixtsmalldd($id);
						//}
						
						$this->ajaxSuccess('支付成功！');
					}
				}
			}
		}
	}

	/**
	 * 删除订单
	 * @return json ajax请求
	 */
	public function delOrder()
	{
		$id =  $_SESSION[C('USER_AUTH_KEY')];
		$order=$this->_get('order');
		$rs=M('gouwu')->where(array('uid'=>$id,'ispay'=>0,'orderfield'=>$order))->delete();
		if ($rs) {
			$this->ajaxSuccess('删除订单！');
		}else{
			$this->ajaxError('系统繁忙，请稍后再试！');
		}
	}

	public function addAddress(){
		$address = M('address');
		$id =  $_SESSION[C('USER_AUTH_KEY')];
		$did = $_POST['ID'];

		$name = $_POST['s_name'];
		$are = $_POST['s_address'];
		$tel= $_POST['s_tel'];

		$data['uid'] = $id;
		$data['name'] = $name;
		$data['address'] = $are;
		$data['tel'] = $tel;
		$data['moren'] = 0;

		if(empty($did)){
			$result = $address->add($data);
		}else{
			$result = $address->where('id='.$did)->save($data);
		}

		if($result){
			$url = __URL__.'/ShoppingListAdd';
			$this->_box(0,'添加成功！',$url,1);
			exit;
		}else{
			$this->error('添加失败');
		}

	}

	public function moren(){
		$address = M('address');
		$id =  $_SESSION[C('USER_AUTH_KEY')];
		$id = $_GET['ID'];
		$rs  = $address->where('id='.$id)->setField('moren',1);
		$rs2 = $address->where('id !='.$id.' and moren=1')->setField('moren',0);
		if($rs && $rs2){
			echo $id;
		}else{
			echo '0';
		}
	}

	public function addadr(){
		$address = M('address');
		$id = $_GET['ID'];
		$rs  = $address->where('id='.$id)->find();
		$this->assign('rs',$rs);
		$this->assign('did',$id);
		$this -> display('addadr');
	}

	public function delAdr(){
		$address = M('address');
		$id = $_GET['ID'];
		$rs  = $address->where('id='.$id)->delete();
		if($rs){
			$url = __URL__.'/ShoppingListAdd';
			$this->_box(1,'删除地址成功！',$url,1);
			exit;
		}else{
			$this->error('删除失败');
		}
	}


	public function BuycpInfo() {//购买信息
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$agent_cf=$fck->where(array('id'=>$id))->getField('agent_cf');
		$this->assign('agent_cf', $agent_cf);
		$agent_use=$fck->where(array('id'=>$id))->getField('agent_use');
		$this->assign('agent_use', $agent_use);
		
		$map['uid'] = $id;

		$time1=$this->_post('time1')?$this->_post('time1'):0;
		$time2=$this->_post('time2')?$this->_post('time2'):"2037-12-1";

		$map['pdt']=array(array('egt',strtotime($time1)),array('elt',strtotime($time2)));
		
		$m['xt_gouwu.pdt']=array(array('egt',strtotime($time1)),array('elt',strtotime($time2)));

        import ( "@.ORG.Page" );  //导入分页类
        $count = $gouwu->where($map)->count();//总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $show = $Page->show();
        $this->assign('page',$show);
        $p=$this->_get('p',true,'1');
		$where = 'xt_gouwu.ID>0 and xt_gouwu.shu>0 and xt_gouwu.uid ='.$id;
		$field = 'xt_gouwu.orderfield,xt_fck.user_id,xt_product.name,xt_product.img,xt_product.money,xt_product.a_money,xt_gouwu.*';
		$join = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join1 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询
		$list = $gouwu->where($where)->where($m)->field($field)->join($join)->join($join1)->order('pdt desc')->page($p.','.C('ONE_PAGE_RE'))->select();

		$field2='xt_gouwu.orderfield,sum(xt_gouwu.shu) as sum,sum(xt_gouwu.cprice) as money,count(*) as count,ispay';
		$join2 = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join3 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询

		$list2= $gouwu->where($where)->where($m)->field($field2)->join($join)->join($join1)->order('xt_gouwu.pdt desc')->page($p.','.C('ONE_PAGE_RE'))->group('orderfield')->select();
		// var_dump($list);
		// var_dump($list2);
		// echo $gouwu->_sql();
		$rs1 = $gouwu->where($map)->sum('Cprice');
		$this->assign('count', $rs1);
		$this->assign('list', $list);
		$this->assign('list2', $list2);
		
		$feei3 = M('fee')->getField('i3');
		$this->assign('feei3', $feei3);

		if ($this->_post('time1')) {
			$this->assign('time1',$time1);
		}
		if ($this->_post('time2')) {
			$this->assign('time2',$time2);
		}
		$this->display('BuycpInfo');
	}
	
	/**
	 * 商城轮播图
	 * @return [type] [description]
	 */
	public function imgManage()
	{
		$this->assign('url',U('Gouwu/imgslist'));
		$this->display('Public/admincontainer');
	}
	/**
	 * 商品列表 未检测
	 * @return null 
	 */
	public function imgslist(){

		$this->_Admin_checkUser();
		$product = M('indeximg');
		$title = $_REQUEST['stitle'];
		$map = array();
		$map['id'] = array('gt',0);
		$orderBy = 'c_time asc,id asc';

		import("@.ORG.Page");// 导入分页类
		$count      = $product->where($map)->count();
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出

        $p=$this->_get('p',true,'1');
        $list = $product->where($map)->field('*')->order($orderBy)->page($p.',25')->select();
        $this->assign('list',$list);//数据输出到模板

        $this->display();
	}
	
	/**
	 * 产品提交保存的页面
	 * @return [type] [description]
	 */
	public function img_inserts(){
		$this->_Admin_checkUser();
		$product = M('indeximg');
		$data = array();
		//h 函数转换成安全html
		$content = trim($this->_post('detail'));
		$title = trim($this->_post('title'));
		$cid = trim($this->_post('cid'));
		$isindex = trim($this->_post('isreg'));
		$image = trim($this->_post('image'));
	
		$data['title'] = $title;
		$data['content'] = stripslashes($content);
		$data['img_url'] = $image;
		$data['is_index'] = $isindex;
		$form_rs = $product->add($data);
		if (!$form_rs){
			$this->ajaxError($product->_sql());
			exit;
		}
		$this->ajaxSuccess('添加成功');
		exit;
	}
	
	//产品表显示修改
	public function img_edit(){
		$this->_Admin_checkUser();
		$proid = $this->_get('proid');
		$field = '*';
		$product = M ('indeximg');

		$pro = $product->where(array('id'=>$proid))->field($field)->find();
		$this->assign('pro',$pro);
		
		$this->display();
	}

	//产品表修改保存
	public function img_edit_save(){
		$this->_Admin_checkUser();
		$product = M ('indeximg');
		$data = array();


		$content = $this->_post('detail');
		$title = trim($this->_post('title'));
		$cid = trim($this->_post('cid'));
		$image = $this->_post('image');
		$isindex = trim($this->_post('isreg'));
		$ctime = trim($this->_post('ctime'));
		$ctime = strtotime($ctime);
		if (empty($title)){
			$this->error('标题不能为空!');
			exit;
		}

		if(!empty($ctime)){
			$data['c_time'] = $ctime;
		}
		$data['id'] = $cid;
		$data['title'] = $title;
		$data['content'] = $content;
		$data['img_url'] = $image;
		$data['is_index'] = $isindex;
		$rs = $product->save($data);

		if (!$rs){
			$this->error('编辑失败！');
			exit;
		}else{
			$this->ajaxSuccess('编辑成功！',U('Gouwu/imgslist'));
		}
		
		exit;
	}
	
	//产品表操作（启用禁用删除）
	public function imgOperation(){
		$this->_Admin_checkUser();
		//处理提交按钮
		$action = $this->_get('action');
		$proid = $this->_get('proid');
		$product = M ('indeximg');
		switch ($action){
			case 'del';
				$wherea=array();
				$wherea['id'] = $proid;
				$rs = $product->where($wherea)->delete();
				if ($rs){
					$this->ajaxSuccess('删除成功！');
				}else{
					$this->ajaxError('删除失败！');
				}
				break;
			default;
				$this->ajaxError('系统繁忙！');
		}
	}
	
	/**
	 * 商品进入的框架页
	 * @return [type] [description]
	 */
	public function proManage()
	{
		$this->assign('url',U('Gouwu/productslist'));
		$this->display('Public/admincontainer');
	}
	
	/**
	 * 商品列表 未检测
	 * @return null 
	 */
	public function productslist(){

		$this->_Admin_checkUser();
		$product = M('product');
		$title = $_REQUEST['stitle'];
		$map = array();
		if(strlen($title)>0){
			$map['name'] = array('like','%'. $title .'%');
		}
		$map['id'] = array('gt',0);
		$orderBy = 'create_time desc,id desc';

		import("@.ORG.Page");// 导入分页类
		$count      = $product->where($map)->count();
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出

        $p=$this->_get('p',true,'1');
        $list = $product->where($map)->field('*')->order($orderBy)->page($p.',25')->select();
        $this->assign('list',$list);//数据输出到模板

        $typelist = M('cptype')->select();
        $this->assign('typelist',$typelist);
        $this->display();
	}
	public function productAdd()
	{
		$cptype = M('cptype');
		$list = $cptype->where('status=0')->order('id asc')->select();
		$this->assign('list',$list);
		$this->display();
	}

	//产品表显示修改
	public function pro_edit(){
		$this->_Admin_checkUser();
		$proid = $this->_get('proid');
		$field = '*';
		$product = M ('product');

		$pro = $product->where(array('id'=>$proid))->field($field)->find();
		$this->assign('pro',$pro);
		if ($pro){
			$cptype = M('cptype');
			$list = $cptype->where('status=0')->order('id asc')->select();
			$this->assign('list',$list);
			$this->display();
		}else{
			$this->error('没有该信息！');
			exit;
		}
	}

	//产品表修改保存
	public function pro_edit_save(){
		$this->_Admin_checkUser();
		$product = M ('product');
		$data = array();

		$money = trim($this->_post('money'));
		$a_money = $this->_post('scprice');
		$b_money = $this->_post('b_money');
		$content = $this->_post('detail');
		$title = trim($this->_post('title'));
		$cid = trim($this->_post('cid'));
		$image = $this->_post('image');
		$ctime = trim($this->_post('ctime'));
		$ccname = $this->_post('size');
		$num=$this->_post('num');
		$xhname = $this->_post('xhname');
		$countid = $this->_post('countid');
		$is_bd=$this->_post('is_bd');
		$cptype = trim($this->_post('cptype'));
		$isreg   =  trim($this->_post('isreg'));
		$cptype = (int)$cptype;
		$ctime = strtotime($ctime);
		if (empty($title)){
			$this->error('标题不能为空!');
			exit;
		}
		if (empty($cid)){
			$this->error('商品编号不能为空!');
			exit;
		}
		
		if (empty($money)||!is_numeric($money)||empty($a_money)||!is_numeric($a_money)){
			$this->error('价格不能为空!');
			exit;
		}
		if($money <= 0||$a_money <= 0){
			$this->error('输入的价格有误!');
			exit;
		}

		if(!empty($ctime)){
			$data['create_time'] = $ctime;
		}
		$data['id'] = $cid;
		$data['ccname'] = $ccname;
		$data['xhname'] = $xhname;
		$data['money'] = $money;
		$data['a_money'] = $a_money;
		$data['b_money'] = $b_money;
		$data['name'] = $title;
		$data['num'] = $num;
		$data['content'] = $content;
		$data['cptype'] = $cptype;
		$data['is_reg'] = $isreg;
		$data['img'] = $image;
		// $data['countid'] = $countid;
		// echo $isreg;
		$rs = $product->save($data);
		// echo $product->_sql();

		if (!$rs){
			$this->error('编辑失败！');
			exit;
		}else{
			$this->ajaxSuccess('编辑成功！',U('Gouwu/productslist'));
		}
		
		exit;
	}

	//产品表操作（启用禁用删除）
	public function proOperation(){
		$this->_Admin_checkUser();
		//处理提交按钮
		$action = $this->_get('action');
		$proid = $this->_get('proid');
		$product = M ('product');
		switch ($action){
			case 'del';
				$wherea=array();
				$wherea['id'] = $proid;
				$rs = $product->where($wherea)->delete();
				if ($rs){
					$this->ajaxSuccess('删除成功！');
				}else{
					$this->ajaxError('删除失败！');
				}
				break;
			case 'state';
				$wherea=array();
				$wherea['id'] = $proid;
				$rs = $product->where($wherea)->setField('yc_cp',1);
				if ($rs){
					$this->ajaxSuccess('下架产品成功');
					exit;
				}else{
					$this->ajaxError('下架产品失败');
				}
				break;
			case 'stateo';
				$wherea=array();
				$wherea['id'] = $proid;
				$rs = $product->where($wherea)->setField('yc_cp',0);
				if ($rs){
					$this->ajaxSuccess('上架产品成功');
				}else{
					$this->ajaxError('下架产品失败');
				}
				break;
			default;
				$this->ajaxError('系统繁忙！');
		}
	}

	/**
	 * 产品提交保存的页面
	 * @return [type] [description]
	 */
	public function pro_inserts(){
		$this->_Admin_checkUser();
		$product = M('product');
		$data = array();
		//h 函数转换成安全html
		$content = trim($this->_post('detail'));
		$title = trim($this->_post('title'));
		$cid = trim($this->_post('cid'));
		$image = trim($this->_post('image'));
		$money = $this->_post('money');
		$a_money = $this->_post('scprice');
		$b_money = $this->_post('scprice');
		$ccname = $this->_post('size');
		$xhname = $this->_post('xhname');
		$countid = $this->_post('countid');
		$cptype = trim($this->_post('tpname'));
		$isreg = trim($this->_post('isreg'));
		// return $this->ajaxReturn($this->_post());
	
		if (empty($title)){
			$this->error('商品名称不能为空!');
			exit;
		}
		// if (empty($cid)){
		// 	$this->error('商品编号不能为空!');
		// 	exit;
		// }
		if (empty($money)||!is_numeric($money)||empty($a_money)||!is_numeric($a_money)){
			$this->error('价格不能为空!');
			exit;
		}
		if($money <= 0||$a_money <= 0){
			$this->error('输入的价格有误!');
			exit;
		}
		$data['name'] = $title;
		$data['cid'] = $cid;
		$data['content'] = stripslashes($content);
		$data['img'] = $image;
		$data['create_time'] = mktime();
		$data['money'] = $money;
		$data['a_money'] = $a_money;
		$data['b_money'] = $b_money;
		$data['ccname'] = $size;
		$data['xhname'] = $xhname;
		$data['is_reg'] = $isreg;
		// $data['countid'] = $countid;
		$data['cptype'] = $cptype;
		$data['num']=$this->_post('num');
		$form_rs = $product->add($data);
		if (!$form_rs){
			$this->ajaxError($product->_sql());
			exit;
		}
		$this->ajaxSuccess('添加成功');
		exit;
	}

	/**
	 * 分类列表
	 * @return array 
	 */
	public function cptypelist(){
		$this->_Admin_checkUser();

		$product = M('cptype');
		$map = array();
		$map['id'] = array('gt',0);
		$orderBy = 'id asc';
		$field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.Page" );  //导入分页类
        $count = $product->where($map)->count();//总页数
        $Page = new Page($count,20);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $p=$this->_get('p',true,'1');
        $list = $product->where($map)->field($field)->order($orderBy)->page($p.',20')->select();
        $this->assign('list',$list);//数据输出到模板
        $this->display();
	}

	/**
	 * 添加分类
	 * @return [type] [description]
	 */
	public function cptype_add()
	{
		if (!IS_POST) {
			$this->display();
		}else{
			$this->_Admin_checkUser();
			$product = M('cptype');
			$title = trim($this->_post('title'));
			if (empty($title)){
				$this->error('分类名不能为空!');
				exit;
			}
			$data = array();
			$data['tpname'] = $title;
			$form_rs = $product->add($data);
			// echo $product->_sql();
			if (!$form_rs){
				$this->error('添加失败');
				exit;
			}
			$this->redirect('Gouwu/cptypelist');
			
		}
	}
	/**
	 * 分类编辑
	 * @return [type] [description]
	 */
	public function cptype_edit(){
		$this->_Admin_checkUser();
		if (!IS_POST) {
			$EDid = $this->_get('EDid');
			$field = '*';
			$product = M ('cptype');
			$where = array();
			$where['id'] = $EDid;
			$rs = $product->where($where)->field($field)->find();
			if ($rs){
				$this->assign('rs',$rs);
				$this->display();
			}else{
				$this->error('没有该信息！');
				exit;
			}
		}else{
			$cptype = M ('cptype');
			$title = trim($this->_post('title'));
			if (empty($title)){
				$this->error('分类名不能为空!');
				exit;
			}
			$data = array();
			$data['tpname'] = $title;
			$data['id'] = $this->_post('cid');
			$rs = $cptype->save($data);
			if (!$rs){
				$this->error('编辑失败！');
				exit;
			}
			$this->redirect('Gouwu/cptypelist');
		}
	}

	/**
	 * 分类操作
	 * @return [type] [description]
	 */
	public function cptype_opear(){
		$this->_Admin_checkUser();
		//处理提交按钮
		$action = $this->_get('action');
		$id=$this->_get('id');
		$product = M ('cptype');
		switch ($action){
			case 'del';
				$wherea=array();
				$wherea['id'] = $id;
				$rs = $product->where($wherea)->delete();
				if ($rs){
					$this->ajaxSuccess('删除成功！');
				}else{
					$this->ajaxError('删除失败！');
				}
				break;
			default;
				$this->ajaxError('删除失败！');
			break;
		}
	}
	

	public function adminLogistics(){
		$this->_Admin_checkUser();//后台权限检测
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		// $map['uid'] = $id;
		$time1=$this->_post('time1')?$this->_post('time1'):0;
		$time2=$this->_post('time2')?$this->_post('time2'):"2037-12-1";
		$map['pdt']=array(array('gt',$time1),array('lt',$time2));
		switch ($this->_post('state')) {
			case '0':
				$map['ispay']='0';
				break;
			case '1':
				$map['ispay']='1';
				break;
			case '2':
				$map['isfh']='1';
				break;
			case '3':
				$map['isfh']='2';
				break;
			default:
				# code...
				break;
		}

        import ( "@.ORG.Page" );  
        $count = $gouwu->where($map)->count();
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $show = $Page->show();
        $this->assign('page',$show);
        $p=$this->_get('p',true,'1');
		//$where = 'xt_gouwu.ID>0 and xt_gouwu.shu>0 and xt_gouwu.uid ='.$id;
		$where = 'xt_gouwu.ID>0 and xt_gouwu.shu>0';
		$field = 'xt_gouwu.orderfield,xt_fck.user_id,xt_product.name,xt_product.img,xt_product.money,xt_product.a_money,xt_gouwu.*';
		$join = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join1 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询
		$list = $gouwu->where($where)->field($field)->join($join)->join($join1)->order('pdt desc')->page($p.','.C('ONE_PAGE_RE'))->select();

		$field2='xt_gouwu.orderfield,sum(xt_gouwu.shu) as sum,sum(xt_gouwu.cprice) as money,count(*) as count,ispay,isfh';
		$join2 = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join3 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询

		$list2= $gouwu->where($where)->field($field2)->join($join)->join($join1)->order('xt_gouwu.pdt desc')->page($p.','.C('ONE_PAGE_RE'))->group('orderfield')->select();

		$rs1 = $gouwu->where($map)->sum('Cprice');
		$this->assign('count', $rs1);
		$this->assign('list', $list);
		$this->assign('list2', $list2);
		if ($this->_post('state')) {
			$this->assign('state',$_POST['state']);
		}
		if ($this->_post('time1')) {
			$this->assign('time1',$_POST['time1']);
		}
		if ($this->_post('time2')) {
			$this->assign('time2',$_POST['time2']);
		}
        $this->display('adminLogistics');
	}

    public function adminLogisticsAC(){
        $this->_Admin_checkUser();
        $action = $this->_param('action');
        //获取复选框的值
        $XGid = $this->_param('order');
        if (!isset($XGid) || empty($XGid)){
            $this->ajaxError('系统繁忙，请稍后再试！');
            exit;
        }
        switch ($action){
            case 'send';
                $this->_adminLogisticsOK($XGid);
                break;
            case 'get';
                $this->_adminLogisticsDone($XGid);
                break;
            case 'del';
                $this->_adminLogisticsDel($XGid);
                break;
	        default;
	            $bUrl = __URL__.'/adminLogistics';
	            $this->_box(0,'没有该货物！',$bUrl,1);
	            break;
        }
    }

    private function _adminLogisticsOK($XGid){
            $gouwu = M ('gouwu');
            $where = array();
            $kuaidi=$this->_post('kuaidi');
            $danhao=$this->_post('danhao');
            $where['orderfield'] = $XGid;
            // $where['isfh'] = array ('eq',0);

            $valuearray = array(
            	'isfh' => '1',
            	'fhdt' => mktime(),
            	'kuaidi'=>$kuaidi,
            	'danhao'=>$danhao
            );
            $gouwu->where($where)->setField($valuearray);
            unset($gouwu,$where);
            $this->ajaxSuccess('发货成功！');
            exit;
    }
    public function getOrderInfo()
    {
    	$order=$this->_get('order');
    	$list=M('gouwu')->where(array('orderfield'=>$order))->find();
    	$list['pdt']=date('Y-m-d H:i:s',$list['pdt']);
    	$list['fhdt']=date('Y-m-d H:i:s',$list['fhdt']);
    	$list['ispay']=payStatus($list['ispay']);
    	unset($order);
    	return $this->ajaxReturn($list);
    }

    private function _adminLogisticsDone($XGid){
            $shopping = M ('gouwu');

            $where1 = array();
			$where1['orderfield'] = $XGid;
            $where1['isfh'] = array ('eq',1);
            $valuearray = array(
            	'isfh' => '2',
            	'okdt' => mktime()
            );

            $shopping->where($where1)->setField($valuearray);
            unset($shopping,$where1,$where);
            $this->ajaxSuccess('确认收货成功！');
            exit;
    }

    private function _adminLogisticsDel($XGid){
            $shopping = M ('gouwu');
            $where = array();
            $where['orderfield'] = $XGid;
            $shopping->where($where)->delete();
            unset($shopping,$where);
            $this->ajaxSuccess('删除成功！');
            exit;
    }

    public function swapManage()
    {
    	$swap = M('swap');
		$title = $_REQUEST['stitle'];
		$map = array();
		if(strlen($title)>0){
			$map['name'] = array('like','%'. $title .'%');
		}
		$map['id'] = array('gt',0);
		// $map['cid'] =$_SESSION[C('USER_AUTH_KEY')];
		$orderBy = 'create_time desc,id desc';

		import("@.ORG.Page");// 导入分页类
		$count      = $swap->where($map)->count();
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出

        $p=$this->_get('p',true,'1');
        $list = $swap->where($map)->field('*')->order($orderBy)->page($p.',25')->select();
        $this->assign('list',$list);//数据输出到模板

        $typelist = M('cptype')->select();
        $this->assign('typelist',$typelist);
        $this->display();
    }

    public function swaptype()
    {
		$this->_Admin_checkUser();

		$product = M('swaptype');
		$map = array();
		$map['id'] = array('gt',0);
		$orderBy = 'id asc';
		$field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.Page" );  //导入分页类
        $count = $product->where($map)->count();//总页数
        $Page = new Page($count,20);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $p=$this->_get('p',true,'1');
        $list = $product->where($map)->field($field)->order($orderBy)->page($p.',20')->select();
        $this->assign('list',$list);//数据输出到模板
        $this->display();
    }
    public function swaptypeadd()
    {
    	if (!IS_POST) {
			$this->display();
		}else{
			$this->_Admin_checkUser();
			$product = M('swaptype');
			$title = trim($this->_post('title'));
			if (empty($title)){
				$this->error('分类名不能为空!');
				exit;
			}
			$data = array();
			$data['tpname'] = $title;
			$form_rs = $product->add($data);
			// echo $product->_sql();
			if (!$form_rs){
				$this->error('添加失败');
				exit;
			}
			$this->redirect('Gouwu/swaptype');
			
		}
    }
    public function swaptypeedit()
    {
    	if (!IS_POST) {
			$EDid = $this->_get('EDid');
			$field = '*';
			$product = M ('swaptype');
			$where = array();
			$where['id'] = $EDid;
			$rs = $product->where($where)->field($field)->find();
			if ($rs){
				$this->assign('rs',$rs);
				$this->display();
			}else{
				$this->error('没有该信息！');
				exit;
			}
		}else{
			$cptype = M ('swaptype');
			$title = trim($this->_post('title'));
			if (empty($title)){
				$this->error('分类名不能为空!');
				exit;
			}
			$data = array();
			$data['tpname'] = $title;
			$data['id'] = $this->_post('cid');
			$rs = $cptype->save($data);
			if (!$rs){
				$this->error('编辑失败！');
				exit;
			}
			$this->redirect('Gouwu/swaptype');
		}
    }
    public function swapopear()
    {
    	$action = $this->_get('action');
		$id=$this->_get('id');
		$product = M ('swaptype');
		switch ($action){
			case 'del';
				$wherea=array();
				$wherea['id'] = $id;
				$rs = $product->where($wherea)->delete();
				if ($rs){
					$this->ajaxSuccess('删除成功！');
				}else{
					$this->ajaxError('删除失败！');
				}
				break;
			default;
				$this->ajaxError('删除失败！');
			break;
		}
    }

    public function baodan()
    {
    	$list=M()->table('xt_fck fck,xt_product pro')->where("fck.is_pay>0 and fck.is_tj=pro.id")->field('pro.*,fck.id,fck.user_id,fck.get_ceng,fck.pdt')->select();
    	$this->assign('list',$list);
    	$this->display();
    }
    public function baodanAc()
    {
    	$action=$this->_get('action');
    	$id=$this->_get('id');
    	switch ($action) {
    		case 'send':
    			$rs=M('fck')->where(array('id'=>$id,'get_ceng'=>0))->setField('get_ceng',1);
    			if ($rs) {
    				$this->ajaxSuccess('发货成功');
    			}else{
    				$this->ajaxError('发货失败！');
    			}
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    }
    
    /**
     * 上传图片
     * **/
	public function upload_fengcai_pp() {
		// $this->_Admin_checkUser();//后台权限检测
        if(!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload_fengcai_pp();
        }
    }
    /**
     * 普通文件上传
     * @return [type] [description]
     */
    protected function _upload_fengcai_pp()
    {
        header("content-type:text/html;charset=utf-8");
        $this->_Admin_checkUser();//后台权限检测
        // 文件上传处理函数

        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize  = 1048576 * 2 ;// TODO 50M   3M 3292200 1M 1048576
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        $upload->savePath =  './Public/Uploads/image/';

        //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  false;

        $upload->thumbPrefix   =  'm_';  //生产2张缩略图
        $upload->thumbMaxWidth =  '800';
        $upload->thumbMaxHeight = '600';

//		$upload->saveRule = uniqid;
		$upload->saveRule = date("Y").date("m").date("d").date("H").date("i").date("s").rand(1,100);

       //删除原图
       $upload->thumbRemoveOrigin = true;

        if(!$upload->upload()) {
            //捕获上传异常
            $error_p=$upload->getErrorMsg();
            $this->error($error_p);
            exit;
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path=$uploadList[0]['savepath'];
            $U_nname=$uploadList[0]['savename'];
            $U_inpath=(str_replace('./Public/','__PUBLIC__/',$U_path)).$U_nname;
            return $this->ajaxReturn(array('info'=>'上传图片成功','url'=>$U_inpath));
            exit;
        }
    }
    /**
     * 用于layui富文本框的图片上传
     * @return [type] [description]
     */
    public function upload_layedit_pp()
    {
        header("content-type:text/html;charset=utf-8");
        $this->_Admin_checkUser();//后台权限检测
        // 文件上传处理函数

        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize  = 1048576 * 2 ;// TODO 50M   3M 3292200 1M 1048576
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        $upload->savePath =  './Public/Uploads/image/';

        //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  false;

        $upload->thumbPrefix   =  'm_';  //生产2张缩略图
        $upload->thumbMaxWidth =  '800';
        $upload->thumbMaxHeight = '600';

//		$upload->saveRule = uniqid;
		$upload->saveRule = date("Y").date("m").date("d").date("H").date("i").date("s").rand(1,100);

       //删除原图
       $upload->thumbRemoveOrigin = true;

        if(!$upload->upload()) {
            //捕获上传异常
            $error_p=$upload->getErrorMsg();
            $this->error($error_p);
            exit;
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path=$uploadList[0]['savepath'];
            $U_nname=$uploadList[0]['savename'];
            $U_inpath=(str_replace('./Public','/Public',$U_path)).$U_nname;
            $info = array('code' => 0, 'msg'=>'插入成功','data'=>array('src'=>$U_inpath,'title'=>$U_nname));
            return $this->ajaxReturn($info);
            exit;
        }
    }

}
?>