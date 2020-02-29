<?php
// 如果能够从post参数中，得到code，则给微信端发送code
if (isset($_GET['code'])) {
	echo $_GET['code'];
} else {
	echo "NO CODE";
}

url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx550e060d8a71a23b&redirect_uri=http://1.xyczapp.applinzi.com/html/wenti.html&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
?>

https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx550e060d8a71a23b&secret=1bb2bacbb62a08f2778b4a86b374c7f3

15_-2IWnlHSnoOjvx42RVThTXwNuEwc3DnfdFQYcCQzo4wujAQuKMfNZQh0DgWCapYh1OsWhsCbemuFM43QUzf6p98Bs6AMWirYrC5BsCVboRLvJn238xgVJCnn6uPHwjsC7hBjhp1Hr-oCXrlgWAVfAGAPTO


{
	"button": 
	[
		{
			"name": "功能",
			"sub_button": 
			[
				{
					"type": "view",
					"name": "充值",
					"url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx550e060d8a71a23b&redirect_uri=http://47.107.187.170/pay/&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
				},
				{
					"type": "view",
					"name": "查询",
					"url" :"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx550e060d8a71a23b&redirect_uri=http://47.107.187.170/query/&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
				}
			]
		},
		{
			"name": "联系",
			"sub_button":
			[
				{
					"type": "click",
					"name": "联系方式",
					"key" : "phone"
				},
				{
					"type": "view",
					"name": "问题反馈",
					"url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx550e060d8a71a23b&redirect_uri=http://47.107.187.170/question/&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
				}
			]
		}
	]
}