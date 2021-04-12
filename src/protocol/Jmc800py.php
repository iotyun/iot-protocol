<?php

namespace iot\iotprotocol\protocol;

class Jmc800py
{
	public function registerAddress() 
	{
		$register = array(
			0x0A => array(0x0A, 10, Ua, Float, 2, "三相相电压数据,单位 V *：只有在三相四线接法时有效， 三相三线接法中数据无效。"),
			0x0C => array(0x0A, 12, Ua, Float, 2, "三相相电压数据,单位 V *：只有在三相四线接法时有效， 三相三线接法中数据无效。"),
			0x0E => array(0x0E, 14, Ua, Float, 2, "三相相电压数据,单位 V *：只有在三相四线接法时有效， 三相三线接法中数据无效。"),
			0x10 => array(0x10, 16, Ua, Float, 2, "三相线电压数据,单位 V"),
			0x12 => array(0x12, 16, Ua, Float, 2, "三相线电压数据,单位 V"),
			0x14 => array(0x14, 18, Ua, Float, 2, "三相线电压数据,单位 V"),
		);
		return json_encode($register);


// 0x16	22	Ia	Float	2	三相电流数据,单位 A
// 0x18	24	Ib	Float	2	
// 0x1A	26	Ic	Float	2	
// 0x1C	28	Pa	Float	2	分相和总的有功功率，单位W 
// NOTE： 有功功率数据带符号，“+”表示负载消耗电能， “-”表示负载发电。一般情况下当接线错误时，有功功率为“-” 。
// 0x1E	30	Pb	Float	2	
// 0x20	32	Pc	Float	2	
// 0x22	34	P∑	Float	2	
// 0x24	36	Qa	Float	2	分相和总的无功功率，单位var 
// NOTE： 无功功率数据带符号，“+”表示感性负载， “-”表示容性负载。
// 0x26	38	Qb	Float	2	
// 0x28	40	Qc	Float	2	
// 0x2A	42	Q∑	Float	2	
// 0x2C	44	S∑	Float	2	总视在功率KVA
// 0x2E	46	cosQ	Float	2	功率因素0~1.00
// 0x30	48	F	Float	2	电压频率Hz
// 0x32	50	Ep+	Float	2	正向有功电能，单位kWh
// 0x34	52	Ep-	Float	2	反向有功电能(双向计量电能)
// 0x36	54	Eq+	Float	2	感性有功电能，单位kvarh
// 0x38	56	Eq-	Float	2	容性无功电能，单位kvarh
// 0x3A	58	cosQ_A	Float	2	A相功率因数,格式 X*1000
// 0x3C	60	cosQ_B	Float	2	B相功率因数,格式 X*1000
// 0x3E	62	cosQ_C	Float	2	C相功率因数,格式 X*1000
// 0x40	64	SA	Float	2	A相视在功率,单位VA
// 0x42	66	SB	Float	2	B相视在功率,单位VA
// 0x44	68	SC	Float	2	C相视在功率,单位VA
// 二次电网数据(int/long整型数据)
// 0x46	70	Ua	Int	1	三相相电压数据,单位 0.1V NOTE：只有在三相四线接法时有效， 在三相三线接法中数据无效。
// 0x47	71	Ub	Int	1	
// 0x48	72	Uc	Int	1	
// 0x49	73	Uab	Int	1	三相相电压数据,单位 0.1V
// 0x4A	74	Ubc	Int	1	
// 0x4B	75	Uca	Int	1	
// 0x4C	76	Ia	Int	1	三相相电流数据,单位 0.001A
// 0x4D	77	Ib	Int	1	
// 0x4E	78	Ic	Int	1	
// 0x4F	79	Pa	Int	1	分相和总的有功功率，单位W 
// NOTE： 有功功率数据带符号，“+”表示负载消耗电能，“-”表示负载发电。一般情况下当接线错误时，有功功率为“-” 。
// 0x50	80	Pb	Int	1	
// 0x51	81	Pc	Int	1	
// 0x52	82	∑P	Int	1	
// 0x53	83	Qa	Int	1	分相和总的无功功率，单位var 
// NOTE： 无功功率数据带符号，
// “+”表示感性负载， 
// “-”表示容性负载。
// 0x54	84	Qb	Int	1	
// 0x55	85	Qc	Int	1	
// 0x56	86	∑Q	Int	1	
// 0x57	87	Sa	Int	1	分相和总的视在功率，单位VA
// 0x58	88	Sb	Int	1	
// 0x59	89	Sc	Int	1	
// 0x5A	90	∑S	Int	1	
// 0x5B	91	cosQ	Int	1	功率因数0~1000，固定格式1.000
// 0x5C	92	F	Int	1	频率，单位 0.01Hz
// 0x5D	93	Ep+	long	2	正向有功电能，单位Wh
// 0x5F	  95	Ep-	long	2	反向有功电能，单位Wh
// 0x61	97	Eq+	long	2	感性无功电能，单位varh
// 0x63	99	Eq-	long	2	容性无功电能，单位varh
// 0x65	101	Umax	Int	1	电压最大需量，0.1V
// 0x66	102	Umax	Int	1	电流最大需量，0.001A
// 0x67	103	Umax	Int	1	有功功率最大需量，W
// 0x68	104	Umax	Int	1	无功功率最大需量，Var
// 0x69	105	Id	Int	1	零序电流或漏电注，0.001A
	// 保留				
// 0x6E	110	THD-Ua	Int	1	A 相电压总谐波含量，0.01%
// 0x6F	111	THD-Ua	Int	1	B相电压总谐波含量，0.01%
// 0x70	112	THD-Uc	Int	1	C相电压总谐波含量，0.01%
// 0x71	113	THD-Ia	Int	1	A相电流总谐波含量，0.01%
// 0x72	114	THD-Ib	Int	1	B相电流总谐波含量，0.01%
// 0x73	115	THD-Ic	Int	1	C相电流总谐波含量，0.01%
	// 保留				
// 电表设置参数
// 0x12C	300	编程设置密码	Int	1	1-9999
// 0x12D	301	仪表通讯地址	Int	1	1-247
// 0x12E	302	电压倍率	Int	1	PT=1-5000
// 0x12F	303	电流倍率	Int	1	CT=1-5000
// 0x130	304	通讯波特率	Int	1	0-1200；1-2400；2-4800；3-9600；4-19200
// 0x131	305	通讯数据格式	Int	1	0-N.8.1 1-O.8.1 2-E.8.1
// 0x132	306	接线方式	Int	1	0-三相四线；1-三相三线
// 0x133	307	电压量程	Int	1	0-100V;1-220V;2-380V
// 0x134	308	电流量程	Int	1	0-5A;1-1A
// 扩展参数（读）
// 0x136	310	DO	Int	1	继电器输出状态 Bit0~3第1~4 路输出状态
// 0x137	311	DI	Int	1	开关量输入信息 Bit0~3第 1~4 路开入状态
// 0x138	312	AO-1	Int	1	4路模拟量输出值 ， 单位0.01mA
// 0x139	313	AO-2	Int	1	
// 0x13A	314	AO-3	Int	1	
// 0x13B	315	AO-4	Int	1	
// 0x140	320	AO1-TYPE	Int	1	模拟量输出1数据项和模式（0~52）
// 0x141	321	AO1-HI	Int	1	模拟量输出1高端
// 0x142	322	AO1-LO	Int	1	模拟量输出1低端
// 0x143	323	AO2-TYPE	Int	1	模拟量输出2数据项和模式（0~52）
// 0x144	324	AO2-HI	Int	1	模拟量输出2高端
// 0x145	325	AO2-LO	Int	1	模拟量输出2低端
// 0x146	326	AO3-TYPE	Int	1	模拟量输出3数据项和模式（0~52）
// 0x147	327	AO3-HI	Int	1	模拟量输出3高端
// 0x148	328	AO3-LO	Int	1	模拟量输出3低端
// 0x149	329	AO4-TYPE	Int	1	模拟量输出4数据项和模式（0~52）
// 0x14A	330	AO4-HI	Int	1	模拟量输出4高端
// 0x14B	331	AO4-LO	Int	1	模拟量输出4低端
// 0x14C	332	DO1-TYPE	Int	1	报警输出1数据项和模式（0~52）
// 0x14D	333	DO1-Value	Int	1	模拟量输出1门限值
// 0x14E	334	DO2-TYPE	Int	1	报警输出2数据项和模式（0~52）
// 0x14F	335	DO2-Value	Int	1	模拟量输出2门限值
// 0x150	336	DO3-TYPE	Int	1	报警输出3数据项和模式（0~52）
// 0x151	337	DO3-Value	Int	1	模拟量输出3门限值
// 0x152	338	DO4-TYPE	Int	1	报警输出4数据项和模式（0~52）
// 0x153	339	DO4-Value	Int	1	模拟量输出4门限值
// 所有参数设置地址(写)
// 0x3E8	1000	编程设置密码	Int	1	1-9999
// 0x3E9	1001	仪表通讯地址	Int	1	1-247
// 0x3EA	1002	电压倍率	Int	1	PT=1-5000
// 0x3EB	1003	电流倍率	Int	1	CT=1-5000
// 0x3EC	1004	通讯波特率	Int	1	0-1200；1-2400；2-4800；3-9600；4-19200
// 0x3ED	1005	通讯数据格式	Int	1	数据格式0-N.8.1 1-O.8.1 2-E.8.1
// 0x3EE	1006	接线方式	Int	1	0-三相四线；1-三相三线
// 0x3F1	1009	AO1-TYPE	Int	1	模拟量输出1数据项和模式（0~52）
// 0x3F2	1010	AO1-HI	Int	1	模拟量输出1高端
// 0x3F3	1011	AO1-LO	Int	1	模拟量输出1低端
// 0x3F4	1012	AO2-TYPE	Int	1	模拟量输出2数据项和模式（0~52）
// 0x3F5	1013	AO2-HI	Int	1	模拟量输出2高端
// 0x3F6	1014	AO2-LO	Int	1	模拟量输出2低端
// 0x3F7	1015	AO3-TYPE	Int	1	模拟量输出3数据项和模式（0~52）
// 0x3F8	1016	AO3-HI	Int	1	模拟量输出3高端
// 0x3F9	1017	AO3-LO	Int	1	模拟量输出3低端
// 0x3FA	1018	AO4-TYPE	Int	1	模拟量输出4数据项和模式（0~52）
// 0x3FB	1019	AO4-HI	Int	1	模拟量输出4高端
// 0x3FC	1020	AO4-LO	Int	1	模拟量输出4低端
// 0x3FD	1021	DO1-TYPE	Int	1	报警输出1数据项和模式（0~52）
// 0x3FE	1022	DO1-Value	Int	1	模拟量输出1门限值
// 0x3FF	1023	DO2-TYPE	Int	1	报警输出2数据项和模式（0~52）
// 0x400	1024	DO2-Value	Int	1	模拟量输出2门限值
// 0x401	1025	DO3-TYPE	Int	1	报警输出3数据项和模式（0~52）
// 0x402	1026	DO3-Value	Int	1	模拟量输出3门限值
// 0x403	1027	DO4-TYPE	Int	1	报警输出4数据项和模式（0~52）
// 0x404	1028	DO4-Value	Int	1	模拟量输出4门限值
		
		
		
		
		
		
	}
	
	
}
