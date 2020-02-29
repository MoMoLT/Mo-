<?php

// -------------------执行主体部分--------------------------------------------------
header("Content-Type:text/html; charset=utf-8"); //添加这行解决乱码

// 定义token
define("TOKEN", "test");

$wechatObj = new wechatCallbackapiTest();
if(isset($_GET['echostr'])){
	$wechatObj->valid();
}else
{
	$wechatObj->responseMsg();
}




// ------------------类------------------------------------------------------------
class wechatCallbackapiTest
{
	// 写入日志
	public function server_log($log) {
		
		file_put_contents("log.txt", $log, FILE_APPEND);
	}
//------------------微信接口验证-------------------------------------------------------
	// 接口验证主体函数
	public function valid()
	{
		$echoStr = $_GET["echostr"];		// 从微信用户端获取一个随机字符赋予变量echostr
		
		// 验证signature（签名）操作
		
		if($this->checkSignature())
		{	// 如果签名一致，输出变量echostr，完整验证配置接口的操作
			header('content-type:text');	// 再添加这行,解决中文编码问题
			echo $echoStr;					// 输出echoStr变量
			exit;
		}
	}
	// 接口验证之判断签名函数
	private function checkSignature()
	{
		// 从用户端获取签名、时间戳、随机数，获取TOKEN
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = TOKEN;
		
		// 建立数组
		$tmpArr = array( $token, $timestamp, $nonce );
		// 进行字典序排序
		sort( $tmpArr,SORT_STRING );
		// 连接三个参数的字符串
		$tmpStr = implode( $tmpArr );
		// sha1加密
		$tmpStr = sha1( $tmpStr );
		
		// 数字签名判断
		if( $tmpStr == $signature){
			return true;
		}else{
			return false;
		}
	}
//-------------------------------------------------------------------------------

