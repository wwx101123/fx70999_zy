<?php
if (! defined('THINK_PATH'))
    exit();
$config = require PATH . '/config.php';
$array = array(
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'memberuser_p_x7816', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY' => 'administrator',
    'USER_AUTH_MODEL' => 'fck', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式
    'NOT_AUTH_MODULE' => 'Public,Reg', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => '', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'SHOW_RUN_TIME' => false, // 运行时间显示
    'SHOW_ADV_TIME' => false, // 显示详细的运行时间
    'SHOW_DB_TIMES' => false, // 显示数据库查询和写入次数
    'SHOW_CACHE_TIMES' => false, // 显示缓存操作次数
    'SHOW_USE_MEM' => false, // 显示内存开销
                              // 'SHOW_PAGE_TRACE' =>true, //开启页面Trace
    'ONE_PAGE_RE' => 10, // 每页显示记录数
    'PAGE_LISTROWS' => 10,
    'PAGE_ROLLPAGE' => 5,
    'DB_LIKE_FIELDS' => 'title|remark',
    'RBAC_ROLE_TABLE' => 'think_role',
    'RBAC_USER_TABLE' => 'think_role_user',
    'RBAC_ACCESS_TABLE' => 'think_access',
    'RBAC_NODE_TABLE' => 'think_node',
    'USER_AUTH_GATEWAY' => '/Public/login', // 默认认证网关
    'DATA_BACKUP_PATH' => 'public/upload/sqldata/', //数据库备份根路径
    'DATA_BACKUP_PART_SIZE' => 20971520, //数据库备份卷大小
    'DATA_BACKUP_COMPRESS' => 0, //数据库备份文件是否启用压缩
    'DATA_BACKUP_COMPRESS_LEVEL' => 9, //数据库备份文件压缩级别
    //模版配置：
    'TMPL_PARSE_STRING' => array(
    
        '__UPIMGS__'     => __ROOT__ . '',
        '__PUBUPIMG__'     => __ROOT__ . '/Public/Public/upload',
        '__savePath__'     => __ROOT__ . '/Public/uploadS'
    ) ,
                                            
    // 'TMPL_ACTION_ERROR' => '', // 默认错误跳转对应的模板文件
    
    'VAR_PAGE' => 'p', // 分页传递参数
    'VAR_PAGE1' => 'pp', // 分页传递参数
                         
    // =======奖金项名称============
    'Bonus_B1' => '奖金',
    'Bonus_B1c' => '', // 空则显示, style="display:none;"则不显示
    'Bonus_B2' => '服务补贴',
    'Bonus_B2c' => '',
    'Bonus_B3' => '分享补贴',
    'Bonus_B3c' => '',
    'Bonus_B4' => '个人所得税',
    'Bonus_B4c' => '',
    'Bonus_B5' => '报单补贴积分',
    'Bonus_B5c' => '',
    'Bonus_B6' => '分红',
    'Bonus_B6c' => 'style="display:none;"',
    'Bonus_B7' => '理财金',
    'Bonus_B7c' => 'style="display:none;"',
    'Bonus_B8' => '税收',
    'Bonus_B8c' => 'style="display:none;"',
    'Bonus_B9' => '直推奖',
    'Bonus_B9c' => 'style="display:none;"',
    'Bonus_B10' => '直推奖',
    'Bonus_B10c' => 'style="display:none;"',
    'Bonus_HJ' => '合计',
    'Bonus_HJc' => 'style="display:none;"',
    'Bonus_Bb0' => '实发配物券',
    'Bonus_Bb0c' => '',
    'Bonus_B0' => '实发奖金',
    'Bonus_B0c' => '',
    'Bonus_XX' => '详细',
    'Bonus_XXc' => '',
    
    // =======系统参数=========
    'System_namex' => '卓异商城', // 系统名字
    'System_bankx' => '农业银行|工商银行', // 银行名字
                                    // 'System_bankx' => '财付通', //银行名字
    'User_namex' => '会员账户',
    'Nick_namex' => '昵称',
    'Agent_Us_Name' => '非代理|服务中心|服务中心|服务中心', // 会员级别名称
    'Member_Level' => '普通会员|经销商', // 会员级别名称
    'Member_Money' => '6600|1000|3000|9000|18000|36000', // 注册金额
    'Member_Single' => '1|2|6|18|36|72', // 会员级别单数
    
    'BAK_Data_Path' => 'Bak_data', // 备份数据路径
    'BAK_Zip_Path' => 'Bak_zip', // 压缩数据路径
    'BAK_Error_Path' => 'ErrorLog', // 还原错误文档存储路径
    'agent_use' => '账户余额',
    'limit_money' => 'dd',
    'live_gupiao' => '授权金额',
    'agent_cash' => '兑换积分',
    'agent_kt' => '券',
    'buy_point' => '购物积分',
    'ssb' => '交易劵',
    'jjbb' => '奖金',
    /**
     * *微信**
     */
    "wx_appID" => "wx81f84faee44296a3",
    "wx_appsecret" => "68ae3fd3de66bfc1167fa0ca8eafc012",
    "weixin_token" => "",
    /**
     * OAuth2.0授权后跳转到的默认页面*
     */
    "wx_webauth_callback_url" => urlencode("http://posadmin.super-nba.com/adm.php/Weixin/notify.html"),
    "wx_webauth_callback_url2" => urlencode("http://posadmin.super-nba.com/adm.php/Weixin/wxInfo.html"),
    "wx_url" => "posadmin.super-nba.com",
    "wx_webauth_expire" => 6500,
    
    'regsmsexpired' => '1',
    'System_sign' => '卓异商城',
    'weburl' => 'www.ycgz5.com',
    'SESSION_SMS_CODE' => 'SESSION_SMS_CODE',
    'COOKIE_USER_MOBILE' => 'COOKIE_USER_MOBILE',
    'sms_appid' => '1400101689',
    'sms_appkey' => '7ec87dcb23bcc3a784c1d3f8ec42828a',
    'recharge_parm' => '1',
    'tixian_parm1' => '1',
    'tixian_parm2' => '0.1',
    'GETUI_APP_KEY' => 'keypJdEA137Z8U6dvD8dB7',
    'GETUI_APPID' => '9rpTBUgGmw9573eZ87Zgh7',
    'GETUI_MASTERSECRET' => 'vSeh7qDxQf88hiP8cjeJM3',
    'LOCK_MSG' => '您已锁定,请联系管理员',
    're_share_sub_title' => '{0}邀请您加盟,免费注册！卓异商城APP，赚钱首选',
    're_share_title' => '有需要卓异商城来帮您！',
    're_pay_sub_title' => '亲,江湖救急,帮忙付个款,滴水之恩,定当泉涌相报',
    're_pay_title' => '{0}希望你帮他付{1}！',
     'tax_txt'=>'所得税',
    'pos_txt'=>'pos分润',
    'tx_txt'=>'提现',
    'cz_txt'=>'充值',
    'configId'=>'f8ff2a35-fc39-4d08-8d52-17deb46b2db6',
    'easemob_username'=>'391948204@qq.com',
    'easemob_password'=>'liubin1989',
    
    'ali_sms_tid' => 'SMS_6035023',
    
    'ali_sms_tid_change_bank' => 'SMS_6035020',
    'ali_sms_tid_change_wx' => 'SMS_6035020',
    'chat_id' => '1',
    'seller_type' => '传统POS|二维码POS|二维码POS', // 银行名字
    'get_detail_error_list'=>'SELECT
					T.*
				FROM
					(
						SELECT
							uid,
							B.user_id AS 用户名,
							B.user_name AS 姓名,
							B.agent_use AS 目前余额,
							COUNT(A.ID)-3 AS 错误次数,
							FROM_UNIXTIME(A.pdt) AS 发生时间,
							A.pdt,
							order_no as 订单号,
							sum(epoints) AS 扣除的金额
						FROM
							xt_history A
						LEFT JOIN xt_fck B ON A.UID = B.ID
						WHERE
							A.order_no IS NOT NULL
						GROUP BY
							A.UID,
							order_no
					) T
				WHERE
					T.错误次数 > 0
				ORDER BY
					T.PDT DESC',
    'get_error_list'=>'SELECT
	P.uid,
	P.用户名,
	P.姓名 ,
	P.目前余额 ,
	P.time_str,
	
	P.需要扣除的金额 AS 总共需要扣除的金额,
	(目前余额 - 需要扣除的金额) AS 扣除完的余额
FROM
	(
		SELECT
			m.*, sum(扣除的金额) AS 需要扣除的金额
		FROM
			(
				SELECT
					T.*
				FROM
					(
						SELECT
							uid,
							B.user_id AS 用户名,
							B.user_name AS 姓名,
							B.agent_use AS 目前余额,
							COUNT(A.ID)-3 AS 错误次数,
							FROM_UNIXTIME(A.pdt) AS 发生时间,
							FROM_UNIXTIME(A.pdt) AS time_str,
							A.PDT,
							order_no as 订单号,
							sum(epoints) AS 扣除的金额
						FROM
							xt_history A
						LEFT JOIN xt_fck B ON A.UID = B.ID
						WHERE
							A.order_no IS NOT NULL
						GROUP BY
							A.UID,
							order_no
					) T
				WHERE
					T.错误次数 > 0
				ORDER BY
					T.PDT DESC
			) m
		GROUP BY
			m.uid
	) p;',
    'image_upload_limit_size'=>1024 * 1024 * 5,
    'default_filter'         => 'htmlspecialchars',
    'erasable_type' =>['.gif','.jpg','.jpeg','.bmp','.png','.mp4','.3gp','.flv','.avi','.wmv'],
    
);
return array_merge($config, $array);
?>
