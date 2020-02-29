<?php

$id = $_POST['id'];
$name = $_POST['name'];
// 连接数据库
$db = mysqli_connect("local", "root", " ", "weixindb");
// 如果数据库连接成功
if ($db) 
{
    mysqli_select_db($db, SAE_MYSQL_DB);				// 解决数据库信息中文乱码问题
    // sql语句，查询学号和姓名
    $sql = "select 卡上余额, 水费余额, 剩余电量 from studentsInfo, studentsBalance, dormsInfo
where studentsInfo.学号 = studentsBalance.学号 and studentsInfo.住址 = dormsInfo.住址 and studentsInfo.学号 = '{$id}' and 姓名 = '{$name}';";
    // 执行sql语句
	$query = mysqli_query($db, $sql);
    // 获取执行的结果，存入一个数组中，该数组可以用字段表示键
	$ans = mysqli_fetch_array($query, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>信息查询</title>

<style type="text/css">
   .Content-Main{   
           max-width: 3000px;
            margin: auto;
            margin-top: 0px;
            padding: 1200px 300px 200px 160px;
            font: 30px "华文新魏", sans-serif;
           
            background:#FFFFFF;
        }
        .Content-Main h1{
            display: block;

            padding: 0px 0px 0px 180px;
            margin: -1100px 0px 0px 200px;
            font: 120px "华文新魏", sans-serif;
            color: #000000;
            width:1000px;

        }
       
        .Content-Main label{
            display: block;
            font: 70px "华文新魏", sans-serif;
            margin: 5px 0px 5px;
        }
        .Content-Main label>span{
          
            width: 40%;
            padding-right: 20px;
            margin-top: 50px;
            font: 70px "华文新魏", sans-serif;
            font-weight: bold;
            text-align: right;
            color: #000000;
            
        }
        .Content-Main input[type="text"],.Content-Main textarea{
           font: 70px "华文新魏", sans-serif;
            width: 1200px;
            height: 120px;
            padding: 15px 5px 10px 10px;
            margin-bottom: 170px;
            margin-right: 12px;
            margin-top: 6px;
            line-height: 80px;
            border-radius: 5px;
            border: 3px solid #333399;
            color: #000000;
            background-color:#CCCCFF;
        }
     
        .Content-Main input{
            width: 1000px;
            height: 120px;
            margin-right: 30px;
     
            background-color:#CCCCFF;
            padding: 5px 0px 5px;
			font: 70px "华文新魏", sans-serif;
        }
        .button{                                              /*提交按钮*/
            padding: 20px 25px 20px 25px;
            width:900px;
            margin-left: 100px;
            border-radius: 5px;
             font: 70px "华文新魏", sans-serif;
            border:3px solid #6666CC;
            background: #CCCCFF;
            color:#000000;
        
        }
        .button:hover{                  /*光标选中提交按钮后效果*/
            color: #333;
            background-color: #6699CC;
            border-color: #333399;
        }
   </style>
</head>

<body>
<div class="Content-Main">
	<div class="titlee">
		<h1>信息查询</h1>
	</div>
   	  <hr style="height:2px;border:none;border-top:3px dashed #6666CC;" />
		<label><!-- 卡上余额查询部分结构 -->
			<span>卡上余额</span><!-- 查询结果部分    -->
            <input readonly="readonly"value="<?php echo "{$ans['卡上余额']}" ?>" />
	  	</label>

		<label><!-- 水费查询部分结构 -->
			<span>水费</span><!--  -->
			<input readonly="readonly"value="<?php echo "{$ans['水费余额']}" ?>" />
		</label>
        
        <label><!-- 寝室用电余额部分结构 -->
			<span>寝室用电余额</span><!-- 问题部分填字 -->
            <input readonly="readonly"value="<?php echo "{$ans['剩余电量']}" ?>" />
		</label>
    	<label>
		<input type='button' onclick="window.location.href='chaxun.html'" value="确定"/>
    	</label>
</div>
</body>
    	
</html>
