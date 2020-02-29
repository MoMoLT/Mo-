<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091900547320",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA11a4czrq/YiiZMVrrHvsY0ssOIJfE3ov4VCBFAIMI4Npm1UGCCb7ezxOMbRJqDqOP9jPRUGOTtdpCHNhwhOZgq+tsb+T9rr5TQo96lZI7PKFQd+e8zUqGVgQsU26Vi+mTnDUZelvIINF1AjKq8gqDzN9MN2vyCfQY+1Licajg5HoY8VdM/BFJJ7D4HXPyTCJY/kpPZKIXK0H/DRYBIXNiFKiT49Ncm4bzd6RgMFI3mXdNa70v6X63dOhiCXSL14itwdJUTPj0VfsuoqrWHCx3E6xTMTChS5X+DUL9yTT0Jh0Dfl4YBH7QBC70o7iiZ7/26SRFeEaQ1t1jfuu92Ue3wIDAQAB",
		
		//异步通知地址
		'notify_url' => "http://47.107.187.170/alipay/notify_url.php",
		
		//同步跳转
		'return_url' => "http://47.107.187.170/alipay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAq5gv0aYN8L3xm1kalZ6Mob2J5TaHSHcQRkjqRiad5RhNl7VkGmeUuzpm/TkMU5WfkeCnz4LtbIaPj8J0DreYZIASEQKFQqxQ2j2nJX39s6EDY/6tCdhjl2108kvnAGQvOKdkdrKHh4os1BFkKHecFYg0GWm9NBzIifzj3GmXxgKeZ4RdvvawZ3CM0pSqq2nq0JoWWMIoNi5bDuvX7iodlsZgMC2wYFSACCTLlf5kGXLrmPxaKxzShRaJdc0D/Ur1pFKJt4+JcFn6iPh5eyGvECJhtk8U9525yH5AQGQmT9F31PC5vB8WManqcSFuQR2dWWHAIskY/1AJKXsqHqm1hQIDAQAB",
		
	
);