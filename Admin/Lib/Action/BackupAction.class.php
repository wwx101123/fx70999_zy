<?php
// 本类由系统自动生成，仅供测试用途
class BackupAction extends CommonAction
{

    public function _initialize()
    {
        $this->_checkUser();
        $this->_Admin_checkUser(); // 后台权限检测
        header("Content-type: text/html; charset=utf-8");
    }

    public function Check_C_config()
    {
        // 默认备份配置
        $bpath = C('BAK_Data_Path');
        $zpath = C('BAK_Zip_Path');
        $epath = C('BAK_Error_Path');
        if (empty($bpath)) {
            $bpath = "Bak_data";
        }
        if (empty($zpath)) {
            $zpath = "Bak_zip";
        }
        if (empty($epath)) {
            $epath = "ErrorLog";
        }
        $array[1] = $bpath;
        $array[2] = $zpath;
        $array[3] = $epath;
        return $array;
    }

    public function index()
    {
        $dbtables = M()->query('SHOW TABLE STATUS');
        $total = 0;
        foreach ($dbtables as $k => $v) {
            $dbtables[$k]['size'] = format_bytes($v['Data_length'] + $v['Index_length']);
            $total += $v['Data_length'] + $v['Index_length'];
        }
        $this->assign('list', $dbtables);
        $this->assign('total', format_bytes($total));
        $this->assign('tableNum', count($dbtables));
        
        $this->display();
    }
    /**
     * 优化
     */
    public function optimize() {
        $batchFlag = I('get.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag) {
            $table = I('key', array());
        }else {
            $table[] = I('tablename' , '');
        }
    
        if (empty($table)) {
            $this->error('请选择要优化的表');
        }
    
        $strTable = implode(',', $table);
        if (!M()->query("OPTIMIZE TABLE {$strTable} ")) {
            $strTable = '';
        }
        $this->success("优化表成功" . $strTable, U('Backup/index'));
    
    }
    
