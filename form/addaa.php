<?php  
require "config.php";

class Qiuzhao{

	public static $info = "";   //信息提示
	public static $response = 0; //返回状态码

	//表单验证
	public function check(){

		//-------TODO：时间验证-----------
		// date_default_timezone_set("Asia/Shanghai");
		// $startTime = strtotime("2016-9-11 12:40:00");
		// $endTime = strtotime("2016-9-14 23:59:59");
		// $nowTime = time();
		// if ($nowTime < $startTime) {
		// 	self::$info = "报名未开始";
		// 	self::$response = 99;
		// 	return false;			
		// }
		// if ($nowTime > $endTime) {
		// 	self::$info = "报名已结束";
		// 	self::$response = 100;
		// 	return false;			
		// }

		//防空
		if (empty($_POST) || empty($_POST['name']) || empty($_POST['sex']) || empty($_POST['college']) || empty($_POST['grade']) || empty($_POST['dorm']) || empty($_POST['phone']) || empty($_POST['department1']) ){
			self::$info = "信息不完整";
			self::$respons = 1;
			return false;
		}

		//验证token
		if (empty($_POST['token']) || empty($_SESSION['token']) || $_SESSION['token'] != $_POST['token']) {
			self::$info = "非法访问";
			self::$response = 5;
			return false;
		}else{
			unset($_SESSION['token']);
		}

		//检查手机号
		$pattern = "/^1[3,4,5,7,8]\d{9}$/";
		if (!preg_match( $pattern, $_POST['phone'] )) {
			self::$info = "手机号错误";
			self::$response = 2;
			return false;
		}
		//检查志愿是否一样
		if ($_POST['department1'] == $_POST['department2']) {
			self::$info = "志愿错误";
			self::$response = 2;
			return false;
		}
		//检查宿舍号
		$pattern = "/^[Cc][01]?\d.*[ -]\d{3}$/";
		if (!preg_match( $pattern, $_POST['dorm'] )) {
			self::$info = "宿舍号错误";
			self::$response = 2;
			echo $_POST['dorm'];
			return false;
		}
		return true;
	}

    //插入数据
	public function insert(){
		//表单验证
		if (!$this->check()){
			return false;
		}

		//创建POD并插入一条数据

		try{
			//$pdo = new PDO('mysql:host=localhost;dbname=qiuzhao','root','');
			$pdo  = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PWD);
			$pdo->exec("SET NAMES 'utf8';");
			$sql = "INSERT INTO user(name,sex,college,grade,dorm,phone,department1,department2,intro,time) VALUES(:name,:sex,:college,:grade,:dorm,:phone,:department1,:department2,:intro,now())";
			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':name',$_POST['name'],PDO::PARAM_STR);
			$stmt->bindParam(':sex',$_POST['sex'],PDO::PARAM_STR);
			$stmt->bindParam(':college',$_POST['college'],PDO::PARAM_STR);
			$stmt->bindParam(':grade',$_POST['grade'],PDO::PARAM_STR);
			$stmt->bindParam(':dorm',$_POST['dorm'],PDO::PARAM_STR);
			$stmt->bindParam(':phone',$_POST['phone'],PDO::PARAM_STR);
			$stmt->bindParam(':department1',$_POST['department1'],PDO::PARAM_STR);
			$stmt->bindParam(':department2',$_POST['department2'],PDO::PARAM_STR);
			$stmt->bindParam(':intro',$_POST['intro'],PDO::PARAM_STR);

			$stmt->execute();

			//检查是否出错
			$arrError = $stmt->errorInfo();
			if ($arrError[0] !='00000') {
				self::$info = 'SQLSTAE: '.$arrError[0].'  SQL Error: '.$arrError[2];
				self::$response = 3 ;
				//echo self::$info;
				return false;
			}

		}catch(PDOException $e){

			self::$info = "PDO异常:".$e;
			self::$response = 3;
			return false;
		}

		//成功了
		self::$info = "执行成功";
		self::$response = 0;
		return true;
	}
}


 // ____main____
session_start();
$qiuzhao = new Qiuzhao();
$qiuzhao->insert();
echo $qiuzhao::$response;


