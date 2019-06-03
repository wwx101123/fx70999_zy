<?php
//注册模块
class SwapAction extends CommonAction{
	
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		$this->_inject_check(0);//调用过滤函数
		$this->_Config_name();//调用参数
 		$this->_checkUser();
	}
	
	public function index()
	{
		$product = M('swap');

		$ss_type = (int) $this->_get('tp');
		if($ss_type>0){
			$condition['cptype'] = array('eq',$ss_type);
		}
		$this->assign('tp',$ss_type);
		$condition['yc_cp'] = array('eq',0);
		// $condition['countid'] = array('eq',1);
		// $i5=M('fee')->where(array('id'=>1))->getField('i5');
		// if ($i5==0) {
		// 	$condition['is_reg'] = array('eq',0);
		// }
		$cptype = M('swaptype');
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
		$this->display();
	}
	/**
	 * 二手互易商品详情
	 * @return [type] [description]
	 */
	public function swapDetail()
	{
		$fck = M('fck');
		$product = M ('swap');
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
	
	public function shopCar($value='')
	{
		$pora = M('swap');
		$fck = M('fck');

		$map['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$f_rs = $fck->where($map)->find();
		$agent_xf = $f_rs['agent_xf'];

		$id = $_REQUEST['id'];

		$arr = $_SESSION["shop"];
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
		$this->assign('agent_xf',$agent_xf);
		$this->assign('bzz',$bzz);
		$this->assign('cc',$cc);
		$this->assign('list',$list);
		$this->assign('path',$path);
		$this->assign('ids',$ids);
		$this->assign('num',$num);
		$this->assign('sum',$sum);
		$this->assign('eps',$eps);
		$this->assign('ep',$ep);
		$this->display('shopCar');
	}

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
	/**
	 * 创建订单
	 */
	public function ShopingSave(){
		$Id = (int) $_SESSION[C('USER_AUTH_KEY')];
		$swap = M('swap');
		$fck = D('Fck');

		$arr = $_SESSION["shop"];
		if(empty($arr)){
			$this->error("您的购物车里面没有商品！");
			exit;
		}
		$rs = explode('|',$arr);
		$path = ',';
		foreach ($rs as $vo){
			$str = explode(',',$vo);
			$p_rs = $swap->where('id='.$str[0].'')->find();
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
		$gwd['lx'] = 1;
		$gwd['ispay'] = 0;
		$gwd['pdt'] = mktime();
		$gwd['us_name'] = $fck_rs['nickname'];
		$gwd['us_address'] = $fck_rs['user_address'];
		$gwd['us_tel'] = $fck_rs['user_tel'];
		if (empty($gwd['us_name'])||empty($gwd['us_address'])||empty($gwd['us_tel'])) {
			$this->ajaxError('请先完善收货信息！');
		}
		$where = array();
		$where['id'] = array('in','0'. $path .'0');

		$prs = $swap->where($where)->select();
		$swaporder = M('swaporder');

		// if($fck_rs['agent_xf'] < $prices){
		// 	$this->error("您的购物不余额不足！");
		// 	exit;
		// }
		$gwd['orderfield']=$this->createOrder();
		foreach ($prs as $vo){

			$gwd['did'] = $vo['id'];
			$gwd['money'] = $vo['money'];
			$gwd['shu'] = $ids[$vo['id']];
			$gwd['cprice'] = $ids[$vo['id']]*$vo['money'];
			
			if(!empty($vo['countid'])){
				$gwd['countid'] = $vo['countid'];
			}
			$swaporder->add($gwd);
		}
		$_SESSION["shop"]='';
		$_SESSION["shopp"]='';
		$this->ajaxSuccess('创建订单成功！',U('Swap/swapBuy'));
	}
	/**
	 * 创建订单号的方法
	 * @return [type] [description]
	 */
	public function createOrder()	
	{
		return date('YmdHis').'-'.str_pad(rand(1,1000),4,'0',STR_PAD_LEFT);
	}

	/**
	 * 购物历史记录
	 * @return [type] [description]
	 */
	public function swapOrderList()
	{
		$fck = M('fck');
		$swaporder = M('swaporder');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$map['swapo.uid'] = $id;
		$map['_string']='swap.id=swapo.did';
		$time1=$this->_post('time1')?$this->_post('time1'):0;
		$time2=$this->_post('time2')?$this->_post('time2'):"2037-12-1";

		$list=M()->table('xt_swap swap,xt_swaporder swapo')->where($map)->field('swapo.*,swap.img,swap.name,swap.money,swap.a_money')->select();
		// echo M()->_sql();
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 支付
	 * @return json ajax
	 */
	public function pay()
	{
		$id =  $_SESSION[C('USER_AUTH_KEY')];
		$order=$this->_get('order');
		if (!$order) {
			$this->ajaxError('系统繁忙，请稍后再试');
		}else{
			$money=M('swaporder')->where(array('ispay'=>0,'uid'=>$id,'orderfield'=>$order))->sum('money');
			//这里应有用户地址检查
			
			$agent_use=M('fck')->where(array('id'=>$id))->getField('agent_use');
			if ($agent_use<$money) {
				$this->ajaxError('您的余额不足，请先充值！');
			}
			if ($money<=0) {
				$this->ajaxError('系统繁忙，请稍后再试');
			}else{
				$re=M('fck')->where(array('id'=>$id))->setDec('agent_use',$money);
				M('fck')->where(array('id'=>$id))->setInc('gp_num',$money);
				if ($re) {
					$pay=M('swaporder')->where(array('ispay'=>0,'uid'=>$id,'orderfield'=>$order))->setField('ispay','1');
					if (!$pay) {
						$this->ajaxError('系统繁忙，请稍后再试');
					}else{
						D('Fck')->addencAdd($id,'null', $money,90);
						// D('Fck')->getusjj($id,'0',$money);//购物计算业绩
						$this->ajaxSuccess('支付成功！');
					}
				}
			}
		}
	}
	/**
	 * 进入我的二手商品
	 */
	public function mySwap()
	{
		$swap = M('swap');
		$title = $_REQUEST['stitle'];
		$map = array();
		if(strlen($title)>0){
			$map['name'] = array('like','%'. $title .'%');
		}
		$map['id'] = array('gt',0);
		$map['cid'] =$_SESSION[C('USER_AUTH_KEY')];
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

	public function swapAdd()
	{
		if (!$_POST) {
			$list = M('swaptype')->where('status=0')->order('id asc')->select();
			$this->assign('list',$list);
			$this->display();
		}else{
			$swap = M('swap');
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
			$countid = $_SESSION[C('USER_AUTH_KEY')];
			$cptype = trim($this->_post('tpname'));
			$isreg = trim($this->_post('isreg'));
			// return $this->ajaxReturn($this->_post());
			if (empty($cptype)){
				$this->error('商品分类不能为空!');
				exit;
			}
			if (empty($title)){
				$this->error('商品名称不能为空!');
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
			$data['cid'] =$_SESSION[C('USER_AUTH_KEY')];
			$data['cptype'] = $cptype;
			$data['num']=$this->_post('num');
			$form_rs = $swap->add($data);
			if (!$form_rs){
				$this->ajaxError("添加失败！");
				exit;
			}
			$this->ajaxSuccess('添加成功');
			exit;
		}	
	}

	public function swapEdit()
	{
		if (!$_POST) {
			$proid = $this->_get('proid');
			$field = '*';
			$id =  $_SESSION[C('USER_AUTH_KEY')];
			$pro = M('swap')->where(array('id'=>$proid,'cid'=>$_SESSION[C('USER_AUTH_KEY')]))->field($field)->find();
			$list = M('swaptype')->where('status=0')->order('id asc')->select();
			$this->assign('list',$list);
			$this->assign('pro',$pro);
			$this->display();
		}else{
			$swap = M ('swap');
			$data = array();

			$money   = trim($this->_post('money'));
			$a_money = $this->_post('scprice');
			$b_money = $this->_post('b_money');
			$content = $this->_post('detail');
			$title   = trim($this->_post('title'));
			$image   = $this->_post('image');
			$ctime   = trim($this->_post('ctime'));
			$ccname  = $this->_post('size');
			$num     = $this->_post('num');
			$xhname  = $this->_post('xhname');
			$countid = $this->_post('countid');
			$is_bd   = $this->_post('is_bd');
			$cptype  = trim($this->_post('cptype'));
			$isreg   =  trim($this->_post('isreg'));
			$cptype  = (int)$cptype;
			$ctime   = strtotime($ctime);
			if (empty($title)){
				$this->error('标题不能为空!');
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
			$data['id'] = $this->_post('cid');
			$data['ccname']  = $ccname;
			$data['xhname']  = $xhname;
			$data['money']   = $money;
			$data['a_money'] = $a_money;
			$data['b_money'] = $b_money;
			$data['name']    = $title;
			$data['num']     = $num;
			$data['content'] = $content;
			$data['cptype']  = $cptype;
			$data['is_reg']  = $isreg;
			$data['img']     = $image;
			// $data['countid'] = $countid;
			// echo $isreg;
			$rs = $swap->save($data);
			if (!$rs){
				$this->error('编辑失败！');
				exit;
			}else{
				$this->ajaxSuccess('编辑成功！',U('Swap/mySwap'));
			}
		}
	}

	//产品表操作（启用禁用删除）
	public function proOperation(){
		$action = $this->_get('action');
		$proid = $this->_get('proid');
		$swap = M ('swap');
		$wherea['cid']=$_SESSION[C('USER_AUTH_KEY')];
		switch ($action){
			case 'del';
				$wherea=array();
				$wherea['id'] = $proid;
				$rs = $swap->where($wherea)->delete();
				if ($rs){
					$this->ajaxSuccess('删除成功！');
				}else{
					$this->ajaxError('删除失败！');
				}
				break;
			case 'state';
				$wherea=array();
				$wherea['id'] = $proid;
				$rs = $swap->where($wherea)->setField('yc_cp',1);
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
				$rs = $swap->where($wherea)->setField('yc_cp',0);
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

	public function swapsell()
	{
		$fck = M('fck');
		$swaporder = M('swaporder');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$map['uid'] = $id;

        import ( "@.ORG.Page" );  //导入分页类
        $count = $swaporder->where($map)->count();//总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $show = $Page->show();
        $this->assign('page',$show);
        $p=$this->_get('p',true,'1');

		$list = M()->table('xt_swap swap,xt_swaporder swapo')->
		where('swap.id=swapo.did and swap.cid='.$id)->field('*')
		->order('pdt desc')->page($p.','.C('ONE_PAGE_RE'))->select();
		$this->assign('list',$list);
		// var_dump($list);
        $this->display();
	}


	public function LogisticsAC(){
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
            $gouwu = M ('swaporder');
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
    	// var_dump($this->_get());
    	$list=M('swaporder')->where(array('orderfield'=>$order))->find();
    	$list['pdt']=date('Y-m-d H:i:s',$list['pdt']);
    	$list['fhdt']=date('Y-m-d H:i:s',$list['fhdt']);
    	$list['ispay']=payStatus($list['ispay']);
    	unset($order);

    	return $this->ajaxReturn($list);
    }

    private function _adminLogisticsDone($XGid){
            $shopping = M ('swaporder');

            $where1 = array();  
			$where1['orderfield'] = $XGid;
            $where1['isfh'] = array ('eq',1);
            $where1['uid'] = $_SESSION[C('USER_AUTH_KEY')];
            $valuearray = array(
            	'isfh' => '2',
            	'okdt' => mktime()
            );
            $shopping->where($where1)->setField($valuearray);
			$seller=M()->table('xt_swap swap,xt_swaporder swapo')->where("swap.id=swapo.did and swapo.orderfield='$XGid'")->find();
			$fck=D('Fck');
			$str25=M('fee')->where('id=1')->getField('str25');
			// echo $str25;
			// echo M()->_sql();
			$shouxu=$str25/100;

			$moneyc=$seller['money']-$seller['money']*$shouxu;
			// echo $moneyc;
			$fck->where(array('id'=>$seller['cid']))->setInc('agent_use',$moneyc);
            // echo M()->_sql();
            $fck->addencAdd($rs['id'], $vo['user_id'], $money_a, 30,0,0,0,$kt_cont);//历史记录
            unset($fck,$shopping,$where1,$where);
            $this->ajaxSuccess('确认收货成功！');
            exit;
    }

    private function _adminLogisticsDel($XGid){
            $shopping = M ('swaporder');
            $where = array();
            $where['orderfield'] = $XGid;
            $shopping->where($where)->delete();
            unset($shopping,$where);
            $this->ajaxSuccess('删除成功！');
            exit;
    }

    public function swapBuy()
    {
    	$fck = M('fck');
		$swaporder = M('swaporder');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$map['uid'] = $id;

        import ( "@.ORG.Page" );  //导入分页类
        $count = $swaporder->where($map)->count();//总页数
        $Page = new Page($count, C('ONE_PAGE_RE'));
        $show = $Page->show();
        $this->assign('page',$show);
        $p=$this->_get('p',true,'1');

		$list = M()->table('xt_swap swap,xt_swaporder swapo')->
		where('swap.id=swapo.did and swapo.uid='.$id)->field('*')
		->order('pdt desc')->page($p.','.C('ONE_PAGE_RE'))->select();
		$this->assign('list',$list);
		// var_dump($list);
        $this->display();


    }

    	/**
	 * 删除购物车商品
	 * @return [type] [description]
	 */
	public function delBuyList(){
		$ID = $_REQUEST['id'];
		$shopping_id ='';
		$arr = $_SESSION["shop"];

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
		$_SESSION["shop"] = $shopping_id;
		$this->success("删除成功！");
	}
	/**
	 * 图片上传
	 * @return [type] [description]
	 */
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
            $U_inpath=(str_replace('./Public', dirname(__APP__).'/Public',$U_path)).$U_nname;
            $info = array('code' => 0, 'msg'=>'插入成功','data'=>array('src'=>$U_inpath,'title'=>$U_nname));
            return $this->ajaxReturn($info);
            exit;
        }
    }
}
?>