    /**
     * 修复
     */
    public function repair() {
        $batchFlag = I('get.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag) {
            $table = I('key', array());
        }else {
            $table[] = I('tablename' , '');
        }
    
        if (empty($table)) {
            $this->error('请选择修复的表');
        }
    
        $strTable = implode(',', $table);
        if (!M()->query("REPAIR TABLE {$strTable} ")) {
            $strTable = '';
        }
    
        $this->success("修复表成功" . $strTable, U('Backup/index'));
    
    }
    public function restore(){
        $path = C('DATA_BACKUP_PATH');
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);
        $list = array();$filenum = $total = 0;
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                $info = pathinfo($file);
                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $info['compress'] = ($info['extension'] === 'sql') ? '-' : $info['extension'];
                $info['time']  = strtotime("{$date} {$time}");
                $filenum++;
                $total += $info['size'];
                $list["{$date} {$time}"] = $info;
            }
        }
        $this->assign('list', $list);
        $this->assign('filenum',$filenum);
        $this->assign('total',$total);
          $this->display();
    }
    
    /**
     * 下载
     * @param int $time
     */
    public function downFile($time = 0) {
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        if(is_array($files)){
            foreach ($files as $filePath){
                if (!file_exists($filePath)) {
                    $this->error("该文件不存在，可能是被删除");
                }else{
                    $filename = basename($filePath);
                    header("Content-type: application/octet-stream");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . filesize($filePath));
                    readfile($filePath);
                }
            }
        }
    }
    

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->error('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    
    /**
     * 执行还原数据库操作
     * @param int $time
     * @param null $part
     * @param null $start
     */
    public function import($time = 0, $part = null, $start = null){
        function_exists('set_time_limit') && set_time_limit(0);
        
        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
            
            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
                $this->success('初始化完成！', U('Backup/import', array('part' => 1, 'start' => 0)));
            } else {
                $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            import("@.ORG.Backup");
            $db = new Backup($list[$part], array(
                'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));
            $start = $db->import($start);
            if(false === $start){
                $this->error('还原数据出错！');
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原...#{$part}", null, $data);
                } else {
                    session('backup_list', null);
                    $this->success('还原完成！', U('Backup/restore'));
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->success("正在还原...#{$part} ({$rate}%)", U('Backup/import', $data));
                } else {
                    $data['gz'] = 1;
                    $this->success("正在还原...#{$part}", U('Backup/import', $data));
                }
            }
        } else {
            $this->error('参数错误！');
        }
    }
    public function restoreUpload()
    {
        import("@.ORG.Request");
//         $Request=new Request();
//         $request =$Request->request();
        
        
        $file=$_FILES['sqlfile'];
        if(empty($file)){
            $this->error('请先上传sql文件');
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $path = UPLOAD_PATH.'sqldata' ;
        $info = $_FILES;
        if ($info) {
            //上传成功 获取上传文件信息
            $file_path_full = $info->getPathName();
            if (file_exists($file_path_full)) {
                import("@.ORG.Backup");
                $sqls = Backup::parseSql($file_path_full);
                if(Backup::install($sqls)){
                    //                    array_map("unlink", glob($path));
                    $this->success("导入sql文件成功", U('Backup/restore'));
                }else{
                    $this->error('sql文件导入失败');
                }
            } else {
                $this->error('sql文件上传失败');
            }
        } else {
            //上传错误提示错误信息
            $this->error($file->getError());
        }
    }
    
    // 遍历
    public function list_file($dir)
    {
        import("@.ORG.KuoZhan"); // 导入扩展类
        $KuoZhan = new KuoZhan();
        $arraylist = array();
        $list = scandir($dir); // 得到该文件下的所有文件和文件夹
        $i = 0;
        
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($KuoZhan->is_utf8($file) == false) {
                        $file = iconv('GB2312', 'UTF-8', $file);
                    }
                    if ($file == "." || $file == "..") { // 判断是不是文件夹
                        continue;
                    }
                    $file_location = $dir . "/" . $file;
                    $arraylist[$i]['name'] = $file;
                    $arraylist[$i]['time'] = $this->selecttime($file_location);
                    $arraylist[$i]['path'] = $file_location;
                    $arraylist[$i]['getpath'] = str_replace("/", "|", $file_location);
                    $i ++;
                }
                closedir($dh);
            }
        }
        
        return $arraylist;
    }

    public function selecttime($file)
    {
        import("@.ORG.KuoZhan"); // 导入扩展类
        $KuoZhan = new KuoZhan();
        if ($KuoZhan->is_utf8($file) == true) {
            $file = iconv('UTF-8', 'GB2312', $file);
        }
        $retime = filectime($file);
        return $retime;
    }
    
    // 处理
    public function indexAC()
    {
        $action = $this->_get('action');
        if ($action == "del") {
            $this->index_del();
        }
        if ($action == "huanyuan") {
            $this->DBHuanYuan();
        }
        if ($action == "down") {
            // $this->DBDoZip();
            $path = $this->_get('mname');
            $backulr = __URL__ . "/DBDoZip/mname/" . $path;
            echo "<script>window.location='" . $backulr . "';</script>";
            echo "数据下载...下载完成后请点击 [<a href='" . __URL__ . "'>返回</a>]";
            exit();
        }
        die();
        $this->error('参数错误！');
        exit();
    }
    
    // 删除文件
    public function index_del()
    {
        $fieldname = $this->_get('fname');
        import("@.ORG.KuoZhan"); // 导入扩展类
        $KuoZhan = new KuoZhan();
        if ($KuoZhan->is_utf8($fieldname) == true) {
            $fieldname = iconv('UTF-8', 'GB2312', $fieldname);
        }
        if (empty($fieldname)) {
            $this->error('参数错误1！');
            exit();
        }
        
        $now_path = $_SERVER["DOCUMENT_ROOT"];
        $web_path = __ROOT__ . '/';
        // $web_path = substr($web_path,1);
        $all_path = $now_path . $web_path . $fieldname;
        if (is_dir($all_path)) {
            $errmsg = "";
            $this->deldir($all_path, $errmsg);
        } else {
            unlink($all_path); // 删除
        }
        if ($errmsg) {
            $this->error("删除失败，错误：" . $errmsg);
            exit();
        } else {
            $bUrl = __URL__ . '/index/';
            $this->success('删除成功！');
            exit();
        }
    }

    public function deldir($dir, &$errmsg)
    {
        
        // 先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (! is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath, $errmsg);
                }
            }
        }
        closedir($dh);
        // 删除当前文件夹：
        if (rmdir($dir)) {} else {
            $errmsg .= "<li>删除" . $dir . "失败！</li>";
        }
    }
    
    // 还原
    public function DBHuanYuan()
    {
        // 读取文件
        set_time_limit(0);
        $fieldname = $this->_get('fname');
        $fieldname = str_replace("|", "/", $fieldname);
        import("@.ORG.KuoZhan"); // 导入扩展类
        $KuoZhan = new KuoZhan();
        if ($KuoZhan->is_utf8($fieldname) == true) {
            $fieldname = iconv('UTF-8', 'GB2312', $fieldname);
        }
        if (empty($fieldname)) {
            $this->error('参数错误1！');
            exit();
        }
        $dir = $fieldname;
        $filearray = array();
        if (is_dir($dir)) { // 目录
            if ($dh = opendir($dir)) {
                $i = 0;
                while (($file = readdir($dh)) !== false) {
                    if ($file == "." || $file == "..") { // 判断是不是文件夹
                        continue;
                    }
                    if ($KuoZhan->is_utf8($file) == true) {
                        $file = iconv('UTF-8', 'GB2312', $file);
                    }
                    $file_location = $dir . "/" . $file;
                    if (is_dir($file_location)) { // 不包含文件夹内
                    } else {
                        if ($KuoZhan->is_utf8($file) == false) {
                            $file = iconv('GB2312', 'UTF-8', $file);
                        }
                        $file_location = $dir . "/" . $file;
                        $filearray[$i] = $file_location;
                    }
                    $i ++;
                }
                closedir($dh);
            }
        } else { // 文件
            if ($KuoZhan->is_utf8($dir) == false) {
                $dir = iconv('GB2312', 'UTF-8', $dir);
            }
            $filearray[] = $dir;
        }
        sort($filearray); // 重新排序
        $errmsg = "";
        $msg = "";
        $maxx = count($filearray);
        $thnn = 0;
        ob_start(); // 打开输出缓冲区
        ob_end_flush();
        ob_implicit_flush(1); // 立即输出
        foreach ($filearray as $vo) {
            $thnn ++;
            $this->sql_query($vo, $errmsg, $msg, $thnn, $maxx);
        }
        if ($errmsg) {
            
            echo $errmsg;
            exit();
        } else {
            echo $msg;
            exit();
        }
    }

    public function sql_query($dir = "", &$errmsg = "", &$msg = "", $n = 0, $maxt = 0)
    {
        import("@.ORG.KuoZhan"); // 导入扩展类
        $KuoZhan = new KuoZhan();
        if ($KuoZhan->is_utf8($dir) == true) {
            $dir = iconv('UTF-8', 'GB2312', $dir);
        }
        $sql = file_get_contents($dir);
        $sql = mb_convert_encoding($sql, "UTF-8", "auto"); // 自动转码
        $arr = explode('-- <fen> --', $sql);
        if (count($arr) > 1) {
            array_pop($arr); // 删除最后一个空元素
        }
        $err = 0;
        
        $tablerows = count($arr);
        if ($tablerows >= 1000) {
            $wanlen = (int) ($tablerows / 1000) . '000';
        } else {
            $wanlen = $tablerows;
        }
        $ti = 0;
        $titt = $ti + 1;
        echo str_repeat(" ", 4096); // 确保足够的字符
        echo '<script language="javascript">' . 'window.parent.restorepresent("' . $n . '","' . $maxt . '","' . $titt . '","' . $wanlen . '");' . '</script>';
        $cp = 1;
        foreach ($arr as $value) {
            $ti ++;
            $ttt = $ti;
            $re = M()->query($value);
            if (! is_array($re)) {
                $err ++;
                $tt = M()->getLastSql();
                $mssg .= "-- 错误： --\r\n" . $tt . "\r\n";
            }
            
            if ($tablerows >= 1000) {
                $want = 1;
            } else {
                $want = 0;
            }
            $tdt = 1000 * $cp;
            $modd = (int) ($ttt / $tdt);
            if ($modd > 0) {
                $cp ++;
                echo str_repeat(" ", 4096); // 确保足够的字符
                echo '<script language="javascript">' . 'window.parent.restorepresent("' . $n . '","' . $maxt . '","' . $ti . '","' . $wanlen . '");' . '</script>';
            } elseif ($ttt == $tablerows) {
                if ($want == 0) {
                    echo str_repeat(" ", 4096); // 确保足够的字符
                    echo '<script language="javascript">' . 'window.parent.restorepresent("' . $n . '","' . $maxt . '","' . $ti . '","' . $wanlen . '");' . '</script>';
                }
            }
        }
        usleep(100000); // 0.5秒
        
        if ($err > 0) {
            $C_path = $this->Check_C_config();
            $ctdir = "./" . $C_path[3];
            if (! is_dir($ctdir)) {
                mkdir($ctdir, 0777); // 创建文件夹
            } else {
                chmod($ctdir, 0777); // 改变文件模式
            }
            $data .= "-- " . $err . "条错误语句未执行！ --\r\n" . $mssg;
            
            $date = date("Y-m-d H-i-s");
            $ctname = "error " . $date;
            $this->createtable_txt($ctdir, $ctname, $data, $err, 1);
            $wronglog = $ctdir . "/" . $ctname . ".log";
            $errmsg = "包含错误，保存文档：" . $wronglog . ' 。';
        } else {
            $msg = "还原成功！";
        }
    }
    
    // 备份
    public function DBBeiFen()
    {
        set_time_limit(0);
        $C_path = $this->Check_C_config();
        // 写入文件
        $date = Date("Y-m-d-H-i-s"); // 不能用冒号，否则创建文件失败！
        $fdirmk = "./" . $C_path[1];
        if (! is_dir($fdirmk)) {
            mkdir($fdirmk, 0777); // 创建文件夹
        } else {
            chmod($fdirmk, 0777); // 改变文件模式
        }
        $randmk = "BAK-" . $date . "-" . rand(1000, 9999);
        $dir = $fdirmk . "/" . $randmk;
        if (! is_dir($dir)) {
            mkdir($dir, 0777); // 创建文件夹
        } else {
            chmod($dir, 0777); // 改变文件模式
        }
        
        $err = "";
        
        $this->getDBQk($dir, $err); // 清空表
        $this->getField($dir, $err); // 创建表
        $this->getData($dir, $err); // 插入表
        
        if ($err) {
            $errdir = $dir . "/error.sql";
            $err = mb_convert_encoding($err, "UTF-8", "auto"); // 自动转码
            $handle = fopen($errdir, "w");
            if (! $handle) {
                $this->error("" . $err . "", "__URL__");
            }
            if (! fwrite($handle, $err)) {
                $this->error("" . $err . "", "__URL__");
            }
            fclose($handle);
        }
        $this->ajaxSuccess('备份成功!');
    }

    public function DBDoZip()
    {
        set_time_limit(0);
        $path = $_GET['mname'];
        $C_path = $this->Check_C_config();
        import("@.ORG.KuoZhan"); // 导入扩展类
        $KuoZhan = new KuoZhan();
        if ($KuoZhan->is_utf8($path) == true) {
            $path = iconv('UTF-8', 'GB2312', $path);
        }
        if (empty($path)) {
            $this->error('参数错误！');
            exit();
        }
        if (strstr($path, "..")) {
            $this->error('参数错误！');
            exit();
        }
        
        $now_path = $_SERVER["DOCUMENT_ROOT"];
        $web_path = __ROOT__ . '/';
        // $web_path = substr($web_path,1);
        $bak_path = $C_path[1] . "/";
        $all_path = $now_path . $web_path . $bak_path . $path;
        if (! file_exists($all_path)) {
            $this->error('路径错误！');
            exit();
        }
        $zipname = $path . ".zip";
        $this->ZipFile($path, $zipname);
        
        $gourl = __URL__ . "/DownZip/f/" . $zipname . "/p/" . $path;
        $backulr = __URL__;
        echo "<script>window.location='" . $gourl . "';</script>";
    }

    public function DownZip()
    {
        $C_path = $this->Check_C_config();
        $file_name = $_GET['f'];
        $file_name = str_replace("/", "", $file_name);
        $file_name = str_replace("\\", "", $file_name);
        $file_name = str_replace("..", "", $file_name);
        if (empty($file_name)) {
            $this->error('路径错误！');
            exit();
        }
        $downfile = $C_path[2] . "/" . $file_name;
        
        $now_path = $_SERVER["DOCUMENT_ROOT"];
        $web_path = __ROOT__ . '/';
        // $web_path = substr($web_path,1);
        $all_path = $now_path . $web_path . $downfile;
        if (! $handle = fopen($all_path, 'rw')) {
            $this->error('文件找不到，或文件正在使用中，无法打开缓存文件.');
            exit();
        } else {
            // 输入文件标签
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($all_path));
            Header("Content-Disposition: attachment; filename=" . $file_name);
            // 输出文件内容
            echo fread($handle, filesize($all_path));
            fclose($handle);
        }
    }
    
    // 清空数据库
    function DBQk()
    {
        $Tlist = $this->getTables();
        foreach ($Tlist as $value) {
            $sql = "DROP TABLE `$value`";
            $re = M()->query($sql);
        }
    }
    
    // 获取所有表名称
    function getTables()
    {
        $database = C('DB_NAME'); // 数据库名
        $sql = "SHOW TABLES FROM `$database`";
        $re = M()->query($sql);
        
        $arr = array();
        foreach ($re as $v) {
            $arr[] = $v["Tables_in_$database"];
        }
        return $arr;
    }
    
    // 清空数据库
    function getDBQk($ctdir = "./DB", &$err)
    {
        $Tlist = $this->getTables();
        foreach ($Tlist as $value) {
            
            $sql = "DROP TABLE `$value`";
            $str = "-- 清空表的: $v --\r\n";
            $data .= $str . $sql . ";\r\n-- <fen> --\r\n";
        }
        
        $ctname = "a drop tables";
        $this->createtable_txt($ctdir, $ctname, $data, $err);
    }
    
    // 表的结构
    function getField($ctdir = "./DB", &$err)
    {
        $list = $this->getTables();
        $arr = array();
        $data = "";
        foreach ($list as $v) {
            $sql = "SHOW CREATE TABLE `$v`";
            $re = M()->query($sql);
            
            $str = "-- 表的结构: $v --\r\n";
            $data .= $str . $re[0]['Create Table'] . ";\r\n-- <fen> --\r\n";
        }
        $ctname = "create tables";
        $this->createtable_txt($ctdir, $ctname, $data, $err);
    }
    
    // 表的数据
    function getData($ctdir = "./DB", &$err)
    {
        ob_start(); // 打开输出缓冲区
        ob_end_flush();
        ob_implicit_flush(1); // 立即输出
        $list = $this->getTables();
        $maxt = count($list);
        $ccc = 0;
        foreach ($list as $v) {
            $n = $ccc + 1;
            $cnsql = "select count(0) as tp from `$v`";
            $str = "-- 表的数据: $v --\r\n";
            $tb_rsc = M()->query($cnsql); // 总页数
            $tablerows = $tb_rsc[0]['tp'];
            $wanlen = $tablerows;
            $ti = 0;
            $titt = $ti + 1;
            echo str_repeat(" ", 4096); // 确保足够的字符
            echo '<script language="javascript">' . 'window.parent.checkpresent("' . $n . '","' . $maxt . '","' . $titt . '","' . $wanlen . '");' . '</script>';
            $one_p = 1000;
            $pagee = ceil($tablerows / $one_p);
            for ($pp = 0; $pp < $pagee; $pp ++) {
                $mysql = '';
                $s_p = $pp * $one_p;
                $limits = $s_p . "," . $one_p;
                $scsql = "select * from `$v` limit " . $limits;
                $re = M()->query($scsql);
                if ($re) {
                    for ($i = 0; $i < count($re); $i ++) {
                        $ti ++;
                        $ttt = $ti;
                        $mysql .= "INSERT INTO `$v` VALUES (";
                        foreach ($re[$i] as $value) {
                            if (gettype($value) == 'string') {
                                // $value=mysql_real_escape_string($value);//转义
                                $mysql .= "'$value',";
                            } elseif (empty($value)) {
                                $mysql .= "NULL,";
                            } else {
                                $mysql .= "$value,";
                            }
                        }
                        $mysql = substr($mysql, 0, strlen($mysql) - 1); // 去除","
                        $mysql .= ");-- <fen> --\r\n";
                    }
                    echo '<script language="javascript">' . 'window.parent.checkpresent("' . $n . '","' . $maxt . '","' . $ti . '","' . $wanlen . '");' . '</script>';
                }
                $data = $str . $mysql;
                $tb_nn = $pp + 1;
                $ctname = "insert " . $v . "_" . $tb_nn;
                $this->createtable_txt($ctdir, $ctname, $data, $err);
            }
            $ccc ++;
            usleep(100000); // 0.5秒
        }
    }
    
    // 压缩
    function ZipFile($path, $zipname)
    {
        @include ("class/phpzip.inc.php");
        $C_path = $this->Check_C_config();
        $z = new PHPZip();
        $bak_data_path = $C_path[1];
        $bak_zip_path = $C_path[2];
        if (! is_dir($bak_zip_path)) {
            mkdir($bak_zip_path, 0777); // 创建文件夹
        } else {
            chmod($bak_zip_path, 0777); // 改变文件模式
        }
        $z->Zip($bak_data_path . "/" . $path, $bak_zip_path . "/" . $zipname, 0); // ָĿ¼
        unset($z);
    }
    
    // 建立文件
    function createtable_txt($ctdir = "./DB", $ctname = "", $data = "", &$err, $type = 0)
    {
        if ($type == 1) {
            $hz = "log";
        } else {
            $hz = "sql";
        }
        $dir = $ctdir . "/" . $ctname . "." . $hz;
        $sql = mb_convert_encoding($data, "UTF-8", "auto"); // 自动转码
        $handle = fopen($dir, "w");
        if (! $handle) {
            $err = "<li>打开文件" . $dir . "失败!</li>";
        }
        if (! fwrite($handle, $sql)) {
            $err = "<li>写入文件" . $dir . "失败!</li>";
        }
        fclose($handle);
    }
    
    // 完成
    public function endgookweb()
    {
        $tttt = 20;
        for ($o = 0; $o < $tttt; $o ++) {
            usleep(100000);
        }
        $cc = "操作完成";
        echo $cc;
        exit();
    }
    public function export($tables = null, $id = null, $start = null){
        //防止备份数据过程超时
        function_exists('set_time_limit') && set_time_limit(0);
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            $path = C('DATA_BACKUP_PATH');
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path'     => realpath($path) . DIRECTORY_SEPARATOR,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                //                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
                return $this->ajaxReturn(array('info'=>'检测到有一个备份任务正在执行，请稍后再试！', 'status'=>0, 'url'=>''));
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }
    
            //检查备份目录是否可写
            //            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            if(!is_writeable($config['path'])){
                return $this->ajaxReturn(array('info'=>'备份目录不存在或不可写，请检查后重试！', 'status'=>0, 'url'=>''));
            }
            session('backup_config', $config);
    
            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', $_SERVER['REQUEST_TIME']),
                'part' => 1,
            );
            session('backup_file', $file);
            //缓存要备份的表
            session('backup_tables', $tables);
            //创建备份文件
            import("@.ORG.Backup");
            $Database = new Backup($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                //                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
                return $this->ajaxReturn(array('tables' => $tables, 'tab' => $tab, 'info'=>'初始化成功！', 'status'=>1, 'url'=>''));
            } else {
                //                $this->error('初始化失败，备份文件创建失败！');
                return $this->ajaxReturn(array('info'=>'初始化失败，备份文件创建失败！', 'status'=>0, 'url'=>''));
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            import("@.ORG.Backup");
            $Database = new Backup(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                //                $this->error('备份出错！');
                return $this->ajaxReturn(array('info'=>'备份出错！', 'status'=>0, 'url'=>''));
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    //                    $this->success('备份完成！', '', array('tab' => $tab));
                    return $this->ajaxReturn(array('tab' => $tab, 'info'=>'备份完成！', 'status'=>1, 'url'=>''));
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    //                    $this->success('备份完成！');
                    return $this->ajaxReturn(array('info'=>'备份完成！', 'status'=>1, 'url'=>''));
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                //                $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
                return $this->ajaxReturn(array('tab' => $tab, 'info'=>"正在备份...({$rate}%)", 'status'=>1, 'url'=>''));
            }
    
        } else {//出错
            //            $this->error('参数错误！');
            return $this->ajaxReturn(array('info'=>'参数错误！', 'status'=>0, 'url'=>''));
        }
    }
}
?>