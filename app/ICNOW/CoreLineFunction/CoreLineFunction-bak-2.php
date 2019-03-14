<?php

namespace YellowProject\ICNOW\CoreLineFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineWebHooks;
use YellowProject\LineSettingBusiness;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\Mini\MiniUser;
use YellowProject\ICNOW\OrderCustomer\OrderCustomer;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;
use YellowProject\ICNOW\Product\ProductImages;
use YellowProject\ICNOW\OrderCustomer\OrderCustomerHistory;
use YellowProject\ICNOW\Mini\Mini;
use YellowProject\ICNOW\AdminUser\AdminUser;

class CoreLineFunction extends Model
{

  public static function getQueryDatas($orderCustomer,$section = null)
  {
    $orderCustomer = OrderCustomer::find($orderCustomer->id);
    if($section != null){
      $shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->where('section_id',$section)->get();
    }else{
      $shoppingCartItems = ShoppingCartItem::where('shopping_cart_id',$orderCustomer->shopping_cart_id)->get();
    }
    $customerShippingAddress = CustomerShippingAddress::find($orderCustomer->address_id);
    $mini = Mini::find($orderCustomer->mini_id);
    $beforeDiscount = $shoppingCartItems->sum('before_price_discount');
    $retialPrice = $shoppingCartItems->sum('retial_price');
    $allQuantity = $shoppingCartItems->sum('quantity');
    $discountPrice = $beforeDiscount - $retialPrice;
    $datas = [];
    $datas['created_at'] =  $orderCustomer->created_at->format('Y-m-d H:i');
    $datas['order_detail'] = [];
    $datas['customer_information'] = [];
    $datas['shipping_address'] = [];
    $datas['shopping_carts'] = [];
    $datas['mini'] = [];
    $datas['id'] = $orderCustomer->id;
    $datas['before_discount_price'] = $beforeDiscount;
    $datas['discount_price'] = $discountPrice;
    $datas['all_quantity'] = $allQuantity;
    $datas['coupon_code'] = null;
    $datas['coupon_discount_price'] = null;
    $datas['sum_total'] = $retialPrice;
    $datas['shipping_cost'] = null;
    $datas['grand_total'] = $retialPrice;
    $datas['mini']['dt_code'] = $mini->dt_code;
    $datas['mini']['dt_name'] = $mini->dt_name;
    $datas['mini']['mini_code'] = $mini->mini_code;
    $datas['mini']['mini_name'] = $mini->mini_name;
    $datas['mini']['mini_tel'] = $mini->customer_phonenumber;
    $datas['mini']['walls_code'] = $mini->walls_code;
    $datas['mini']['walls_name'] = $mini->walls_name;
    $datas['order_detail']['order_no'] = $orderCustomer->order_no;
    $datas['order_detail']['order_date'] = $orderCustomer->created_at->format('Y-m-d');
    $datas['order_detail']['order_time'] = $orderCustomer->created_at->format('H:i');
    $datas['order_detail']['order_status'] = $orderCustomer->status;
    $datas['order_detail']['grand_total'] = $retialPrice;
    $datas['order_detail']['deliver_date'] = \Carbon\Carbon::createFromFormat('d/m/y', $orderCustomer->date_of_delivery)->format('Y-m-d');
    $datas['order_detail']['deliver_time'] = $orderCustomer->time_of_delivery;
    $datas['order_detail']['cancle_case'] = $orderCustomer->cancle_case;
    $datas['customer_information']['customer_id'] = null;
    $datas['customer_information']['first_name'] = $customerShippingAddress->first_name;
    $datas['customer_information']['last_name'] = $customerShippingAddress->last_name;
    $datas['customer_information']['email'] = null;
    $datas['customer_information']['phone_number'] = $customerShippingAddress->phone_number;
    $datas['customer_information']['reward_point'] = null;
    $datas['shipping_address']['first_name'] = $customerShippingAddress->first_name;
    $datas['shipping_address']['last_name'] = $customerShippingAddress->last_name;
    $datas['shipping_address']['address'] = $customerShippingAddress->address;
    $datas['shipping_address']['province'] = $customerShippingAddress->province;
    $datas['shipping_address']['district'] = $customerShippingAddress->district;
    $datas['shipping_address']['sub_district'] = $customerShippingAddress->sub_district;
    $datas['shipping_address']['post_code'] = $customerShippingAddress->post_code;
    $datas['shipping_address']['phone_number'] = $customerShippingAddress->phone_number;
    $datas['shipping_address']['latitude'] = $customerShippingAddress->latitude;
    $datas['shipping_address']['longtitude'] = $customerShippingAddress->longtitude;
    foreach ($shoppingCartItems as $key => $shoppingCartItem) {
      $productImages = ProductImages::where('icnow_product_id',$shoppingCartItem->product_id)->first();
      $datas['shopping_carts'][$key]['product_name'] = $shoppingCartItem->product_name;
      $datas['shopping_carts'][$key]['section_id'] = $shoppingCartItem->section_id;
      $datas['shopping_carts'][$key]['image_url'] = ($productImages)? $productImages->img_url : null;
      $datas['shopping_carts'][$key]['sku'] = $shoppingCartItem->sku;
      $datas['shopping_carts'][$key]['price'] = $shoppingCartItem->price;
      $datas['shopping_carts'][$key]['special_price'] = $shoppingCartItem->special_price;
      $datas['shopping_carts'][$key]['quantity'] = $shoppingCartItem->quantity;
      $datas['shopping_carts'][$key]['total'] = $shoppingCartItem->retial_price;
      $datas['shopping_carts'][$key]['details'] = [];
      if($shoppingCartItem->section_id == 1){
        $shoppingCartItemDetailDiy = $shoppingCartItem->shoppingCartItemDetailDiy;
        $shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiy->shoppingCartItemDetailDiyItems;
        $datas['shopping_carts'][$key]['details']['person_in_party'] = $shoppingCartItemDetailDiy->person_in_party;
        $datas['shopping_carts'][$key]['details']['product_focus'] = $shoppingCartItemDetailDiyItems->pluck('value')->toArray();
        $datas['shopping_carts'][$key]['details']['comment'] = $shoppingCartItemDetailDiy->comment;
      }else{
        $shoppingCartItemDetailPartySets = $shoppingCartItem->shoppingCartItemDetailPartySets;
        $datas['shopping_carts'][$key]['details']['group_items'] = [];
        foreach ($shoppingCartItemDetailPartySets as $keyPartySet => $shoppingCartItemDetailPartySet) {
          $shoppingCartItemDetailPartySetItems = $shoppingCartItemDetailPartySet->shoppingCartItemDetailPartySetItems;
          $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['group_name'] = $shoppingCartItemDetailPartySet->group_name;
          $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['choose_item'] = $shoppingCartItemDetailPartySet->choose_item;
          $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['max_item'] = $shoppingCartItemDetailPartySet->max_item;
          $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'] = [];
          foreach ($shoppingCartItemDetailPartySetItems as $keyPartySetItem => $shoppingCartItemDetailPartySetItem) {
            $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_name'] = $shoppingCartItemDetailPartySetItem->item_name;
            $datas['shopping_carts'][$key]['details']['group_items'][$keyPartySet]['items'][$keyPartySetItem]['item_value'] = $shoppingCartItemDetailPartySetItem->item_value;
          }

        }
      }
    }

    return $datas;
  }

