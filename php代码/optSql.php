<?php

class optSql
{
	// 连接数据库，成功true, 失败false
	public function connect()
	{
		$db = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
		if ($db)
		{
			mysql_select_db(SAE_MYSQL_DB, $db);				// 解决数据库信息中文乱码问题
			return true;
		}
		else return false;
	}
	// 查询语句
	// 参数学号，姓名, 返回值：一个数组：包含卡上余额，水费余额，用电余额
	public function query($id, $name)
	{
		// sql语句，查询学号和姓名
		$sql = "select 卡上余额, 水费余额, 剩余电量 from studentsInfo, studentsBalance, dormsInfo
where studentsInfo.学号 = studentsBalance.学号 and studentsInfo.住址 = dormsInfo.住址 and studentsInfo.学号 = '{$id}' and 姓名 = '{$name}';";
		// 执行sql语句
		$query = mysql_query($sql);
		// 获取执行的结果，存入一个数组中，该数组可以用字段表示键
		$ans = mysql_fetch_array($query);

		return $ans;
	}
}
?>