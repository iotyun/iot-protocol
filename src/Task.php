<?php

namespace iotyun\iotprotocol;

use think\facade\Log;
use GatewayWorker\Lib\Gateway;
use Workerman\Crontab\Crontab;
use Workerman\Worker;

class Task
{
	public static function setCrontab()
    {
		//$worker = new Worker();

		// 设置时区，避免运行结果与预期不一致
		date_default_timezone_set('PRC');
		$crontab = new Crontab('1 * * * * *', function(){
			//echo date('Y-m-d H:i:s')."\n";
			Log::channel('iottcp')->info('crontab ' . date('Y-m-d H:i:s'));
		});
		//Worker::runAll();
		Log::channel('iottcp')->info('setCrontab ' . date('Y-m-d H:i:s'));
		return $crontab;
	}
}
