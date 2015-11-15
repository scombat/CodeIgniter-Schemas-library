<?php
defined('BASEPATH') OR exit('Nodirectscriptaccessallowed');

/*
|-------------------------------------------------------------------
|SchemaConfig
|-------------------------------------------------------------------
|AlibraryBasicGoogle&Schema.orgstructureddata
|
|-------------------------------------------------------------------
|EXPLANATION
|-------------------------------------------------------------------
|
|	Seehttps://developers.google.com/structured-data/
|	formoredetailsandexplainations
|
|
|
*/

$config_schema = array();

$config_schema["default"] = array(

	'encoded'					=>		true,

	'allowedContactTypes'		=>		array(
			"customer support",
			"technical support",
			"billing support",
			"bill payment",
			"sales",
			"reservations",
			"credit card support",
			"emergency",
			"baggage tracking",
			"roadside assistance",
			"package tracking"
	),

	"allowedContactOption"		=>		array(
			"TollFree",
			"HearingImpairedSupported"
	),

	'allowedAreasServed'		=>		array(
			"AA","AB","AC1","AD","AE","AF","AG","AH","AI2","AJ","AK","AL","AM","AN3","AO","AP4","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ5","BR","BS","BT","BU6","BV","BW","BX4","BY7","BZ","CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP8","CQ","CR","CS9","CT10","CU","CV","CW11","CX","CY","CZ","DA","DB","DC","DD12","DE","DF","DG8","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY13","DZ","EA14","EB","EC","ED","EE","EF4","EG","EH","EI","EJ","EK","EL","EM4","EN","EO","EP4","EQ","ER","ES","ET","EU15","EV4","EW16","EX","EY","EZ17","FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL18","FM","FN","FO","FP","FQ19","FR","FS","FT","FU","FV","FW","FX20","FY","FZ","GA","GB","GC4","GD","GE21","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ","HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV22","HW","HX","HY","HZ","IA","IB4","IC14","ID","IE","IF","IG","IH","II","IJ","IK","IL","IM","IN","IO","IP","IQ","IR","IS","IT","IU","IV","IW","IX","IY","IZ","JA13","JB1","JC","JD","JE","JF","JG","JH","JI","JJ","JK","JL","JM","JN","JO","JP","JQ","JR","JS","JT23","JU","JV","JW","JX","JY","JZ","KA","KB","KC","KD","KE","KF","KG","KH","KI","KJ","KK","KL","KM","KN","KO","KP","KQ","KR","KS","KT","KU","KV","KW","KX","KY","KZ","LA","LB","LC","LD","LE","LF24","LG","LH","LI","LJ","LK","LL","LM","LN","LO","LP","LQ","LR","LS","LT","LU","LV","LW","LX","LY","LZ","MA","MB","MC","MD","ME","MF","MG","MH","MI25","MJ","MK","ML","MM","MN","MO","MP","MQ","MR","MS","MT","MU","MV","MW","MX","MY","MZ","NA","NB","NC","ND","NE","NF","NG","NH26","NI","NJ","NK","NL27","NM","NN","NO","NP","NQ28","NR","NS","NT29","NU","NV","NW","NX","NY","NZ","OA4","OB","OC","OD","OE","OF","OG","OH","OI","OJ","OK","OL","OM","ON","OO","OP","OQ","OR","OS","OT","OU","OV","OW","OX","OY","OZ","PA","PB","PC30.","PD","PE","PF","PG","PH","PI31","PJ","PK","PL","PM","PN","PO","PP","PQ","PR","PS","PT","PU32.","PV","PW","PX","PY","PZ33","QA","QB","QC","QD","QE","QF","QG","QH","QI","QJ","QK","QL","QM","QN","QO","QP","QQ","QR","QS","QT","QU","QV","QW","QX","QY","QZ","RA34","RB35","RC36","RD","RE","RF","RG","RH37","RI38","RJ","RK","RL39","RM40","RN41","RO","RP42","RQ","RR","RS","RT","RU","RV","RW","RX","RY","RZ","SA","SB","SC","SD","SE","SF43","SG","SH","SI","SJ","SK44","SL","SM","SN","SO","SP","SQ","SR","SS45","ST","SU46","SV","SW","SX47","SY","SZ","TA1","TB","TC","TD","TE","TF","TG","TH","TI","TJ","TK","TL","TM","TN","TO","TP48","TQ","TR","TS","TT","TU","TV","TW","TX","TY","TZ","UA","UB","UC","UD","UE","UF","UG","UH","UI","UJ","UK49","UL","UM","UN50","UO","UP","UQ","UR","US","UT","UU","UV","UW","UX","UY","UZ","VA","VB","VC","VD51","VE","VF","VG","VH","VI","VJ","VK","VL","VM","VN","VO","VP","VQ","VR","VS","VT","VU","VV","VW","VX","VY","VZ","WA","WB","WC","WD","WE","WF","WG52","WH","WI","WJ","WK53","WL52","WM","WN","WO4","WP","WQ","WR","WS","WT","WU","WV52","WW","WX","WY","WZ","XA","XB","XC","XD","XE","XF","XG","XH","XI","XJ","XK","XL","XM","XN","XO","XP","XQ","XR","XS","XT","XU","XV","XW","XX","XY","XZ","YA","YB","YC","YD54","YE","YF","YG","YH","YI","YJ","YK","YL","YM","YN","YO","YP","YQ","YR","YS","YT","YU55","YV56","YW","YX","YY","YZ","ZA","ZB","ZC","ZD","ZE","ZF","ZG","ZH","ZI","ZJ","ZK","ZL","ZM","ZN","ZO","ZP","ZQ","ZR57","ZS","ZT","ZU","ZV","ZW","ZX","ZY","ZZ"
	),

	'allowedContactLanguages'	=>		array(
			"mandarin","spanish","english","hindi","arabic","portuguese","bengali","russian","japanese","punjabi","german","javanese","wu","malay/indonesian","telugu","vietnamese","korean","french","marathi","tamil","urdu","turkish","italian","yue","thai","gujarati","jin","southernmin","persian","polish","pashto","kannada","xiang","malayalam","sundanese","tamazight","hausa","odia","burmese","hakka","ukrainian","bhojpuri","tagalog","yoruba","maithili","uzbek","sindhi","amharic","fula","romanian","oromo","igbo","azerbaijani","awadhi","ganchinese","cebuano","dutch","kurdish","serbo-croatian","malagasy","saraiki","nepali","sinhalese","chittagonian","zhuang","khmer","turkmen","assamese","madurese","somali","marwari","magahi","haryanvi","hungarian","chhattisgarhi","greek","chewa","deccan","akan","kazakh","northernmin","sylheti","zulu","czech","kinyarwanda","dhundhari","haitiancreole","easternmin","ilocano","quechua","kirundi","swedish","hmong","shona","uyghur","hiligaynon","mossi","xhosa","belarusian","balochi","konkani"
	),
);

$config['schemas'] = $config_schema['default'];

/*Endoffileschemas.php*/
/*Location:./application/config/schemas.php*/