<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use YellowProject\LineEmoticon;

class LineEmoticonSeeder extends Seeder
{
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->addlineEmoticon();
        Model::reguard();
    }

    private function addlineEmoticon()
    {
    	$datas = [
	    	'0x100078' => 'emoji_001',
			'0x100079' => 'emoji_002',
			'0x10007A' => 'emoji_003',
			'0x10007B' => 'emoji_004',
			'0x10007C' => 'emoji_005',
			'0x10007D' => 'emoji_006',
			'0x10007E' => 'emoji_007',
			'0x10008C' => 'emoji_008',
			'0x10008D' => 'emoji_009',
			'0x10008E' => 'emoji_010',
			'0x10008F' => 'emoji_011',
			'0x100090' => 'emoji_012',
			'0x100091' => 'emoji_013',
			'0x100092' => 'emoji_014',
			'0x100093' => 'emoji_015',
			'0x100094' => 'emoji_016',
			'0x100095' => 'emoji_017',
			'0x10007F' => 'emoji_018',
			'0x100080' => 'emoji_019',
			'0x100081' => 'emoji_020',
			'0x100082' => 'emoji_021',
			'0x100083' => 'emoji_022',
			'0x100096' => 'emoji_023',
			'0x100097' => 'emoji_024',
			'0x100098' => 'emoji_025',
			'0x100099' => 'emoji_026',
			'0x10009A' => 'emoji_027',
			'0x10009B' => 'emoji_028',
			'0x10009C' => 'emoji_029',
			'0x10009D' => 'emoji_030',
			'0x10009E' => 'emoji_031',
			'0x100084' => 'emoji_032',
			'0x100085' => 'emoji_033',
			'0x100086' => 'emoji_034',
			'0x100087' => 'emoji_035',
			'0x100088' => 'emoji_036',
			'0x100089' => 'emoji_037',
			'0x10008A' => 'emoji_038',
			'0x10008B' => 'emoji_039',
			'0x10009F' => 'emoji_040',
			'0x100001' => 'emoji_041',
			'0x100002' => 'emoji_042',
			'0x100003' => 'emoji_043',
			'0x100004' => 'emoji_044',
			'0x100005' => 'emoji_045',
			'0x100006' => 'emoji_046',
			'0x100007' => 'emoji_047',
			'0x100008' => 'emoji_048',
			'0x100009' => 'emoji_049',
			'0x10000A' => 'emoji_050',
			'0x10000B' => 'emoji_051',
			'0x10000C' => 'emoji_052',
			'0x10000D' => 'emoji_053',
			'0x10000E' => 'emoji_054',
			'0x10000F' => 'emoji_055',
			'0x100010' => 'emoji_056',
			'0x100011' => 'emoji_057',
			'0x100012' => 'emoji_058',
			'0x100013' => 'emoji_059',
			'0x100014' => 'emoji_060',
			'0x100015' => 'emoji_061',
			'0x100016' => 'emoji_062',
			'0x100017' => 'emoji_063',
			'0x100018' => 'emoji_064',
			'0x100019' => 'emoji_065',
			'0x10001A' => 'emoji_066',
			'0x10001B' => 'emoji_067',
			'0x10001C' => 'emoji_068',
			'0x10001D' => 'emoji_069',
			'0x10001E' => 'emoji_070',
			'0x10001F' => 'emoji_071',
			'0x100020' => 'emoji_072',
			'0x100021' => 'emoji_073',
			'0x100022' => 'emoji_074',
			'0x100023' => 'emoji_075',
			'0x10005D' => 'emoji_076',
			'0x10005F' => 'emoji_077',
			'0x10005E' => 'emoji_078',
			'0x1000A0' => 'emoji_079',
			'0x1000A1' => 'emoji_080',
			'0x100024' => 'emoji_081',
			'0x1000A2' => 'emoji_082',
			'0x1000A3' => 'emoji_083',
			'0x1000A4' => 'emoji_084',
			'0x1000A5' => 'emoji_085',
			'0x1000A6' => 'emoji_086',
			'0x1000A7' => 'emoji_087',
			'0x100026' => 'emoji_088',
			'0x100027' => 'emoji_089',
			'0x100029' => 'emoji_090',
			'0x10002A' => 'emoji_091',
			'0x10002B' => 'emoji_092',
			'0x10002C' => 'emoji_093',
			'0x10002D' => 'emoji_094',
			'0x10002E' => 'emoji_095',
			'0x10002F' => 'emoji_096',
			'0x10003A' => 'emoji_097',
			'0x1000A8' => 'emoji_098',
			'0x1000A9' => 'emoji_099',
			'0x1000AA' => 'emoji_100',
			'0x1000AB' => 'emoji_101',
			'0x1000AC' => 'emoji_102',
			'0x100033' => 'emoji_103',
			'0x1000AD' => 'emoji_104',
			'0x100030' => 'emoji_105',
			'0x100031' => 'emoji_106',
			'0x100032' => 'emoji_107',
			'0x1000AE' => 'emoji_108',
			'0x100035' => 'emoji_109',
			'0x100036' => 'emoji_110',
			'0x100039' => 'emoji_111',
			'0x100037' => 'emoji_112',
			'0x100038' => 'emoji_113',
			'0x1000AF' => 'emoji_114',
			'0x1000B0' => 'emoji_115',
			'0x1000B1' => 'emoji_116',
			'0x1000B2' => 'emoji_117',
			'0x1000B3' => 'emoji_118',
			'0x10003B' => 'emoji_119',
			'0x10003C' => 'emoji_120',
			'0x10003D' => 'emoji_121',
			'0x1000B4' => 'emoji_122',
			'0x100040' => 'emoji_123',
			'0x100041' => 'emoji_124',
			'0x100042' => 'emoji_125',
			'0x100043' => 'emoji_126',
			'0x100044' => 'emoji_127',
			'0x100045' => 'emoji_128',
			'0x1000B5' => 'emoji_129',
			'0x100047' => 'emoji_130',
			'0x100049' => 'emoji_131',
			'0x10004A' => 'emoji_132',
			'0x10004B' => 'emoji_133',
			'0x10004C' => 'emoji_134',
			'0x10004D' => 'emoji_135',
			'0x10004E' => 'emoji_136',
			'0x10004F' => 'emoji_137',
			'0x100050' => 'emoji_138',
			'0x100051' => 'emoji_139',
			'0x100053' => 'emoji_140',
			'0x100054' => 'emoji_141',
			'0x100055' => 'emoji_142',
			'0x100056' => 'emoji_143',
			'0x1000B6' => 'emoji_144',
			'0x100057' => 'emoji_145',
			'0x100058' => 'emoji_146',
			'0x100059' => 'emoji_147',
			'0x1000B7' => 'emoji_148',
			'0x10005B' => 'emoji_149',
			'0x10005C' => 'emoji_150',
			'0x100060' => 'emoji_151',
			'0x100061' => 'emoji_152',
			'0x100062' => 'emoji_153',
			'0x1000B8' => 'emoji_154',
			'0x1000B9' => 'emoji_155',
			'0x100064' => 'emoji_156',
			'0x100065' => 'emoji_157',
			'0x100066' => 'emoji_158',
			'0x100067' => 'emoji_159',
			'0x100068' => 'emoji_160',
			'0x100069' => 'emoji_161',
			'0x10006A' => 'emoji_162',
			'0x10006B' => 'emoji_163',
			'0x10006C' => 'emoji_164',
			'0x10006D' => 'emoji_165',
			'0x10006E' => 'emoji_166',
			'0x10006F' => 'emoji_167',
			'0x100070' => 'emoji_168',
			'0x100071' => 'emoji_169',
			'0x100072' => 'emoji_170',
			'0x100073' => 'emoji_171',
			'0x100074' => 'emoji_172',
			'0x100075' => 'emoji_173',
			'0x100076' => 'emoji_174',
			'0x100077' => 'emoji_175',
    	];
    	$lineEmoticons = [];
    	foreach ($datas as $unicode => $file_name) {
   			$lineEmoticons = new LineEmoticon([
   				'unicode'    => $unicode,
   				'file_name'  => $file_name
   			]);
   			if($lineEmoticons) $lineEmoticons->save(); unset($lineEmoticons);
    	}
    }
}
