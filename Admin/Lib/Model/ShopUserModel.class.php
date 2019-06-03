<?php
// 用户模型
class ShopUserModel extends Model {
    protected $trueTableName ='am_users';
    //新增商城会员
    public function  insertShopUser(FckModel $hyUser){
        if(isset($hyUser)){
           // $shopUser =D('ShopUser');

                $u=array();
                $u['xt_fck_id']=$hyUser->id;
                $u['u_name']=$hyUser->user_id;
                $u['u_pwd']=$hyUser->password;
                $u['u_secondpwd']=$hyUser->passopen;
                $u['u_amount']=$hyUser->b1;
                $u['u_zamount']=$hyUser->b2;
                $u['u_xfjf']=$hyUser->b3;
                $u['u_zsjf']=$hyUser->agent_gp;
                $u['u_status']=1;
                $u['u_type']=0;
                $u['u_regtime'] = time();
                $res = $this->add($u);

        }else{

            exit;

        }

    }
    //逻辑删除会员
    public function  deleteShopUser(FckModel $hyUser){
        if(isset($hyUser)){

            $where = array();
            $where['xt_fck_id'] = array ('in',$hyUser->id);
            $rs = $this -> where($where) -> find();
//          $rs->u_status = 2;
//          $rs->save();
            $hyUser->delete();

        }else{
			exit;
        }

    }
    //更新会员
    public function  updateShopUser(FckModel $hyUser){
      if(isset($hyUser)){
           // $hyUser->find();
            $where = array();
            $where['xt_fck_id'] = array ('in',$hyUser->id);
            $rs = $this -> where($where) -> find();

			$u=array();
			$this->u_name=$hyUser->user_id;
			$this->u_pwd=$hyUser->password;
			$this->u_secondpwd=$hyUser->passopen;
			$this->u_amount=$hyUser->b1;
			$this->u_zamount=$hyUser->b2;
			$this->u_xfjf=$hyUser->b3;
			$this->u_zsjf=$hyUser->agent_gp;

			$res = $this->save();

       }else{
            exit;
       }
    }
}
?>