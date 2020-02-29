<?php
// 获取提交的参数，即学号，和姓名
$name = $_POST['name'];
$phone = $_POST['phone'];
$question = $_POST['message'];

$isInsert = false;				// 是否插入成功
// 连接数据库
$db = mysqli_connect("localhost", "root", " ", "momodb");
// 如果数据库连接成功
if ($db) 
{
    mysqli_select_db($db, SAE_MYSQL_DB);				// 解决数据库信息中文乱码问题
	mysqli_query($db, "set names utf8");				// 解决数据库信息中文乱码问题
    // sql语句，查询学号和姓名
    $sql = "insert into questions(联系人, 联系方式, 问题) values('{$name}', '{$phone}', '{$question}');";
    // 执行sql语句, 如果插入成功,则设置插入标志为true
	if(mysqli_query($db, $sql))
	{
		$isInsert = true;
	}
	mysqli_close($db);
}
?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>用户反馈界面</title>
    
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
     
        .Content-Main textarea{
            width: 1200px;
            height: 320px;
            margin-right: 30px;
     
            background-color:#CCCCFF;
            padding: 10px 0px 0px 5px;
        }
        .button{                                              /*提交按钮*/
            padding: 20px 25px 20px 25px;
            width:900px;
            margin-left: 145px;
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
                <h1>反馈问题</h1>
            </div>

		<hr style="height:2px;width:1250px;border:none;border-top:3px dashed #6666CC;" />                                                                                        
        <label>                                                                                <!-- 问题部分结构 -->
            <textarea id="message" name="message" >
			<?php
			if($isInsert == true)
			{
				echo "\n\t\t\t问题已传达\n";

                echo "\t\t很快就会解决您的问题!";
			}else
			{
				echo "\n\t\t\t很遗憾!\n";
                echo "\t\t问题传达时不慎遗失~~";
			}
			?>
			</textarea>      <!--多行输入框-->
        </label>
       
        <label>
            <input type="button" onclick="window.location.href='wenti.html'" class="button" value="返回">   <!-- 提交按钮 -->
        </label>

</div>
</body>
</html>

