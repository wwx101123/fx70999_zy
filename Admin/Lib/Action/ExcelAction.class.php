<?php
class ExcelAction extends CommonAction {
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		
	}
	
	//分润表
	public function profitflowsDaoChu_MM()
	{
	    // 导出excel
	    set_time_limit(0);
	    $user_id = I('user_id', 0);
	    header("Content-Type:   application/vnd.ms-excel");
	    header("Content-Disposition:   attachment;   filename=分润记录.xls");
	    header("Pragma:   no-cache");
	    header("Content-Type:text/html; charset=utf-8");
	    header("Expires:   0");
	    
	    $tiqu = M('history'); // 奖金表
	    
	    $map = array();
	    $map['_string']= "   t.bz like '%分润-%'";
	    $field = 't.*,g.shop_id';
	    $history = M('history');
	  
	    $list =$history->alias('t')
	    ->join("xt_trade_orders AS g ON g.order_no = t.order_no ", 'LEFT')->where($map)
	    ->field($field)
	    ->order('t.pdt desc,t.id desc')
	     
	     
	    ->select();
	    
	    $title = "分润表 导出时间:" . date("Y-m-d   H:i:s");
	    
	    echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
	    // 输出标题
	    echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
	    // 输出字段名
	    echo '<tr  align=center>';
	    echo "<td>序号</td>";
	    echo "<td>订单号</td>";
	    echo "<td>商户号</td>";
	    echo "<td>所属商户</td>";
	    echo "<td>用户账号</td>";
	    echo "<td>实时等级</td>";
	    echo "<td>盟友交易量</td>";
	    echo "<td>分润交易量</td>";
	    echo "<td>分润金额</td>";
	    echo "<td>上传时间</td>";
	    echo "<td>交易时间</td>"; 
	    echo '</tr>';
	    // 输出内容
	    
	    // dump($list);exit;
	    
	    $fck = M('fck');
	    $i = 0;
	    foreach ($list as $row) {
	        
	        $i ++;
	        $num = strlen($i);
	        if ($num == 1) {
	            $num = '000' . $i;
	        } elseif ($num == 2) {
	            $num = '00' . $i;
	        } elseif ($num == 3) {
	            $num = '0' . $i;
	        } else {
	            $num = $i; 
	            
	           
	        }
	        
	        $usrs = $fck->where('id=' . $row['uid'])
	        ->field('id,user_id,user_name')
	        ->find();
	        $user_name = $usrs['user_id'] . '(' . $usrs['user_name'] . ')';
	        
	        
	        $order_no=chr(28)."\t". $row['order_no']  ."\t";
	        
	        echo '<tr align=center>';
	        echo '<td>' . chr(28) . $num . '</td>'; 
	        echo "<td>" . $order_no . "</td>";
	        echo "<td>" . $row['shop_id'] . "</td>";
	        echo "<td>" . $row['shop_title'] . "</td>";
	        echo "<td>" . $user_name. "&nbsp;</td>";
	        echo "<td>V" . $row['u_level'] . "</td>"; 
	        echo "<td>" . $row['all_trade_money'] . "</td>"; 
	        echo "<td>" . $row['trade_money'] . "</td>"; 
	        echo "<td>" . $row['epoints'] . "</td>"; 
	        echo "<td>" . date("Y-m-d H:i:s", $row['pdt']) . "</td>";
	        echo "<td>" . date("Y-m-d H:i:s", $row['trade_time']) . "</td>";  
	        echo '</tr>';
	    }
	    echo '</table>';
	}
	
	//交易量表
	public function tradeflowsDaoChu_MM()
	{
	    // 导出excel
	    set_time_limit(0);
	    $user_id = I('user_id', 0);
	    header("Content-Type:   application/vnd.ms-excel");
	    header("Content-Disposition:   attachment;   filename=交易量记录.xls");
	    header("Pragma:   no-cache");
	    header("Content-Type:text/html; charset=utf-8");
	    header("Expires:   0");
	    
	    $tiqu = M('tiqu'); // 奖金表
	    
	    $map = array();
	    $map['t.id'] = array(
	        'gt',
	        0
	    );
	    $map['t.user_id'] = array(
	        'gt',
	        0
	    );
	   
	    
	    $field = 't.*';
	    $history = M('trade_orders');
	    $field = 't.*,g.user_id,g.user_name,g.user_tel,g.shop_name,g.bank_name ';
	    $list = $history->alias('t')
	    ->join("xt_fck AS g ON   g.id = t.user_id ", 'LEFT')
	    ->where($map)
	    ->field($field)
	    
	    ->select();
	    
	    $title = "交易量表 导出时间:" . date("Y-m-d   H:i:s");
	    
	    echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
	    // 输出标题
	    echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
	    // 输出字段名
	    echo '<tr  align=center>';
	    echo "<td>序号</td>";
	    echo "<td>订单号</td>";
	    echo "<td>商户号</td>";
	    echo "<td>所属商户</td>";
	    echo "<td>所属盟友</td>";
	    echo "<td>交易量</td>";
	    echo "<td>上传时间</td>";
	    echo "<td>交易时间</td>";
	    echo '</tr>';
	    // 输出内容
	    
	    // dump($list);exit;
	    
	    $i = 0;
	    foreach ($list as $row) {
	        
	        $i ++;
	        $num = strlen($i);
	        if ($num == 1) {
	            $num = '000' . $i;
	        } elseif ($num == 2) {
	            $num = '00' . $i;
	        } elseif ($num == 3) {
	            $num = '0' . $i;
	        } else {
	            $num = $i;
	            
	            
	        }
	        
	        $order_no=chr(28)."\t". $row['order_no']  ."\t";
	        
	        echo '<tr align=center>';
	        echo '<td>' . chr(28) . $num . '</td>';
	        echo "<td>" . $order_no . "</td>";
	        echo "<td>" . $row['shop_id'] . "</td>";
	        echo "<td>" . $row['shop_title'] . "</td>";
	        echo "<td>" . $row['user_name'] . "&nbsp;</td>";
	        echo "<td>" . $row['trade_money'] . "</td>";
	        echo "<td>" . date("Y-m-d H:i:s", $row['add_time']) . "</td>";
	        echo "<td>" . date("Y-m-d H:i:s", $row['trade_time']) . "</td>";
	        echo '</tr>';
	    }
	    echo '</table>';
	}
	//提现表
	public function currencyDaoChu_MM()
	{
	    // 导出excel
	    set_time_limit(0);
	    $user_id = I('user_id', 0);
	    header("Content-Type:   application/vnd.ms-excel");
	    header("Content-Disposition:   attachment;   filename=提现记录.xls");
	    header("Pragma:   no-cache");
	    header("Content-Type:text/html; charset=utf-8");
	    header("Expires:   0");
	    
	    $tiqu = M('tiqu'); // 奖金表
	    
	    $map = array();
	    $map['t.id'] = array(
	        'gt',
	        0
	    );
	    
	    $field = 't.*,g.user_id,g.user_name,g.user_tel,g.shop_name,g.bank_name ';
	    $list = $tiqu->alias('t')
	    ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
	    ->where($map)
	    ->field($field)
	    ->order('t.rdt desc')
	    ->select();
	    
	    $title = "终端下发表 导出时间:" . date("Y-m-d   H:i:s");
	    
	    echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
	    // 输出标题
	    echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
	    // 输出字段名
	    echo '<tr  align=center>';
	    echo "<td>序号</td>";
	    echo "<td>盟友</td>";
	    echo "<td>姓名</td>";
	    echo "<td>联系方式</td>";
	    echo "<td>所属银行</td>";
	    echo "<td>银行卡号</td>";
	    echo "<td>所属省市</td>";
	    echo "<td>提现状态</td>";
	    echo "<td>提现时间</td>";
	    echo "<td>金额</td>";
	    echo '</tr>';
	    // 输出内容
	    
	    // dump($list);exit;
	    
	    $i = 0;
	    foreach ($list as $row) {
	        
	        $i ++;
	        $num = strlen($i);
	        if ($num == 1) {
	            $num = '000' . $i;
	        } elseif ($num == 2) {
	            $num = '00' . $i;
	        } elseif ($num == 3) {
	            $num = '0' . $i;
	        } else {
	            $num = $i;
	            
	            
	        }
	        $status_str='未发';
	        if($row['is_pay']==1)
	        {
	            
	            $status_str='已发';
	        }
	        $bank_card=chr(28)."\t". $row['bank_card']  ."\t";
	        
	        echo '<tr align=center>';
	        echo '<td>' . chr(28) . $num . '</td>';
	        echo "<td>" . $row['user_id'] . "</td>";
	        echo "<td>" . $row['user_name'] . "</td>";
	        echo "<td>" . $row['user_tel'] . "&nbsp;</td>";
	        echo "<td>" . $row['bank_name'] . "</td>";
	        echo "<td>" . $bank_card . "</td>";
	        echo "<td>" . $row['bank_address'] . "</td>";
	        echo "<td>" . $status_str . "</td>";
	        echo "<td>" . date("Y-m-d H:i:s", $row['rdt']) . "</td>";
	        echo "<td>" . $row['money'] . "</td>";
	        echo '</tr>';
	    }
	    echo '</table>';
	}
	// 终端表
	public function terminalDaoChu_MM()
	{
	    // 导出excel
	    set_time_limit(0);
	    $user_id = I('user_id', 0);
	    header("Content-Type:   application/vnd.ms-excel");
	    header("Content-Disposition:   attachment;   filename=终端下发.xls");
	    header("Pragma:   no-cache");
	    header("Content-Type:text/html; charset=utf-8");
	    header("Expires:   0");
	    
	    $user_terminal = M('user_terminal'); // 奖金表
	    
	    $map = array();
	    $map['t.id'] = array(
	        'gt',
	        0
	    );
	    
	    $field = 't.*,g.user_id,g.user_name,g.user_tel,g.shop_name ';
	    $list = $user_terminal->alias('t')
	    ->join("xt_fck AS g ON   g.id = t.uid ", 'LEFT')
	    ->where($map)
	    ->field($field)
	    ->order('t.add_time asc')
	    ->select();
	    
	    $title = "终端下发表 导出时间:" . date("Y-m-d   H:i:s");
	    
	    echo '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
	    // 输出标题
	    echo '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">' . $title . '</td></tr>';
	    // 输出字段名
	    echo '<tr  align=center>';
	    echo "<td>序号</td>";
	    echo "<td>SN编号</td>";
	    echo "<td>所属盟友</td>";
	    echo "<td>联系方式</td>";
	    echo "<td>所属股东</td>";
	    echo "<td>机器状态</td>";
	    echo "<td>到期状态</td>";
	    echo "<td>返现状态</td>";
	    echo "<td>下发时间</td>";
	    echo "<td>到期时间</td>";
	    echo "<td>交易量</td>";
	    echo '</tr>';
	    // 输出内容
	    
	    // dump($list);exit;
	    
	    $i = 0;
	    foreach ($list as $row) {
	        
	        $i ++;
	        $num = strlen($i);
	        if ($num == 1) {
	            $num = '000' . $i;
	        } elseif ($num == 2) {
	            $num = '00' . $i;
	        } elseif ($num == 3) {
	            $num = '0' . $i;
	        } else {
	            $num = $i;
	        }
	        $expire_status_str = get_expire_status_str($row['expire_time']);
	        
	        
	        $status_str= get_terminal_status_str($row);
	        $terminal_fan_status_str= get_terminal_fan_status_str($row);
	        $sn=chr(28)."\t". $row['sn']  ."\t";
	        echo '<tr align=center>';
	        echo '<td>' . chr(28) . $num . '</td>';
	        echo "<td>" .$sn. "</td>";
	        echo "<td>" . $row['user_id'] . "</td>";
	        echo "<td>" . $row['user_tel'] . "&nbsp;</td>";
	        echo "<td>" . $row['shop_name'] . "</td>";
	        echo "<td>" . $status_str . "</td>";
	        echo "<td>" . $expire_status_str . "</td>";
	        echo "<td>" . $terminal_fan_status_str . "</td>";
	        echo "<td>" . date("Y-m-d H:i:s", $row['add_time']) . "</td>";
	        echo "<td>" . date("Y-m-d H:i:s", $row['expire_time']) . "</td>";
	        echo "<td>" . $row['trade_money'] . "</td>";
	        echo '</tr>';
	    }
	    echo '</table>';
	}

}
?>