    // 【7】有道云翻译api接口,参数分别为应用ID,应用密钥，要翻译的文本
	private function trainsapi($appKey, $secretKey, $q)
	{
        // 加盐处理
        $salt = strval(rand(1, 65536));
        // md5加密处理，并大写，生成签名
        $tmpArr = array( $appKey, $q, $salt, $secretKey );
        $sign = implode( $tmpArr );
        $sign = strtoupper( md5($sign) );
        // api接口拼接起来
        $trainUrl = "http://openapi.youdao.com/api?appKey={$appKey}&q={$q}&from=auto&to=auto&salt={$salt}&sign={$sign}";
        
		//【7】读取文件
		$trainStr = file_get_contents($trainUrl);
		//【7】JSON解析
		$trainSon = json_decode($trainStr);
		//【7】提取内容
		$contentStr = $trainSon->translation[0];
        
        return $contentStr;
	}
    
//------------------------------------------------------------------------------
	// 自动回复消息函数
	public function responseMsg()
	{
		// 获取微信用户端发来的信息，不同的环境可能由差异。
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		
		// 若有消息发来，解析用户数据
		if(!empty($postStr))
		{
			// 将$postStr进行解析，simplexml_load_string是php中一个解析XML的函数
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			// 获取微信用户端的用户名OpenID
			$fromUsername = $postObj->FromUserName;
			// 获取我的微信公众账号ID
			$toUsername = $postObj->ToUserName;
			// 【后加入2】获取消息类型
			$type = $postObj->MsgType;
			// 【后加入2】事件推送格式中的，获取事件类型subscribe(订阅), unsubscribe(取消订阅)
			$customevent = $postObj->Event;
			// 【4】获取纬度信息
			$locX = $postObj->Location_X;
			// 【4】获取经度信息
			$locY = $postObj->Location_Y;
			// 获取用户发来的文本内容，去掉两边的空格
			$keyword = trim($postObj->Content);
			// 获取当前时间
			$time = time();
			
			// 构建消息回复xml格式的文本
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
			// 构建音乐回复xml格式的文本
			$musicTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							</Music>
							<FuncFlag>0</FuncFlag>
							</xml>";
							
			// 构建新闻回复xml格式的文本
			$newsTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>
							</Articles>
							<FuncFlag>0</FuncFlag>
							</xml>";
			
			// 判断收到的文本消息类型
			$flag = 0;
            
			switch($type)
			{
				// 收到关注消息
				case "event":
					if($customevent == "subscribe") $contentStr = 
					$contentStr = "感谢您的关注\n回复1 查看联系方式\n 回复2 查看最新消息\n 回复\"功能\"查看本微信功能\n 回复\"故事\" 收听故事音乐， 暂时没放到后台上\n 还可以发送图片，位置和链接给微信平台";
					break;
				
				// 收到图片消息
				case "image":
					$contentStr = "哇!很帮的一张图呢！";
					break;
				
				// 收到位置消息
				case "location":
					//【5】介入高德地图API，精确定位地址， 反Geocoding即逆地址编码接口
					$geourl = "https://restapi.amap.com/v3/geocode/regeo?output=xml&location={$locY},{$locX}&key=d49a2b6eaf269a5a737f47b5e04928c7&radius=100&extensions=all";
					//【5】$contentStr = "你的纬度是{$locX}, 经度是{$locY}, 我看到你了哦!";
					//【5】读取xml文件
					$apistr = file_get_contents($geourl);
					//【5】xml解析
					$apiobj = simplexml_load_string($apistr);
					//【5】逐级解析,获取内容
					$addstr1 = $apiobj->regeocode->pois->poi[0]->name;
					$addstr2 = $apiobj->regeocode->pois->poi[0]->address;
                    
					//【5】拼接信息
                    $addstrs = $addstr1.$addstr2;
					$contentStr = "哈哈，我知道你在哪了，你在{$addstrs}附近";
					break;
				
				// 收到链接消息
				case "link":
					$contentStr = "啊啊，你这是什么链接，我才不会看呢，嗯，绝不点开看，嗯，没错！";
					break;
				
				// 收到文本消息
				case "text":
					switch($keyword)
					{
						case "1": $contentStr = "联系方式：呵呵哒，不告诉你!"; break;
						case "2": $contentStr = "最新消息：我们的平台正在一步步进步哦！"; break;
						case "3": $contentStr = "没啦，没有这个选项啦!"; break;
						case "功能":
							$contentStr = "回复1 查看联系方式\n 回复2 查看最新消息\n 回复\"功能\"查看本微信功能\n 回复\"故事\" 收听故事音乐， 暂时没放到后台上\n 还可以发送图片，位置和链接给微信平台";
							break;
						case "故事":
							// flag设置为1，则使用音乐xml模板，发送消息
							$flag = 1;
							
							$msgType = "music";
							$title = "咕咚";
							$description = "鬼故事";
							$musicUrl = "http://1.weichatphp.applinzi.com/1.mp3";
							$hqMusicUrl = "http://1.weichatphp.applinzi.com/1.mp3";
							$resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType,
													$title, $description, $musicUrl, $hqMusicUrl);
							echo $resultStr;
							break;
							
						case "新闻":
							// 新闻回复xml格式的文本
							$flag = 2;
							
							$msgType = "news";
							$title = "美女来袭";
							$description = "满满的福利哦！";
							$picUrl = "http://1.weichatphp.applinzi.com/image/1.jpg";
							$url = "http://jandan.net/ooxx";
							$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType,
													$title, $description, $picUrl, $url);
							echo $resultStr;
							break;
                            
						default: 
							//【6】图灵聊天机器人,接入接口
							$chaturl1 = "http://www.tuling123.com/openapi/api?key=b1e21e4543ae43e2b6e4fde713bc008d";
							$info = "&info='{$keyword}'";
							// 把空格用%20代替， url一般自动转空格为%20，但这是代码操作，没有自动转%20，会出错
							$info = str_replace(" ", "%20", $info);
							
							$chaturl = $chaturl1. $info;
							
							//【6】读取文件
							$chatstr = file_get_contents($chaturl);
							//【6】JSON解析
							$chatjson = json_decode($chatstr);
							$contentStr = $chatjson->text;
							
							//$contentStr = "٩( 'ω' )و 我们的系统正在努力建设中，你刚才说的是: ".$chatjson->code;
							
							/*有道云翻译
							//【7】应用的ID
                            $appKey='6a9e9c3e18f195bd';
                            //【7】应用的密钥
                            $secretKey = 'uvBHoyZbWFaTJaaOsTgdmGggqixNGsKh';
                            $contentStr = $this->trainsapi($appKey, $secretKey, $keyword);
							*/
							break;
					}
					break; // switch($type)--->break;
					/*  数据库操作
					// 连接数据库
					$db = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
					if($db)
					{
						mysql_select_db(SAE_MYSQL_DB, $db);
						// 查询用户是否存在
						$sql = "select * from CRM where USER = '{$fromUsername}';";
						$query = mysql_query($sql);
						$rs = mysql_fetch_array($query);
						// 读取用户名
						$name = $rs['USER'];
						// 判断用户是否相同
						if($name == $fromUsername)
						{
							$contentStr = "欢迎老朋友！";
						}else
						{
							$sql = "insert into CRM(USRER) values ('{$fromUsername}');";
							mysql_query($sql);
							$contentStr = "欢迎新朋友!";
						}
						mysql_close($this->db);
					}else
					{
						echo "数据库连接失败";
					}
					break;
				*/
				
				// 收到其他类型消息
				default:
					//$contentStr = "稍等哦，此功能还没有开发呢!";
					$contentStr = $keyword;
			}
			// 回复消息，当flag为0时回复文本消息，否则不用管。
			if($flag) $flag = 0;
			else
			{
				// 回复的文本信息类型为text
				$msgType = "text";
				// 将XML格式中的变量分别赋值
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				// 发送微信
				echo $resultStr;
			}
		}else
		{
			echo "wrong input!";
			exit;
		}
	}
}
?>