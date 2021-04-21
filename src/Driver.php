<?php

namespace iotyun\iotprotocol;

use think\facade\Log;
use GatewayWorker\Lib\Gateway;
use iotyun\iotprotocol\Utils\Types;
use iotyun\iotprotocol\Utils\Crc16;

class Driver
{
	public static function getRegisterInfo($data)
    {
        // $app = new Application;
        // $app->initialize();
		// $appid = $data[0] . $data[1];
		// $pid = $data[2] . $data[3];
		$appid = hexdec(bin2hex($data[0] . $data[1]));
		$productid = hexdec(bin2hex($data[2] . $data[3]));
		$driverid = hexdec(bin2hex($data[4] . $data[5] . $data[6] . $data[7]));
		$data_array = array('appid' => $appid, 'productid' => $productid, 'driverid' => $driverid);
		return json_encode($data_array);
    }
	public static function getMessageInfo($client_id, $data)
    {
		$uid = Gateway::getUidByClientId($client_id);
		$driver = json_decode(self::getRegisterInfo(hex2bin($uid)));
		//Log::channel('iottcp')->info('getMessageInfo ' . $driver);	//解析信息记入日志
        // $app = new Application;
        // $app->initialize();
		// $appid = $data[0] . $data[1];
		// $pid = $data[2] . $data[3];
		$get_session = Gateway::getSession($client_id);
		$message = array(
			'addr' => hexdec(bin2hex($data[0])),
			'function_code' => hexdec(bin2hex($data[1])),
			'data_length' => hexdec(bin2hex($data[2]))
		);
		
		
		switch ($message['function_code'])
		{
			case 0x01:
				$code_info = "读寄存器输出状态";
				break;  
			case 0x02:
				$code_info = "读开关量输入状态";
				break;
			case 0x03:
				$code_info = "读数据寄存器值";
				for ($i = 0; $i < $message['data_length']; $i += 4)
				{
					$message['data'][] = array('register' => $get_session['register'] + ($i/2), 'value' => Types::parseFloat($data[3+$i] . $data[4+$i] . $data[5+$i] . $data[6+$i]));
				}
				
				
				break;
			case 0x04:
				$code_info = "读数据寄存器值";
				break;
			case 0x05:
				$code_info = "遥控单路继电器输出";
				break;
			case 0x0F:
				$code_info = "遥控多路继电器输出";
				break;
			case 0x10:
				$code_info = "写设置寄存器";
				break;
			default:
				$code_info = "不存在的命令";
				break;
		}
		
		$data_array = array('appid' => $driver->appid, 'productid' => $driver->productid, 'driverid' => $driver->driverid, 'code_info' => $code_info,  'message' => $message);
		return json_encode($data_array);
		//return $info;
    }
}