	public static function pushMessageToCustomerOrder($lineUserProfile,$orderCustomer)
	{
    $type = 1;
    // $section = 1;
    // $queryDatas = self::getQueryDatas($orderCustomer,$section);
    // $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
    $section = 2;
    $queryDatas = self::getQueryDatas($orderCustomer,$section);
    $messages[1]  = CoreLineFunction::setHeader($queryDatas,$type);
    // $section = 3;
    // $queryDatas = self::getQueryDatas($orderCustomer,$section);
    // $messages[2]  = CoreLineFunction::setHeader($queryDatas,$type);
    /*-------------------------comment 2019-03-11----------------------------------*/
    // $messages[1]  = CoreLineFunction::setBody($queryDatas,$type);
		// $messages[2]  = CoreLineFunction::setFooter($queryDatas,$type);
    /*-------------------------comment 2019-03-11----------------------------------*/
    $message = collect($messages);
    self::pushMessage($lineUserProfile->mid,$message);
    // self::pushMessageToMiniOrder($orderCustomer);
    // self::pushMessageToAdminOrder($orderCustomer);
	}

	public static function pushMessageToMiniOrder($orderCustomer)
	{
    $queryDatas = self::getQueryDatas($orderCustomer);
    $type = 2;
		$miniUser = MiniUser::where('dt_code',$orderCustomer->dt_code)->first();
		if($miniUser){
			$lineUserProfile = LineUserProfile::find($miniUser->line_user_id);
			$messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
      // $messages[1]  = CoreLineFunction::setBody($queryDatas,$type);
      // $messages[2]  = CoreLineFunction::setFooter($queryDatas,$type);
	    $message = collect($messages);
	    if($lineUserProfile){
        self::pushMessage($lineUserProfile->mid,$message);
      }
		}
	}

  public static function pushMessageToAdminOrder($orderCustomer)
  {
    $type = 4;
    $queryDatas = self::getQueryDatas($orderCustomer);
    $adminUsers = AdminUser::where('is_user',1)->get();
    foreach ($adminUsers as $key => $adminUser) {
      $lineUserProfile = LineUserProfile::find($adminUser->line_user_id);
      $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
      // $messages[1]  = CoreLineFunction::setHeaderMini($queryDatas,$type);
      // $messages[2]  = CoreLineFunction::setBody($queryDatas,$type);
      // $messages[3]  = CoreLineFunction::setFooter($queryDatas,$type);
      $message = collect($messages);
      self::pushMessage($lineUserProfile->mid,$message);
    }
  }

  public static function getTextHeader($queryDatas,$type)
  {
    $text = "";
    if($type == 1){
      $text = "สวัสดีค่ะ คุณ ".$queryDatas['customer_information']['first_name']." \nขอบคุณสำหรับคำสั่งซื้อ";
    }else if($type == 2){
      $text = "สวัสดี MINI ".$queryDatas['mini']['mini_name']." \nมีคำสั่งซื้อใหม่";
    }else if($type == 3){
      $text = "สวัสดีค่ะ คุณ ".$queryDatas['customer_information']['first_name']." \nจัดส่งสินค้าเรียบร้อยแล้ว";
    }else if($type == 4){
      $text = "สวัสดี Admin... \nรายละเอียดคำสั่งซื้อใหม่";
    }else if($type == 5){
      $text = "สวัสดี Admin... \nMINI ยืนยันคำสั่งซื้อเรียบร้อยแล้ว";
    }else if($type == 6){
      $text = "สวัสดี Admin... \nคำสั่งซื้อถูกยกเลิกโดย MINI";
    }else if($type == 7){
      $text = "สวัสดีค่ะ Admin... \nจัดส่งสินค้าเรียบร้อยแล้ว";
    }else if($type == 8){
      $text = "สวัสดี Admin... \nคำสั่งซื้อถูกยกเลิกโดยระบบ";
    }

    return $text;
  }

