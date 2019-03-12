<?php

namespace YellowProject\GeneralFunction;
use YellowProject\LH\CouponTypeData;
use YellowProject\LH\CouponLocationData;
use YellowProject\LH\CouponProjectNameData;
use YellowProject\LH\CouponDiscountData;
use YellowProject\Subscriber;
use YellowProject\Campaign;
use YellowProject\SubscriberLine;
use YellowProject\SubscriberItem;
use YellowProject\LineUserProfile;
use YellowProject\Field;
use YellowProject\TrackingBc;
use YellowProject\TrackingRecieveBc;
use YellowProject\Campaign\CampaignSendMessage;
use YellowProject\RolePermission\RolePermission;
use YellowProject\RolePermission\RolePermissionItem;
use Illuminate\Database\Eloquent\Model;
use Excel;

class GenData extends Model
{
    public static function genLHCouponData()
    {
        // dd('kk');
        $couponTypeDataArrays = [
            ['name' => 'CONDO'],
            ['name' => 'TOWNHOME'],
            ['name' => 'SINGLE HOME']
        ];

        foreach ($couponTypeDataArrays as $key => $couponTypeDataArray) {
            CouponTypeData::create($couponTypeDataArray);
        }
        $couponLocationDataArrays = [
            /*1*/['coupon_type_data_id' => 1,'name' => 'กรุงเทพชั้นใน'],
            /*2*/['coupon_type_data_id' => 2,'name' => 'บางนา-ศรีนครินทร์'],
            /*3*/['coupon_type_data_id' => 2,'name' => 'พระราม2-ประชาอุทิศ-เพชรเกษม'],
            /*4*/['coupon_type_data_id' => 2,'name' => 'สุวรรณภูมิ-ศรีนครินทร์-ร่มเกล้า'],
            /*5*/['coupon_type_data_id' => 2,'name' => 'รามอินทรา-วัชรพล-เลียบด่วนดรามอินทรา'],
            /*6*/['coupon_type_data_id' => 2,'name' => 'รังสิต-ลำลูกกา'],
            /*7*/['coupon_type_data_id' => 3,'name' => 'รังสิต-ลำลูกกา'],
            /*8*/['coupon_type_data_id' => 3,'name' => 'บางนา-ศรีนครินทร์'],
            /*9*/['coupon_type_data_id' => 3,'name' => 'ราชพฤกษ์-ปิ่นเกล้า-ศาลายา'],
            /*10*/['coupon_type_data_id' => 3,'name' => 'รามอินทรา-วัชรพล-เลียบด่วนดรามอินทรา'],
            /*11*/['coupon_type_data_id' => 3,'name' => 'แจ้งวัฒนะ-ชัยพฤกษ์'],
            /*12*/['coupon_type_data_id' => 3,'name' => 'พระราม2-ประชาอุทิศ-ศาลายา'],
            /*13*/['coupon_type_data_id' => 3,'name' => 'สุวรรณภูมิ-ศรีนครินทร์-ร่มเกล้า'],
        ];
        foreach ($couponLocationDataArrays as $key => $couponLocationDataArray) {
            CouponLocationData::create($couponLocationDataArray);
        }
        $couponProjectNameDataArrays = [
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => '333 ริเวอร์ไซด์','price' => '(เริ่ม 6 ล้าน)'],
            // ['coupon_type_data_id' => 2,'coupon_location_data_id' => 2,'name' => 'Indy บางนา กม7(2)','price' => '(เริ่ม 3 ล้าน)'],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 3,'name' => 'Indy ประชาอุทิศ 90(3)','price' => '(เริ่ม 2.2 ล้าน)'],
            // ['coupon_type_data_id' => 2,'coupon_location_data_id' => 4,'name' => 'Indy ศรีนครินทร์-ร่มเกล้า','price' => '(เริ่ม 3 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 7,'name' => 'INIZIO2 รังสิต-คลอง 3','price' => '(เริ่ม 3.7 ล้าน)'],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 5,'name' => 'THE LANDMARK เอกมัย รามอินทรา','price' => '(เริ่ม 7 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'name' => 'Villaggio บางนา (บ้านเดี่ยว)','price' => '(เริ่ม 3.9 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 7,'name' => 'Villaggio รังสิต-คลอง 3 (บ้านเดี่ยว)','price' => '(เริ่ม 3.9 ล้าน)'],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 2,'name' => 'Villaggio บางนา (ทาวน์โฮม)','price' => '(เริ่ม 2 ล้าน)'],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 6,'name' => 'Villaggio รังสิต-คลอง 3 (ทาวน์โฮม)','price' => '(เริ่ม 2.2 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'name' => 'Villaggio ปิ่นเกล้า-ศาลายา','price' => '(เริ่ม 3.5 ล้าน)'],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => 'The Bangkok ทองหล่อ','price' => '(เริ่ม 30 ล้าน)'],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => 'The Bangkok สาทร','price' => '(เริ่ม 18 ล้าน)'],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => 'The Key สาทร-เจริญราษฎร์','price' => '(เริ่ม 3.9 ล้าน)'],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => 'The Room  เจริญกรุง 30','price' => '(เริ่ม 9 ล้าน)'],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => 'The Room สาทร-เซนต์หลุยส์','price' => '(เริ่ม 5.1 ล้าน)'],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'name' => 'The Room สุขุมวิท 69','price' => '(เริ่ม 7 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'name' => 'ชัยพฤกษ์ จตุโชติ-วัชรพล','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'name' => 'ชัยพฤกษ์ บางนา-กม.7','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'name' => 'ชัยพฤกษ์ ปิ่นเกล้า-กาญจนาฯ','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'name' => 'ชัยพฤกษ์ พุทธมณฑล-สาย 5','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'name' => 'ชัยพฤกษ์ รามอินทรา-พระยาสุเรนทร์','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'name' => 'ชัยพฤกษ์ ศรีนครินทร์','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'name' => 'นันทวัน บางนา กม.7','price' => '(เริ่ม 20 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'name' => 'นันทวัน ปิ่นเกล้า-ราชพฤกษ์','price' => '(เริ่ม 20 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 11,'name' => 'นันทวัน แจ้งวัฒนะ-ราชพฤกษ์','price' => '(เริ่ม 9 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'name' => 'พฤกษ์ลดา เพชรเกษม-สาย 4','price' => '(เริ่ม 4.2 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'name' => 'พฤกษ์ลดา ประชาอุทิศ 90','price' => '(เริ่ม 4.3 ล้าน)'],
            // ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'name' => 'พฤกษ์ลดา มหาชัย','price' => '(เริ่ม 3 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'name' => 'พฤกษ์ลดา วงแหวน-หทัยราษฎร์','price' => '(เริ่ม 4 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 13,'name' => 'พฤกษ์ลดา สุวรรณภูมิ','price' => '(เริ่ม 4 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'name' => 'มัณฑนา Lake Watcharapol','price' => '(เริ่ม 5.6 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'name' => 'มัณฑนา บางนา กม.7','price' => '(เริ่ม 7 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'name' => 'มัณฑนา ราชพฤกษ์-สะพานมหาเจษฎาบดินทร์ฯ','price' => '(เริ่ม 7.5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'name' => 'มัณฑนา วงแหวน-บางบอน','price' => '(เริ่ม 6 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'name' => 'มัณฑนา ศรีนครินทร์-บางนา','price' => '(เริ่ม 6 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 13,'name' => 'มัณฑนา ศรีนครินทร์-ร่มเกล้า','price' => '(เริ่ม 5 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'name' => 'LADAWAN พระราม 2','price' => '(เริ่ม 50 ล้าน)'],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'name' => 'LADAWAN ราชพฤกษ์-ปิ่นเกล้า','price' => '(เริ่ม 50 ล้าน)'],
        ];
        foreach ($couponProjectNameDataArrays as $key => $couponProjectNameDataArray) {
            CouponProjectNameData::create($couponProjectNameDataArray);
        }
        $couponDiscountDataArrays = [
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 1,'name' => 'ส่วนลด 100,000','discount' => 100000],
            // ['coupon_type_data_id' => 2,'coupon_location_data_id' => 2,'coupon_project_name_data_id' => 2,'name' => 'ส่วนลด 50,000','discount' => 50000],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 3,'coupon_project_name_data_id' => 3,'name' => 'ส่วนลด 50,000','discount' => 50000],
            // ['coupon_type_data_id' => 2,'coupon_location_data_id' => 4,'coupon_project_name_data_id' => 4,'name' => 'ส่วนลด 50,000','discount' => 50000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 7,'coupon_project_name_data_id' => 5,'name' => 'ส่วนลด 50,000','discount' => 50000],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 5,'coupon_project_name_data_id' => 6,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'coupon_project_name_data_id' => 7,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 7,'coupon_project_name_data_id' => 8,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 2,'coupon_project_name_data_id' => 9,'name' => 'ส่วนลด 50,000','discount' => 50000],
            ['coupon_type_data_id' => 2,'coupon_location_data_id' => 6,'coupon_project_name_data_id' => 10,'name' => 'ส่วนลด 50,000','discount' => 50000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'coupon_project_name_data_id' => 11,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 12,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 13,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 14,'name' => 'ส่วนลด 50,000','discount' => 50000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 15,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 15,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 16,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 1,'coupon_location_data_id' => 1,'coupon_project_name_data_id' => 17,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'coupon_project_name_data_id' => 18,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'coupon_project_name_data_id' => 19,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'coupon_project_name_data_id' => 20,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'coupon_project_name_data_id' => 21,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'coupon_project_name_data_id' => 22,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'coupon_project_name_data_id' => 23,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'coupon_project_name_data_id' => 24,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'coupon_project_name_data_id' => 25,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 11,'coupon_project_name_data_id' => 26,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'coupon_project_name_data_id' => 27,'name' => 'ส่วนลด 100,000','discount' => 100000],
            // ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'coupon_project_name_data_id' => 28,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'coupon_project_name_data_id' => 29,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'coupon_project_name_data_id' => 30,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 13,'coupon_project_name_data_id' => 31,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 10,'coupon_project_name_data_id' => 32,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'coupon_project_name_data_id' => 33,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 9,'coupon_project_name_data_id' => 34,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'coupon_project_name_data_id' => 35,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 8,'coupon_project_name_data_id' => 36,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 13,'coupon_project_name_data_id' => 37,'name' => 'ส่วนลด 100,000','discount' => 100000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'coupon_project_name_data_id' => 38,'name' => 'ส่วนลด 200,000','discount' => 200000],
            ['coupon_type_data_id' => 3,'coupon_location_data_id' => 12,'coupon_project_name_data_id' => 39,'name' => 'ส่วนลด 200,000','discount' => 200000],
        ];
        foreach ($couponDiscountDataArrays as $key => $couponDiscountDataArray) {
            CouponDiscountData::create($couponDiscountDataArray);
        }
    }

    public static function genSegmentData()
    {
        $dataExports = [];
        $arrayFieldEnums = ['yes','no','n/a'];
        $arrayFieldEnum2s = ['enum2_1','enum2_2','enum2_3','enum2_4'];
        $arrayFieldBooleans = ['yes','no'];
        $arrayFieldFavorites = ['favorite_A','favorite_B','favorite_C','favorite_D','favorite_E'];
        $subscriber = Subscriber::where('name','TEST-SEGMENT')->first();
        if($subscriber){
            foreach (range(0, 5000) as $index) {
                
                $faker = \Faker\Factory::create();
                $lineUserProfileArrays = [
                    'mid' => $faker->sha256,
                    'avatar' => $faker->imageUrl($width = 640, $height = 480),
                    'name' => $faker->firstName,
                    'user_type' => 'prospect',
                    'is_follow' => 1
                ];

                $lineUserProfile = LineUserProfile::create($lineUserProfileArrays);

                $dataExports[$index]['mid'] = $lineUserProfile->mid;
                $dataExports[$index]['display_name'] = $lineUserProfile->name;

                $subscriberLineArrays = [
                    'subscriber_id' => $subscriber->id,
                    'line_user_id' => $lineUserProfile->id
                ];

                $subscriberLine = SubscriberLine::create($subscriberLineArrays);

                $fieldName = Field::where('name','seg_name')->first();
                if($fieldName){
                    $value = $faker->firstName;
                    $dataExports[$index][$fieldName->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldName->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldLastName = Field::where('name','seg_lastname')->first();
                if($fieldLastName){
                    $value = $faker->lastName;
                    $dataExports[$index][$fieldLastName->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldLastName->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldAge = Field::where('name','seg_age')->first();
                if($fieldAge){
                    $value = rand(10,100);
                    $dataExports[$index][$fieldAge->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldAge->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldTel = Field::where('name','seg_tel')->first();
                if($fieldTel){
                    $value = $faker->tollFreePhoneNumber;
                    $dataExports[$index][$fieldTel->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldTel->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldEnum = Field::where('name','seg_enum')->first();
                if($fieldEnum){
                    $randNum = rand(0,2);
                    $value = $arrayFieldEnums[$randNum];
                    $dataExports[$index][$fieldEnum->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldEnum->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldBoolean = Field::where('name','seg_boolean')->first();
                if($fieldBoolean){
                    $randNum = rand(0,1);
                    $value = $arrayFieldBooleans[$randNum];
                    $dataExports[$index][$fieldBoolean->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldBoolean->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldFavorite = Field::where('name','seg_favorite')->first();
                if($fieldFavorite){
                    $randNum = rand(0,4);
                    $value = $arrayFieldFavorites[$randNum];
                    $dataExports[$index][$fieldFavorite->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldFavorite->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldBirthDay = Field::where('name','seg_birthday')->first();
                if($fieldBirthDay){
                    $value = $faker->dateTimeThisCentury->format('Y-m-d');
                    $dataExports[$index][$fieldBirthDay->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldBirthDay->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldTextArea = Field::where('name','seg_textarea')->first();
                if($fieldTextArea){
                    $value = $faker->text($maxNbChars = 200);
                    $dataExports[$index][$fieldTextArea->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldTextArea->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldDecimal = Field::where('name','seg_decimal')->first();
                if($fieldDecimal){
                    $value = $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL);
                    $dataExports[$index][$fieldDecimal->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldDecimal->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldEmail = Field::where('name','seg_email')->first();
                if($fieldEmail){
                    $value = $faker->email;
                    $dataExports[$index][$fieldEmail->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldEmail->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldBoolean2 = Field::where('name','seg_boolean_2')->first();
                if($fieldBoolean2){
                    $randNum = rand(0,1);
                    $value = $arrayFieldBooleans[$randNum];
                    $dataExports[$index][$fieldBoolean2->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldBoolean2->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldEnum2 = Field::where('name','seg_enum_2')->first();
                if($fieldEnum2){
                    $randNum = rand(0,3);
                    $value = $arrayFieldEnum2s[$randNum];
                    $dataExports[$index][$fieldEnum2->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldEnum2->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }

                $fieldTextArea2 = Field::where('name','seg_textarea2')->first();
                if($fieldTextArea2){
                    $value = $faker->text($maxNbChars = 200);
                    $dataExports[$index][$fieldTextArea2->field_name] = $value;
                    $subscriberItemArrays = [
                        'subscriber_line_id' => $subscriberLine->id,
                        'field_id' => $fieldTextArea2->id,
                        'value' => $value,
                    ];
                    SubscriberItem::create($subscriberItemArrays);
                }
            }

            Excel::create('faker_segment_data', function($excel) use ($dataExports) {
                $excel->sheet('sheet1', function($sheet) use ($dataExports)
                {
                    $sheet->fromArray($dataExports);
                });
            })->download('xls');
        }
    }

    public static function genCampaignSent()
    {
        $campaigns = Campaign::all();
        $lineUserProfiles = LineUserProfile::all();
        foreach (range(0, 200) as $index) {
            CampaignSendMessage::create([
                'campaign_id' => $campaigns->random()->id,
                'mid' => $lineUserProfiles->random()->mid,
                'ip' => $campaigns->random()->id,
                ipv4
            ]);
        }
    }

    public static function genRecieveBcActivity()
    {
        $trackingBcs = TrackingBc::all();
        $lineUserProfiles = LineUserProfile::all();
        foreach (range(0, 50) as $index) {
            $faker = \Faker\Factory::create();
            $trackingBc = $trackingBcs->random();
            TrackingRecieveBc::create([
                'tracking_bc_id' => $trackingBc->id,
                'line_user_id' => $lineUserProfiles->random()->id,
                'ip' => $faker->ipv4,
                'device' => "WebKit",
                'lat' => "",
                'long' => "",
                'city' => "Bangkok",
                'platform' => "Windows",
                'tracking_source' => $trackingBc->tracking_source,
                'tracking_campaign' => $trackingBc->tracking_campaign,
                'tracking_ref' => $trackingBc->tracking_ref,
            ]);
        }
    }

    public static function genDtRole()
    {
        $rolePermission = RolePermission::find(1000);
        if(!$rolePermission){
            RolePermission::create([
                'id' => 1000,
                'name' => 'DT'
            ]);

            RolePermissionItem::create([
                'permission_role_id' => 1000,
                'menu_id' => 88,
                'role_id' => 0,
                'access_id' => -1,
                'is_active' => 1
            ]);

            RolePermissionItem::create([
                'permission_role_id' => 1000,
                'menu_id' => 88,
                'role_id' => 0,
                'access_id' => 1,
                'is_active' => 1
            ]);

            RolePermissionItem::create([
                'permission_role_id' => 1000,
                'menu_id' => 88,
                'role_id' => 0,
                'access_id' => 2,
                'is_active' => 1
            ]);

            RolePermissionItem::create([
                'permission_role_id' => 1000,
                'menu_id' => 88,
                'role_id' => 0,
                'access_id' => 3,
                'is_active' => 1
            ]);

            RolePermissionItem::create([
                'permission_role_id' => 1000,
                'menu_id' => 88,
                'role_id' => 0,
                'access_id' => 4,
                'is_active' => 1
            ]);
        }
    }
}
