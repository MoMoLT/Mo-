<?php

// -------------------执行主体部分--------------------------------------------------
header("Content-Type:text/html; charset=utf-8"); //添加这行解决乱码

// 定义token
define("TOKEN", "test");
// 打开文本logs.txt
$file = fopen("logs.txt", "a+");
$wechatObj = new wechatCallbackapiTest();

if(isset($_GET['echostr'])){
    $wechatObj->valid();
}else
{
    $wechatObj->responseMsg();
}


class wechatCallbackapiTest
{
//------------------微信接口验证-------------------------------------------------------
    // 接口验证主体函数
    public function valid()
    {
        $echoStr = $_GET["echostr"];        // 从微信用户端获取一个随机字符赋予变量echostr
        
        // 验证signature（签名）操作
        
        if($this->checkSignature())
        {   // 如果签名一致，输出变量echostr，完整验证配置接口的操作
            header('content-type:text');    // 再添加这行,解决中文编码问题
            echo $echoStr;                  // 输出echoStr变量
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

//======================api接口调用=======================
    // 有道云翻译api接口,参数分别为应用ID,应用密钥，要翻译的文本
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
        
        // 读取文件
        $trainStr = file_get_contents($trainUrl);
        // JSON解析
        $trainSon = json_decode($trainStr);
        // 提取内容
        $contentStr = $trainSon->translation[0];
        
        return $contentStr;
    }
    
//=======================================微信消息处理=======================================
    // 自动回复消息函数
    public function responseMsg()
    {
        // 获取微信用户端发来的信息，不同的环境可能由差异。我草泥马的，该死的$GLOBALS搞了半天原来是有风险，php.ini的register_globals参数被关了
        //$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = file_get_contents("php://input");
        // 若有消息发来，解析用户数据
        if(!empty($postStr))
        {
            // 将$postStr进行解析，simplexml_load_string是php中一个解析XML的函数
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            // 获取消息类型
            $type = trim($postObj->MsgType);
            switch($type)
            {
                // 处理事件，如菜单点击，新关注的客户
                case "event":
                    $this->receiveEvent($postObj);
                    break;
                    
                // 处理客户的消息
                case "text":
                    $this->receiveText($postObj);
                    break;
                // 其他处理
                default:
                    $this->postText($postObj, "moren");
                    break;
            }
        }else fwrite($file, "is empty");
    }
    // 发送文本消息, 参数：用户端post的信息，发送的内容
    private function postText($postObj, $contentStr)
    {
        // 回复的xml模版
        // 构建消息回复xml格式的文本
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        
        // 将xml格式中的变量分别赋值
        $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), "text", $contentStr);
        
        return $resultStr;
    }

    // 推送新闻, 参数：用户端post的信息，新闻标题，新闻描述，图片地址，新闻网页地址
    private function postNews($postObj, $title, $description, $picUrl, $url)
    {
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

        // 将xml格式中的变量分别赋值
        $resultStr = sprintf($newsTpl, $postObj->FromUserName, $postObj->ToUserName, time(), "news", $title, $description, $picUrl, $url);
        // 返回消息格式
        return $resultStr;
    }

    // 处理客户消息函数
    private function receiveText($postObj)
    {
		$file = fopen("logs.txt", "a+");
		$keyword = trim($postObj->Content);
		fwrite($file, $keyword);
        // 图灵聊天机器人,接入接口
        $chaturl1 = "http://www.tuling123.com/openapi/api?key=b1e21e4543ae43e2b6e4fde713bc008d";
        $info = "&info='{$keyword}'";
		fwrite($file, $contentStr);
        // 把空格用%20代替， url一般自动转空格为%20，但这是代码操作，没有自动转%20，会出错
        $info = str_replace(" ", "%20", $info);
		
        $chaturl = $chaturl1.$info;
		
        // 读取文件
        $chatstr = file_get_contents($chaturl);
        // JSON解析
        $chatjson = json_decode($chatstr);
        // 回复的内容
        $contentStr = $chatjson->text;
        
        // 规范化发送消息的格式
        $resultStr = $this->postText($postObj, $contentStr);
        // 发送消息
        echo $resultStr;
    }

    // 处理事件函数
    private function receiveEvent($postObj)
    {
        $contentStr = "没触发事件，很奇怪";
        $flag = 1;
        // 通过post参数中的Event,判断触发的事件， 然后构建发送的消息contentStr
        switch ($postObj->Event)
        {
            // 当新客户关注时
            case "subscrib":
                $contentStr = "感谢您的关注";
                // 规范化发送消息的格式
                $resultStr = $this->postText($postObj, $contentStr);
                break;
            // 当客户取消关注时
            case "unsubscribe":
                break;
            
            // 点击菜单时
            case "CLICK":
                switch ($postObj->EventKey)
                {
                    // 点击充值功能时
                    case "chongzhi":
                        $contentStr = "正在开发充值页面";
                        // 规范化发送消息的格式
                        $resultStr = $this->postText($postObj, $contentStr);
                        break;
                    // 点击查询功能时
                    case "chaxun":
                        $contentStr = "正在开发查询页面";
                        // 规范化发送消息的格式
                        $resultStr = $this->postText($postObj, $contentStr);
                        break;
                    // 点击联系方式时
                    case "phone":
                        $contentStr = "我的电话是17307402811";
                        // 规范化发送消息的格式
                        $resultStr = $this->postText($postObj, $contentStr);
                        break;
                    // 点击反馈问题功能时
                    case "wenti":
                        $flag = 2;
                        $contentStr = "正在开发反馈页面";
                        
                        $picUrl = "http://1.xyczapp.applinzi.com/html/images/wenti.jpg";
                        $url = "http://1.xyczapp.applinzi.com/html/wenti.html";
                        $resultStr = $this->postNews($postObj, "问题反馈", "写下你遇到的问题，我们将很快解决你的烦恼", $picUrl, $url);
                        break;
                    default:
                        $contentStr = "无此功能";
                        // 规范化发送消息的格式
                        $resultStr = $this->postText($postObj, $contentStr);
                        break;
                }
                break;
            // 触发其他事件时
            default:
                $contentStr = "还无次事件";
                // 规范化发送消息的格式
                $resultStr = $this->postText($postObj, $contentStr);
                break;
        }
        // 发送消息
        echo $resultStr;
    }
}
?>