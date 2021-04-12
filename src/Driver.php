<?php

namespace iotyun\iotprotocol;

use think\facade\Log;

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
		$uid = Gateway::bindUid($client_id, bin2hex($data));
		$driver = $this->getRegisterInfo(hex2bin($uid));
		Log::channel('iottcp')->info($driver_json);	//解析信息记入日志
        // $app = new Application;
        // $app->initialize();
		// $appid = $data[0] . $data[1];
		// $pid = $data[2] . $data[3];
		$driver = hexdec(bin2hex($data[0]));
		$function_code = hexdec(bin2hex($data[1]));
		$productid = hexdec(bin2hex($data[2] . $data[3]));
		$driverid = hexdec(bin2hex($data[4] . $data[5] . $data[6] . $data[7]));
		$data_array = array('appid' => $appid, 'productid' => $productid, 'driverid' => $driverid);
		return json_encode($data_array);
    }
}