  public static function setHeader($queryDatas,$type)
  {
    // dd($queryDatas);
    $headerText = self::getTextHeader($queryDatas,$type);
    $datas = [];
    $datas = [
      "type"      => "flex",
      "altText"   => "ข้อมูลการสั่งซื้อ",
    ];
    $datas['contents'] = [
      "type"=> "bubble",
      "styles"=> [
        "footer"=> [
          "separator"=> true
        ]
      ],
      "body"=> [
        "type"=> "box",
        "layout"=> "vertical",
        "contents"=> [
          [
            "type"=>"text",
            "text"=>$headerText,
            "wrap"=>true,
            "weight"=>"bold",
            "size"=>"sm",
            "margin"=>"xl"
          ],
          [
            "type"=>"separator",
            "margin"=>"xl"
          ]
        ]
      ]
    ];
    $count = 2;
    if($type == 4 || $type == 5 || $type == 8){
      $datas['contents']['body']['contents'][$count] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "margin"=>"xl",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"รายละเอียด MINI",
            "size"=>"sm",
            "weight"=>"bold",
            "color"=>"#ee322a",
            "flex"=>0
          ]
        ]
      ];
      $count++;
      $datas['contents']['body']['contents'][$count] = [
        "type"=>"box",
        "layout"=>"vertical",
        "margin"=>"xl",
        "spacing"=>"sm"
      ];
      $countSet = 0;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"DT Code:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['dt_code'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"DT Name:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['dt_name'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"separator",
        "margin"=>"xl"
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "margin"=>"xl",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"MINI Code:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['mini_code'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"MINI Name:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['mini_name'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"MINI Tel:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=> ($queryDatas['mini']['mini_tel'] != "")? $queryDatas['mini']['mini_tel'] : "N/A",
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"separator",
        "margin"=>"xl"
      ];
      // $countSet++;
      // $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
      //   "type"=>"box",
      //   "layout"=>"horizontal",
      //   "margin"=>"xl",
      //   "contents"=>[
      //     [
      //       "type"=>"text",
      //       "text"=>"Wall's Code:",
      //       "size"=>"sm",
      //       "color"=>"#555555",
      //       "flex"=>0
      //     ],
      //     [
      //       "type"=>"text",
      //       "text"=>$queryDatas['mini']['walls_code'],
      //       "size"=>"sm",
      //       "color"=>"#555555",
      //       "align"=>"end"
      //     ]
      //   ]
      // ];
      // $countSet++;
      // $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
      //   "type"=>"box",
      //   "layout"=>"horizontal",
      //   "contents"=>[
      //     [
      //       "type"=>"text",
      //       "text"=>"Wall's Name:",
      //       "size"=>"sm",
      //       "color"=>"#555555",
      //       "flex"=>0
      //     ],
      //     [
      //       "type"=>"text",
      //       "text"=>$queryDatas['mini']['walls_name'],
      //       "size"=>"sm",
      //       "color"=>"#555555",
      //       "align"=>"end"
      //     ]
      //   ]
      // ];
      $count++;
    }
    // $count = 3;
    $datas['contents']['body']['contents'][$count] = [
      "type"=>"box",
      "layout"=>"horizontal",
      "margin"=>"xl",
      "contents"=>[
        [
          "type"=>"text",
          "text"=>"รายละเอียด คำสั่งซื้อ",
          "size"=>"sm",
          "weight"=>"bold",
          "color"=>"#ee322a",
          "flex"=>0
        ]
      ]
    ];
    $count++;
    $datas['contents']['body']['contents'][$count] = [
          "type"=>"box",
          "layout"=>"vertical",
          "margin"=>"xl",
          "spacing"=>"sm"
    ];
    $countSet = 0;
    $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=> [
          [
            "type"=>"text",
            "text"=>"เลขที่คำสั่งซื้อ:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
              "type"=>"text",
              "text"=>$queryDatas['order_detail']['order_no'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
          ]
      ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
            "layout"=>"horizontal",
            "contents"=>[
              [
                "type"=>"text",
                "text"=>"วันที่/เวลาสั่งซื้อ:",
                "size"=>"sm",
                "color"=>"#555555",
                "flex"=>0
              ],
              [
                "type"=>"text",
                "text"=>$queryDatas['created_at'],
                "size"=>"sm",
                "color"=>"#111111",
                "align"=>"end"
              ]
            ]
      ];
      $countSet++;
      if($type != 1){
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"ชื่อ:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>$queryDatas['customer_information']['first_name'],
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
        ];
        $countSet++;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"เบอร์โทร:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>$queryDatas['shipping_address']['phone_number'],
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
        ];
        $countSet++;
      }
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
            "layout"=>"horizontal",
            "contents"=>[
              [
                "type"=>"text",
                "text"=>"สถานะ:",
                "size"=>"sm",
                "color"=>"#555555",
                "flex"=>0
              ],
              [
                "type"=>"text",
                "text"=>$queryDatas['order_detail']['order_status'],
                "size"=>"sm",
                "color"=> "#ee322a",
                "align"=>"end"
              ]
            ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
            "layout"=>"horizontal",
            "margin"=>"xl",            
            "contents"=>[
              [
                "type"=>"text",
                "text"=>"วันที่จัดส่งสินค้า:",
                "size"=>"sm",
                "color"=>"#555555",
                "flex"=>0
              ],
              [
                "type"=>"text",
                "text"=>$queryDatas['order_detail']['deliver_date'],
                "size"=>"sm",
                "color"=>"#111111",
                "align"=>"end"
              ]
            ]
      ];
      $countSet++;
      $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        "type"=>"box",
            "layout"=>"horizontal",
            "contents"=>[
              [
                "type"=>"text",
                "text"=>"ช่วงเวลาจัดส่งสินค้า:",
                "size"=>"sm",
                "color"=>"#555555",
                "flex"=>0
              ],
              [
                "type"=>"text",
                "text"=>$queryDatas['order_detail']['deliver_time'],
                "size"=>"sm",
                "color"=>"#111111",
                "align"=>"end"
              ]
            ]
      ];
      $count++;
      $datas['contents']['body']['contents'][$count] = [
        "type"=>"separator",
        "margin"=>"xl"
      ];
      if($type != 5 && $type != 7 && $type != 8){
        $count++;
        $datas['contents']['body']['contents'][$count] = [
            "type"=>"box",
            "layout"=>"vertical",
            "margin"=>"xl",
            "spacing"=>"sm"
        ];
        $countSet = 0;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
            "type"=>"box",
            "layout"=>"horizontal",
            "contents"=> [
                [
                  "type"=>"text",
                  "text"=>"รายละเอียด สินค้า",
                  "size"=>"sm",
                  "color"=>"#ee322a",
                  "weight"=>"bold",
                  "flex"=>0
                ]
            ]
          ];
        $count++;
        foreach ($queryDatas['shopping_carts'] as $key => $shoppingCart) {
          if($shoppingCart['section_id'] == 1){
            $datas['contents']['body']['contents'][$count] = [
              "type"=>"box",
              "layout"=>"vertical",
              "margin"=>"xl",
              "spacing"=>"sm"
            ];
            $countSet = 0;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"ชื่อสินค้า:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>$shoppingCart['product_name'],
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"เน้นสินค้าจำพวก:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>implode(' / ', $shoppingCart['details']['product_focus']),
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "margin"=>"xl",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"จำนวน:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=> (string)$shoppingCart['quantity'],
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"ราคา:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>(string)$shoppingCart['total']." บาท",
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"separator",
              "margin"=>"xl"
            ];
          }else{
            $datas['contents']['body']['contents'][$count] = [
              "type"=>"box",
              "layout"=>"vertical",
              "spacing"=>"sm",
              "margin"=>"xl"
            ];
            $countSet = 0;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"ชื่อสินค้า:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>$shoppingCart['product_name'],
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "margin"=>"xl",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"จำนวน:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>(string)$shoppingCart['quantity'],
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"ราคา:",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>(string)$shoppingCart['total']." บาท",
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"separator",
              "margin"=>"xl"
            ];
          }
          $count++;
        }
        $datas['contents']['body']['contents'][$count] = [
            "type"=>"box",
            "layout"=>"vertical",
            "margin"=>"xl",
            "spacing"=>"sm"
        ];
        $countSet = 0;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=> [
              [
                "type"=>"text",
                "text"=>"ยอดรวมทั้งหมด",
                "size"=>"sm",
                "color"=>"#ee322a",
                "weight"=>"bold",
                "flex"=>0
              ]
          ]
        ];
        $countSet++;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "margin"=>"xl",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"จำนวนรวมทั้งหมด:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>(string)$queryDatas['all_quantity'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $countSet++;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ราคารวม:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>(string)number_format($queryDatas['grand_total'],2)." THB",
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        // $countSet++;
        // $count++;
        // $datas['contents']['body']['contents'][$count] = [
        //   "type"=>"separator",
        //   "margin"=>"xl"
        // ];
        $countSet++;
      }
      if($type == 1){
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] =  [
          "type"=>"box",
          "layout"=>"vertical",
          "margin"=>"xxl",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"คุณจะได้รับการติดต่อกลับภายใน 1 ชม",
              "wrap"=>true,
              "margin"=>"md",
              "size"=>"sm"
            ]
          ]
        ];
      }else if($type == 2){
        $datas['contents']['footer'] = [
          "type"=>"box",
          "layout"=>"vertical",
          "contents"=>[
            [
              "type"=>"spacer",
              "size"=>"sm"
            ],
            [
              "type"=>"text",
              "text"=>"กรุณาโทรหาลูกค้า ภายใน 15 นาที",
              "wrap"=>true,
              "margin"=>"md",
              "size"=>"sm"
            ],
            [
              "type"=>"separator",
              "margin"=>"xl"
            ],
            [
              "type"=>"button",
              "style"=>"primary",
              "height"=>"sm",
              "color"=>"#ee322a",
              "margin"=>"xl",
              "action"=>[
                "type"=>"uri",
                "label"=>"ยืนยันคำสั่งซื้อ",
                "uri"=>\URL::to('/')."/mini-page/bp/".$queryDatas['order_detail']['order_no']
              ]
            ]
          ]
        ];
      }else if($type == 4){
        // dd($queryDatas);
        if($queryDatas['mini']['mini_tel'] != ''){
          $datas['contents']['footer'] = [
            "type"=>"box",
            "layout"=>"vertical",
            "contents"=>[
              [
                "type"=>"spacer",
                "size"=>"sm"
              ],
              [
                "type"=>"button",
                "style"=>"primary",
                "height"=>"sm",
                "color"=>"#ee322a",
                "margin"=>"xl",
                "action"=>[
                  "type"=>"uri",
                  "label"=>"โทรหา MINI",
                  "uri"=> 'tel:'.$queryDatas['mini']['mini_tel']
                ]
              ]
            ]
          ];
        }
      }else if($type == 6){
        // $count++;
        // $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
        //   "type"=>"box",
        //   "layout"=>"vertical",
        //   "margin"=>"xxl",
        //   "contents"=>[
        //     [
        //     "type"=>"text",
        //     "text"=>"เหตุผล:".$queryDatas['order_detail']['cancle_case'],
        //     "wrap"=>true,
        //     "margin"=>"md",
        //     "size"=>"sm"
        //     ]
        //   ]
        // ];
      }else if($type == 8){
        // $count++;
        // $datas['contents']['body']['contents'][$count] = [
        //     "type"=>"box",
        //     "layout"=>"vertical",
        //     "margin"=>"xl",
        //     "spacing"=>"sm"
        // ];
        // $datas['contents']['body']['contents'][$count]['contents'][0] = [
        //   "type"=>"box",
        //   "layout"=>"vertical",
        //   "margin"=>"xxl",
        //   "contents"=>[
        //     [
        //     "type"=>"text",
        //     "text"=>"คำสั่งซื้อถูกยกเลิกโดยระบบ เนื่องจาก MINI ในพื้นที่ไม่ได้ทำการยืนยันคำสั่งซื้อภายใน 2 ชั่วโมง",
        //     "wrap"=>true,
        //     "margin"=>"md",
        //     "size"=>"sm"
        //     ]
        //   ]
        // ];
      }


    return $datas;
  }

  public static function setHeaderMini($queryDatas,$type)
  {
      $datas = [];
      $datas = [
        "type"      => "flex",
        "altText"   => "ข้อมูลการสั่งซื้อ",
      ];
      $datas['contents'] = [
        "type"=> "bubble",
        "styles"=> [
          "footer"=> [
            "separator"=> true
          ]
        ],
        "body"=> [
          "type"=> "box",
          "layout"=> "vertical",
          "contents"=> [
              [
                "type"=>"box",
                "layout"=>"horizontal",
                "margin"=>"xl",
                "contents"=>[
                  [
                    "type"=>"text",
                    "text"=>"รายละเอียด MINI",
                    "size"=>"sm",
                    "weight"=>"bold",
                    "color"=>"#ee322a",
                    "flex"=>0
                  ],
              ]
            ]
          ]
        ]
      ];
      $datas['contents']['body']['contents'][1] = [
        "type"=>"separator",
        "margin"=>"xl"
      ];
      $datas['contents']['body']['contents'][2] = [
        "type"=>"box",
        "layout"=>"vertical",
        "margin"=>"xl",
        "spacing"=>"sm"
      ];
      $datas['contents']['body']['contents'][2]['contents'][0] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"DT Code:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['dt_code'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $datas['contents']['body']['contents'][2]['contents'][1] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"DT Name:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['dt_name'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $datas['contents']['body']['contents'][2]['contents'][2] = [
        "type"=>"separator",
        "margin"=>"xl"
      ];
      $datas['contents']['body']['contents'][2]['contents'][3] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "margin"=>"xl",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"MINI Code:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['mini_code'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $datas['contents']['body']['contents'][2]['contents'][4] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"MINI Name:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['mini_name'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $datas['contents']['body']['contents'][2]['contents'][5] = [
        "type"=>"separator",
        "margin"=>"xl"
      ];
      $datas['contents']['body']['contents'][2]['contents'][6] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "margin"=>"xl",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"Wall's Code:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['walls_code'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];
      $datas['contents']['body']['contents'][2]['contents'][7] = [
        "type"=>"box",
        "layout"=>"horizontal",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"Wall's Name:",
            "size"=>"sm",
            "color"=>"#555555",
            "flex"=>0
          ],
          [
            "type"=>"text",
            "text"=>$queryDatas['mini']['walls_name'],
            "size"=>"sm",
            "color"=>"#555555",
            "align"=>"end"
          ]
        ]
      ];

      return $datas;
  }  

  public static function setBody($queryDatas,$type)
  {
    $datas = [];
    $datas = [
      "type"      => "flex",
      "altText"   => "ข้อมูลการสั่งซื้อ",
    ];
    $datas['contents'] = [
      "type"=> "bubble",
      "styles"=> [
        "footer"=> [
          "separator"=> true
        ]
      ],
      "body"=> [
        "type"=> "box",
        "layout"=> "vertical",
        "contents"=> [
            [
              "type"=>"box",
              "layout"=>"horizontal",
              "margin"=>"xl",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"รายละเอียด สินค้า",
                  "size"=>"sm",
                  "weight"=>"bold",
                  "color"=>"#ee322a",
                  "flex"=>0
                ],
            ]
          ]
        ]
      ]
    ];
    $datas['contents']['body']['contents'][1] = [
      "type"=>"separator",
      "margin"=>"xl"
    ];
    $count = 2;
    foreach ($queryDatas['shopping_carts'] as $key => $shoppingCart) {
      if($shoppingCart['section_id'] == 1){
        $datas['contents']['body']['contents'][$count] = [
          "type"=>"box",
          "layout"=>"vertical",
          "margin"=>"xl",
          "spacing"=>"sm"
        ];
        $datas['contents']['body']['contents'][$count]['contents'][0] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ชื่อสินค้า:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>$shoppingCart['product_name'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $datas['contents']['body']['contents'][$count]['contents'][1] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ประมาณจำนวนคนในปาร์ตี้:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=> (string)$shoppingCart['details']['person_in_party'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $datas['contents']['body']['contents'][$count]['contents'][2] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"เน้นสินค้าจำพวก:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>implode(' / ', $shoppingCart['details']['product_focus']),
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $datas['contents']['body']['contents'][$count]['contents'][3] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ข้อความเพิ่มเติม:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "wrap"=> true,
              "text"=>($shoppingCart['details']['comment'] != "")? $shoppingCart['details']['comment'] : "-",
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $datas['contents']['body']['contents'][$count]['contents'][4] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "margin"=>"xl",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"จำนวน:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=> (string)$shoppingCart['quantity'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $datas['contents']['body']['contents'][$count]['contents'][5] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ราคารวม:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>(string)$shoppingCart['total']." THB",
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $datas['contents']['body']['contents'][$count]['contents'][6] = [
          "type"=>"separator",
          "margin"=>"xl"
        ];
      }else{
        $datas['contents']['body']['contents'][$count] = [
          "type"=>"box",
          "layout"=>"vertical",
          "spacing"=>"sm",
          "margin"=>"xl"
        ];
        $datas['contents']['body']['contents'][$count]['contents'][0] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ชื่อสินค้า:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>$shoppingCart['product_name'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $countSet = 0;
        foreach ($shoppingCart['details']['group_items'] as $key => $groupItems) {
          $countSet++;
          $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
            "type"=>"box",
            "layout"=>"horizontal",
            "contents"=>[
              [
                "type"=>"text",
                "text"=>$groupItems['group_name']."(".$groupItems['choose_item']."/".$groupItems['max_item']."):",
                "size"=>"sm",
                "color"=>"#555555",
                "flex"=>0
              ],
              [
                "type"=>"text",
                "text"=>"-",
                "size"=>"sm",
                "color"=>"#111111",
                "align"=>"end"
              ]
            ]
          ];
          foreach ($groupItems['items'] as $key => $item) {
            $countSet++;
            $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
              "type"=>"box",
              "layout"=>"horizontal",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"-- ".$item['item_name'].":",
                  "size"=>"sm",
                  "color"=>"#555555",
                  "flex"=>0
                ],
                [
                  "type"=>"text",
                  "text"=>$item['item_value']." แท่ง",
                  "size"=>"sm",
                  "color"=>"#111111",
                  "align"=>"end"
                ]
              ]
            ];
          }
        }
        $countSet++;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "margin"=>"xl",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"จำนวน:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>(string)$shoppingCart['quantity'],
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $countSet++;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"box",
          "layout"=>"horizontal",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"ราคารวม:",
              "size"=>"sm",
              "color"=>"#555555",
              "flex"=>0
            ],
            [
              "type"=>"text",
              "text"=>(string)$shoppingCart['total']." THB",
              "size"=>"sm",
              "color"=>"#111111",
              "align"=>"end"
            ]
          ]
        ];
        $countSet++;
        $datas['contents']['body']['contents'][$count]['contents'][$countSet] = [
          "type"=>"separator",
          "margin"=>"xl"
        ];
      }
      $count++;
    }
    // dd($datas);

    return $datas;
  }

  public static function setFooter($queryDatas,$type)
  {
    $datas = [];
    $datas = [
      "type"      => "flex",
      "altText"   => "ข้อมูลการสั่งซื้อ",
    ];
    $datas['contents'] = [
      "type"=> "bubble",
      "styles"=> [
        "footer"=> [
          "separator"=> true
        ]
      ],
      "body"=> [
        "type"=> "box",
        "layout"=> "vertical",
        "contents"=> [
            [
              "type"=>"box",
              "layout"=>"horizontal",
              "margin"=>"xl",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"ยอดรวมทั้งหมด",
                  "size"=>"sm",
                  "weight"=>"bold",
                  "color"=>"#ee322a",
                  "flex"=>0
                ],
            ]
          ]
        ]
      ]
    ];
    $datas['contents']['body']['contents'][1] = [
      "type"=>"separator",
      "margin"=>"xl"
    ];

    $datas['contents']['body']['contents'][2] = [
      "type"=>"box",
      "layout"=>"horizontal",
      "margin"=>"xl",
      "contents"=>[
        [
          "type"=>"text",
          "text"=>"จำนวนรวมทั้งหมด:",
          "size"=>"sm",
          "color"=>"#555555",
          "flex"=>0
        ],
        [
          "type"=>"text",
          "text"=>(string)$queryDatas['all_quantity'],
          "size"=>"sm",
          "color"=>"#111111",
          "align"=>"end"
        ]
      ]
    ];

    $datas['contents']['body']['contents'][3] = [
      "type"=>"box",
      "layout"=>"vertical",
      "spacing"=>"sm"
    ];

    $datas['contents']['body']['contents'][3]['contents'][] = [
      "type"=>"box",
      "layout"=>"horizontal",
      "contents"=>[
        [
          "type"=>"text",
          "text"=>"ราคาก่อนหักส่วนลด:",
          "size"=>"sm",
          "color"=>"#555555",
          "flex"=>0
        ],
        [
          "type"=>"text",
          "text"=>(string)number_format($queryDatas['before_discount_price'],2)." THB",
          "size"=>"sm",
          "color"=>"#111111",
          "align"=>"end"
        ]
      ]
    ];

    $datas['contents']['body']['contents'][3]['contents'][] = [
      "type"=>"box",
      "layout"=>"horizontal",
      "contents"=>[
        [
          "type"=>"text",
          "text"=>"จำนวนส่วนลด:",
          "size"=>"sm",
          "color"=>"#555555",
          "flex"=>0
        ],
        [
          "type"=>"text",
          "text"=>(string)number_format($queryDatas['discount_price'],2)." THB",
          "size"=>"sm",
          "color"=>"#111111",
          "align"=>"end"
        ]
      ]
    ];

    $datas['contents']['body']['contents'][3]['contents'][] = [
      "type"=>"box",
      "layout"=>"horizontal",
      "contents"=>[
        [
          "type"=>"text",
          "text"=>"ราคาหลังหักส่วนลด:",
          "size"=>"sm",
          "color"=>"#555555",
          "flex"=>0
        ],
        [
          "type"=>"text",
          "text"=>(string)number_format($queryDatas['sum_total'],2)." THB",
          "size"=>"sm",
          "color"=>"#111111",
          "align"=>"end"
        ]
      ]
    ];

    $datas['contents']['body']['contents'][3]['contents'][] = [
      "type"=>"box",
      "layout"=>"horizontal",
      "margin"=>"xl",
      "contents"=>[
        [
          "type"=>"text",
          "text"=>"ราคารวม:",
          "size"=>"sm",
          "color"=>"#555555",
          "flex"=>0
        ],
        [
          "type"=>"text",
          "text"=>(string)number_format($queryDatas['grand_total'],2)." THB",
          "size"=>"sm",
          "color"=>"#111111",
          "align"=>"end"
        ]
      ]
    ];

    // $datas['contents']['body']['contents'][3]['contents'][] = [
    //   "type"=>"separator",
    //   "margin"=>"xl"
    // ];

    if($type == 1){
      $datas['contents']['body']['contents'][3]['contents'][] =  [
        "type"=>"box",
        "layout"=>"vertical",
        "margin"=>"xxl",
        "contents"=>[
          [
            "type"=>"text",
            "text"=>"กรุณารอการยืนยันคำสั่งซื้อ\nจากทางวอลล์แมนของเรา",
            "wrap"=>true,
            "margin"=>"md",
            "size"=>"sm"
          ]
        ]
      ];
    }else if($type == 2){
      $datas['contents']['footer'] = [
        "type"=>"box",
        "layout"=>"vertical",
        "contents"=>[
          [
            "type"=>"spacer",
            "size"=>"sm"
          ],
          [
            "type"=>"button",
            "style"=>"primary",
            "height"=>"sm",
            "color"=>"#ee322a",
            "action"=>[
              "type"=>"uri",
              "label"=>"ยืนยันคำสั่งซื้อ",
              "uri"=>\URL::to('/')."/mini-page/bp/".$queryDatas['order_detail']['order_no']
            ]
          ]
        ]
      ];
    }else if($type == 6){
      $datas['contents']['body']['contents'][3]['contents'][] = [
        "type"=>"box",
        "layout"=>"vertical",
        "margin"=>"xxl",
        "contents"=>[
          [
          "type"=>"text",
          "text"=>"เหตุผล:".$queryDatas['order_detail']['cancle_case'],
          "wrap"=>true,
          "margin"=>"md",
          "size"=>"sm"
          ]
        ]
      ];
    }else if($type == 8){
      $datas['contents']['body']['contents'][3]['contents'][] = [
        "type"=>"box",
        "layout"=>"vertical",
        "margin"=>"xxl",
        "contents"=>[
          [
          "type"=>"text",
          "text"=>"คำสั่งซื้อถูกยกเลิกโดยระบบ เนื่องจาก MINI ในพื้นที่ไม่ได้ทำการยืนยันคำสั่งซื้อภายใน 2 ชั่วโมง",
          "wrap"=>true,
          "margin"=>"md",
          "size"=>"sm"
          ]
        ]
      ];
    }


    return $datas;
  }

  public static function pushMessageToCustomerConfirmOrder($orderCustomer)
	{
    $queryDatas = self::getQueryDatas($orderCustomer);
		$lineUserProfile = LineUserProfile::find($orderCustomer->line_user_id);
		$messages[0]  = CoreLineFunction::setFlexToCustomerConfirmOrder($queryDatas);
    // $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
    $message = collect($messages);
    self::pushMessage($lineUserProfile->mid,$message);
    self::pushMessageToAdminConfirmOrder($orderCustomer);
	}

  public static function pushMessageToAdminConfirmOrder($orderCustomer)
  {
    $type = 5;
    $queryDatas = self::getQueryDatas($orderCustomer);
    $adminUsers = AdminUser::where('is_user',1)->get();
    foreach ($adminUsers as $key => $adminUser) {
      $lineUserProfile = LineUserProfile::find($adminUser->line_user_id);
      $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
      // $messages[1]  = CoreLineFunction::setHeaderMini($queryDatas,$type);
      // $messages[2]  = CoreLineFunction::setBody($queryDatas,$type);
      // $messages[3]  = CoreLineFunction::setFooter($queryDatas,$type);
      $message = collect($messages);
      self::pushMessage($lineUserProfile->mid,$message);
    }
  }

	public static function setFlexToCustomerConfirmOrder($queryDatas)
  {
    	$datas = [
            "type"      => "flex",
            "altText"   => "ยืนยันคำสั่งซื้อ",
        ];
      $datas['contents'] = [
        "type"=> "bubble",
        "styles"=> [
          "footer"=> [
            "separator"=> true
          ]
        ],
        "body"=> [
          "type"=> "box",
          "layout"=> "vertical",
          "contents"=> [
            [
              "type"=> "text",
              "text"=> "สวัสดีค่ะ คุณ ".$queryDatas['shipping_address']['first_name']." \nยืนยันคำสั่งซื้อ",
              "wrap"=> true,
              "weight"=> "bold",
              "size"=> "sm",
              "margin"=> "xl"
            ],
            [
              "type"=> "separator",
              "margin"=> "xl"
            ],
            [
              "type"=> "box",
              "layout"=> "horizontal",
              "margin"=> "xl",
              "contents"=> [
                [
                  "type"=> "text",
                  "text"=> "รายละเอียด คำสั่งซื้อ",
                  "size"=> "sm",
                  "weight"=> "bold",
                  "color"=> "#ee322a",
                  "flex"=> 0
                ]
              ]
            ],
            [
              "type"=> "box",
              "layout"=> "vertical",
              "spacing"=> "sm",
              "contents"=> [
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "เลขที่คำสั่งซื้อ:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['order_no'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "วันที่/เวลาสั่งซื้อ:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['created_at'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                // [
                //   "type"=> "box",
                //   "layout"=> "horizontal",
                //   "contents"=> [
                //     [
                //       "type"=> "text",
                //       "text"=> "Cus. Name:",
                //       "size"=> "sm",
                //       "color"=> "#555555",
                //       "flex"=> 0
                //     ],
                //     [
                //       "type"=> "text",
                //       "text"=> $queryDatas['shipping_address']['first_name'],
                //       "size"=> "sm",
                //       "color"=> "#111111",
                //       "align"=> "end"
                //     ]
                //   ]
                // ],
                // [
                //   "type"=> "box",
                //   "layout"=> "horizontal",
                //   "contents"=> [
                //     [
                //       "type"=> "text",
                //       "text"=> "Cus. Tel:",
                //       "size"=> "sm",
                //       "color"=> "#555555",
                //       "flex"=> 0
                //     ],
                //     [
                //       "type"=> "text",
                //       "text"=> $queryDatas['shipping_address']['phone_number'],
                //       "size"=> "sm",
                //       "color"=> "#111111",
                //       "align"=> "end"
                //     ]
                //   ]
                // ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "สถานะ:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['order_status'],
                      "size"=> "sm",
                      "color"=> "#ee322a",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "margin"=> "xl",            
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "วันที่จัดส่งสินค้า:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['deliver_date'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "ช่วงเวลาจัดส่งสินค้า:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['deliver_time'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=>"separator",
                  "margin"=>"xl"
                ],
                [
                  "type"=>"box",
                  "layout"=>"vertical",
                  "margin"=>"xxl",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"คุณจะได้รับการติดต่อ ก่อนเวลาจัดส่งสินค้า 1 ชั่วโมง",
                      "wrap"=>true,
                      "margin"=>"md",
                      "size"=>"sm"
                    ]
                  ]
                ]
              ]
            ]
          ]
        ]
      ];

		  return $datas;
    }

  public static function setFlexToCustomerDelivery($queryDatas)
  {
      $datas = [
            "type"      => "flex",
            "altText"   => "จัดส่งสินค้าเรียบร้อย",
        ];
      $datas['contents'] = [
        "type"=> "bubble",
        "styles"=> [
          "footer"=> [
            "separator"=> true
          ]
        ],
        "body"=> [
          "type"=> "box",
          "layout"=> "vertical",
          "contents"=> [
            [
              "type"=> "text",
              "text"=> "สวัสดีค่ะ คุณ ".$queryDatas['shipping_address']['first_name']." \nจัดส่งสินค้าเรียบร้อยแล้ว",
              "wrap"=> true,
              "weight"=> "bold",
              "size"=> "sm",
              "margin"=> "xl"
            ],
            [
              "type"=> "separator",
              "margin"=> "xl"
            ],
            [
              "type"=> "box",
              "layout"=> "horizontal",
              "margin"=> "xl",
              "contents"=> [
                [
                  "type"=> "text",
                  "text"=> "รายละเอียด คำสั่งซื้อ",
                  "size"=> "sm",
                  "weight"=> "bold",
                  "color"=> "#ee322a",
                  "flex"=> 0
                ]
              ]
            ],
            [
              "type"=> "box",
              "layout"=> "vertical",
              "spacing"=> "sm",
              "contents"=> [
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "เลขที่คำสั่งซื้อ:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['order_no'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "วันที่/เวลาสั่งซื้อ:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['created_at'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                // [
                //   "type"=> "box",
                //   "layout"=> "horizontal",
                //   "contents"=> [
                //     [
                //       "type"=> "text",
                //       "text"=> "Cus. Name:",
                //       "size"=> "sm",
                //       "color"=> "#555555",
                //       "flex"=> 0
                //     ],
                //     [
                //       "type"=> "text",
                //       "text"=> $queryDatas['shipping_address']['first_name'],
                //       "size"=> "sm",
                //       "color"=> "#111111",
                //       "align"=> "end"
                //     ]
                //   ]
                // ],
                // [
                //   "type"=> "box",
                //   "layout"=> "horizontal",
                //   "contents"=> [
                //     [
                //       "type"=> "text",
                //       "text"=> "Cus. Tel:",
                //       "size"=> "sm",
                //       "color"=> "#555555",
                //       "flex"=> 0
                //     ],
                //     [
                //       "type"=> "text",
                //       "text"=> $queryDatas['shipping_address']['phone_number'],
                //       "size"=> "sm",
                //       "color"=> "#111111",
                //       "align"=> "end"
                //     ]
                //   ]
                // ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "สถานะ:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['order_status'],
                      "size"=> "sm",
                      "color"=> "#ee322a",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "margin"=> "xl",            
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "วันที่จัดส่งสินค้า:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['deliver_date'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ],
                [
                  "type"=> "box",
                  "layout"=> "horizontal",
                  "contents"=> [
                    [
                      "type"=> "text",
                      "text"=> "ช่วงเวลาจัดส่งสินค้า:",
                      "size"=> "sm",
                      "color"=> "#555555",
                      "flex"=> 0
                    ],
                    [
                      "type"=> "text",
                      "text"=> $queryDatas['order_detail']['deliver_time'],
                      "size"=> "sm",
                      "color"=> "#111111",
                      "align"=> "end"
                    ]
                  ]
                ]

              ]
            ]
          ]
        ],
        "footer" => [
          "type" => "box",
          "layout" => "horizontal",
          "contents" => [
            [
              "type" => "button",
              "action" => [
                "type" => "uri",
                "label" => "ข้อเสนอแนะ",
                "uri" => "line://app/1451346504-ZoVLJezR?order_id=".$queryDatas['id']
              ],
              "color" => "#FF4747",
              "style" => "primary"
            ]
          ]
        ]
      ];

      return $datas;
    }

  public static function pushMessageToCustomerCancleOrder($orderCustomer)
	{
    $queryDatas = self::getQueryDatas($orderCustomer);
		$lineUserProfile = LineUserProfile::find($orderCustomer->line_user_id);
		$messages[0]  = CoreLineFunction::setFlexToCustomerCancleOrder($queryDatas);
    // $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type=8);
    $message = collect($messages);
    self::pushMessage($lineUserProfile->mid,$message);
    self::pushMessageToAdminCancleOrder($orderCustomer);
	}

	public static function pushMessageToAdminCancleOrder($orderCustomer)
  {
    // $type = 6;
    $type = 8;
    $queryDatas = self::getQueryDatas($orderCustomer);
    $adminUsers = AdminUser::where('is_user',1)->get();
    foreach ($adminUsers as $key => $adminUser) {
      $lineUserProfile = LineUserProfile::find($adminUser->line_user_id);
      $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
      // $messages[1]  = CoreLineFunction::setHeaderMini($queryDatas,$type);
      // $messages[2]  = CoreLineFunction::setBody($queryDatas,$type);
      // $messages[3]  = CoreLineFunction::setFooter($queryDatas,$type);
      $message = collect($messages);
      self::pushMessage($lineUserProfile->mid,$message);
    }
  }

	public static function setFlexToCustomerCancleOrder($queryDatas)
  {
    	$datas = [
        "type"      => "flex",
        "altText"   => "ปฎิเสธคำสั่งซื้อ",
      ];
      $datas['contents'] = [
        "type"=>"bubble",
        "styles"=>[
          "footer"=>[
            "separator"=>true
          ]
        ],
        "body"=>[
          "type"=>"box",
          "layout"=>"vertical",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"สวัสดีค่ะ คุณ ".$queryDatas['shipping_address']['first_name']." \nคำสั่งซื้อถูกยกเลิกโดยระบบ",
              "wrap"=>true,
              "weight"=>"bold",
              "size"=>"sm",
              "margin"=>"xl"
            ],
            [
              "type"=>"separator",
              "margin"=>"xl"
            ],
            [
              "type"=>"box",
              "layout"=>"horizontal",
              "margin"=>"xl",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"รายละเอียด คำสั่งซื้อ",
                  "size"=>"sm",
                  "weight"=>"bold",
                  "color"=>"#ee322a",
                  "flex"=>0
                ]
              ]
            ],
            [
              "type"=>"box",
              "layout"=>"vertical",
              "spacing"=>"sm",
              "contents"=>[
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"เลขที่คำสั่งซื้อ:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['order_no'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                // [
                //   "type"=>"box",
                //   "layout"=>"horizontal",
                //   "contents"=>[
                //     [
                //       "type"=>"text",
                //       "text"=>"Cus. Name:",
                //       "size"=>"sm",
                //       "color"=>"#555555",
                //       "flex"=>0
                //     ],
                //     [
                //       "type"=>"text",
                //       "text"=>$queryDatas['shipping_address']['first_name'],
                //       "size"=>"sm",
                //       "color"=>"#111111",
                //       "align"=>"end"
                //     ]
                //   ]
                // ],
                // [
                //   "type"=>"box",
                //   "layout"=>"horizontal",
                //   "contents"=>[
                //     [
                //       "type"=>"text",
                //       "text"=>"Cus. Tel:",
                //       "size"=>"sm",
                //       "color"=>"#555555",
                //       "flex"=>0
                //     ],
                //     [
                //       "type"=>"text",
                //       "text"=>$queryDatas['shipping_address']['phone_number'],
                //       "size"=>"sm",
                //       "color"=>"#111111",
                //       "align"=>"end"
                //     ]
                //   ]
                // ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"วันที่/เวลาสั่งซื้อ:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['created_at'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"สถานะ:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['order_status'],
                      "size"=>"sm",
                      "color"=>"#ee322a",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "margin"=>"xl",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"วันที่จัดส่งสินค้า:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['deliver_date'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"ช่วงเวลาจัดส่งสินค้า:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['deliver_time'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"separator",
                  "margin"=>"xl"
                ],
                [
                  "type"=>"box",
                  "layout"=>"vertical",
                  "margin"=>"xxl",
                  "contents"=>[
                    [
                      "type"=>"text",
                      // "text"=>"เราต้องขออภัยเป็นอย่างสูง เนื่องจาก ".$queryDatas['order_detail']['cancle_case']." \n\nกรุณาทำรายการใหม่อีกครั้ง",
                      "text"=>"เราต้องขออภัยเป็นอย่างสูง เนื่องจากไม่สามารถจัดส่งสินค้าในช่วงเวลาดังกล่าวได้  \n\nกรุณาทำรายการใหม่อีกครั้ง",
                      "wrap"=>true,
                      "margin"=>"md",
                      "size"=>"sm"
                    ]
                  ]
                ]
              ]
            ]
          ]
        ]
      ];

		  return $datas;
  }

  public static function pushMessageToCustomerDeliver($orderCustomer)
	{
    $type = 3;
    $queryDatas = self::getQueryDatas($orderCustomer);
		$lineUserProfile = LineUserProfile::find($orderCustomer->line_user_id);
		$messages[0]  = CoreLineFunction::setFlexToCustomerDelivery($queryDatas);
    $message = collect($messages);
    self::pushMessage($lineUserProfile->mid,$message);
    self::pushMessageToAdminDeliver($orderCustomer);
	}

	public static function pushMessageToAdminDeliver($orderCustomer)
  {
    $type = 7;
    $queryDatas = self::getQueryDatas($orderCustomer);
    $adminUsers = AdminUser::where('is_user',1)->get();
    foreach ($adminUsers as $key => $adminUser) {
      $lineUserProfile = LineUserProfile::find($adminUser->line_user_id);
      $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
      $message = collect($messages);
      self::pushMessage($lineUserProfile->mid,$message);
    }
  }

  public static function pushMessageToCustomerCancleBySystem($orderCustomer)
  {
    $queryDatas = self::getQueryDatas($orderCustomer);
    $lineUserProfile = LineUserProfile::find($orderCustomer->line_user_id);
    $messages[0]  = CoreLineFunction::setFlexToCustomerCancleOrder($queryDatas);
    $message = collect($messages);
    self::pushMessage($lineUserProfile->mid,$message);
    self::pushMessageToAdminCancleBySystem($orderCustomer);
  }

  public static function pushMessageToAdminCancleBySystem($orderCustomer)
  {
    $type = 8;
    $queryDatas = self::getQueryDatas($orderCustomer);
    $adminUsers = AdminUser::where('is_user',1)->get();
    foreach ($adminUsers as $key => $adminUser) {
      $lineUserProfile = LineUserProfile::find($adminUser->line_user_id);
      $messages[0]  = CoreLineFunction::setHeader($queryDatas,$type);
      // $messages[1]  = CoreLineFunction::setHeaderMini($queryDatas,$type);
      // $messages[2]  = CoreLineFunction::setBody($queryDatas,$type);
      // $messages[3]  = CoreLineFunction::setFooter($queryDatas,$type);
      $message = collect($messages);
      self::pushMessage($lineUserProfile->mid,$message);
    }
  }

  public static function setFlexToCustomerCancleBySystem($queryDatas)
  {
    $datas = [
          "type"      => "flex",
          "altText"   => "ยืนยันคำสั่งซื้อ",
      ];
    $datas['contents'] = [
        "type"=>"bubble",
        "styles"=>[
          "footer"=>[
            "separator"=>true
          ]
        ],
        "body"=>[
          "type"=>"box",
          "layout"=>"vertical",
          "contents"=>[
            [
              "type"=>"text",
              "text"=>"สวัสดีค่ะ คุณ ".$queryDatas['shipping_address']['first_name']." \nคำสั่งซื้อถูกยกเลิกโดยระบบ",
              "wrap"=>true,
              "weight"=>"bold",
              "size"=>"sm",
              "margin"=>"xl"
            ],
            [
              "type"=>"separator",
              "margin"=>"xl"
            ],
            [
              "type"=>"box",
              "layout"=>"horizontal",
              "margin"=>"xl",
              "contents"=>[
                [
                  "type"=>"text",
                  "text"=>"รายละเอียด คำสั่งซื้อ",
                  "size"=>"sm",
                  "weight"=>"bold",
                  "color"=>"#ee322a",
                  "flex"=>0
                ]
              ]
            ],
            [
              "type"=>"box",
              "layout"=>"vertical",
              "spacing"=>"sm",
              "contents"=>[
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"Order No.:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['order_no'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"Cus. Name:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['shipping_address']['first_name'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"Cus. Tel:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['shipping_address']['phone_number'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"Date/Time:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['created_at'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"Order Status:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['order_status'],
                      "size"=>"sm",
                      "color"=>"#ee322a",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "margin"=>"xl",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"วันที่จัดส่งสินค้า:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['deliver_date'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                [
                  "type"=>"box",
                  "layout"=>"horizontal",
                  "contents"=>[
                    [
                      "type"=>"text",
                      "text"=>"ช่วงเวลาจัดส่งสินค้า:",
                      "size"=>"sm",
                      "color"=>"#555555",
                      "flex"=>0
                    ],
                    [
                      "type"=>"text",
                      "text"=>$queryDatas['order_detail']['deliver_time'],
                      "size"=>"sm",
                      "color"=>"#111111",
                      "align"=>"end"
                    ]
                  ]
                ],
                    [
                      "type"=>"separator",
                      "margin"=>"xl"
                    ],
                    [
                      "type"=>"box",
                      "layout"=>"vertical",
                      "margin"=>"xxl",
                      "contents"=>[
                        [
                          "type"=>"text",
                          "text"=>"เราต้องขออภัยเป็นอย่างสูง เนื่องจากไม่สามารถจัดส่งสินค้าในช่วงเวลาดังกล่าวได้ \n\nกรุณาทำรายการใหม่อีกครั้ง",
                          "wrap"=>true,
                          "margin"=>"md",
                          "size"=>"sm"
                        ]
                      ]
                    ]          
              ]
            ]
          ]
        ]
      ];

    return $datas;
  }
    
  public static function pushMessage($mid,$messages)
  {
      $datas = collect();
      $lineSettingBusiness = LineSettingBusiness::where('active', 1)->first();
      $datas->put('sentUrl', 'https://api.line.me/v2/bot/message/push');
      $datas->put('token', 'Bearer '.$lineSettingBusiness->channel_access_token);
      $data = collect([
          "to" => $mid,
          "messages"   => $messages,
      ]);
      $datas->put('data', $data->toJson());
      $sent = LineWebHooks::sent($datas);

      return 1;
  }
}
