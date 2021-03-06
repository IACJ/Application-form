<?php  
require "config.php";


	/* 
	* 本程序只返回一个数字即 $chunzhao::$response;
	* 规定数字含义如下  
	*  100  报名已经截止
	*  99   入口未开放
	*  0    【提交成功】
    *  1    信息不完整
    *  2    信息有错误,不符合要求
    *  3    信息不能正常提交（数据库配置错误、PDO运行出错等）
    *  4    非法访问 或 重复提交表单
	*/
class Chunzhao{

	public static $info = "";   //信息提示
	public static $response = 3; //返回状态码



	//表单验证
	public function check(){

		//-------TO DO：时间验证-----------
		// date_default_timezone_set("Asia/Shanghai");
		// $startTime = strtotime("2017-4-14 23:59:59");
		// $endTime   = strtotime("2017-4-19 23:59:59");
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


		//防空 (注意，第二志愿可为空)
		if (empty($_POST) || empty($_POST['name']) || 
				empty($_POST['sex']) || empty($_POST['college']) || 
				empty($_POST['grade']) || empty($_POST['dorm']) || 
				empty($_POST['phone']) || empty($_POST['department1']) ){

			self::$info = "信息不完整";
			self::$respons = 1;
			return false;
		}

		//验证token
		if (empty($_POST['token']) || empty($_SESSION['token']) || $_SESSION['token'] != $_POST['token']) {
			self::$info = "非法访问";
			self::$response = 4;
			return false;
		}else{
			unset($_SESSION['token']); //将token销毁
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



		//------- TO DO: 针对数据库字段长度等信息，再增添一些合理的表单验证------


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

			//绑定数据，防止SQL注入
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
				self::$info = 'SQLSTAE: '.$arrError[0].'  SQL Error: '.$arrError[2];  //拼接错误信息
				self::$response = 3 ;
				//echo self::$info;  for debug
				return false;
			}

		}catch(PDOException $e){
			self::$info = "PDO异常:".$e;
			self::$response = 3;
			return false;
		}

		//执行成功
		self::$info = "执行成功";
		self::$response = 0;
		return true;
	}
}


 // ____main____
session_start();
$chunzhao = new Chunzhao();
$chunzhao->insert();
echo $chunzhao::$response;
//echo $qiuzhao::$info   ONLY WHEN DEBUG


