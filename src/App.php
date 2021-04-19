<?php

namespace iotyun\iotprotocol;

use think\facade\Log;

class App
{
	public static function product() 
	{
		$app = array(
			['id' => 0x0001, 'name' => '远程抄表' , 'product' => array('Jmc800py' => 0x0001)],
		);
		return json_encode($app);
	}
	
	
}
