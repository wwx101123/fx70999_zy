<?php
// 用户模型
class ShopBonusModel extends Model {
    protected $trueTableName ='xt_fck';
    //更新积分与结算消费
    public function  updateShopBonus($uid,$money,$stype){
        
		$Fck =D('Fck');
		
		$mrs = $Fck->where('id='.$uid)->field('id,user_id,re_path,u_level,agent_kt,agent_xf')->find();
		
		if($mrs){
			$agent_kt = $mrs['agent_kt'];
			$agent_xf = $mrs['agent_xf'];
			
			$u=array();
			if($stype==0){
			$u['agent_kt'] = $agent_kt - $money;
			}else{
			$u['agent_xf'] = $agent_xf - $money;
			}		   
			$res = $Fck->where('id='.$uid)->save($u);
			
			$inUserID = $mrs['user_id'];
			$repath = $mrs['re_path'];
			
			$Fck->getusjj($uid,1,$money);
		}
	   
    }
    
}
?>