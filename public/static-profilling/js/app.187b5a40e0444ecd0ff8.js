webpackJsonp([0],[function(e,t,l){"use strict";function i(e,t){var l=["width","height","font-size"],i=(l.indexOf(t.key),t.values.split("px")),a="";return 2==i.length?a+=t.key+":"+parseInt(i[0])+"px;":a+=t.key+":"+t.values+";",a}t.a={convertCss:function(e){var t="",l="",a="";return e.css.map(function(e,l){t+=i(l,e)}),e.parent_css.map(function(e,t){l+=i(t,e)}),e.label_css.map(function(e,t){a+=i(t,e)}),{_css:t,_parent_css:l,_label_css:a}}}},,,function(e,t,l){"use strict";var i=l(1),a=l(28),s=l(25),n=l.n(s);i.a.use(a.a),t.a=new a.a({routes:[{path:"/",name:"Hello",component:n.a}]})},function(e,t,l){function i(e){l(23)}var a=l(2)(l(5),l(26),i,null,null);e.exports=a.exports},function(e,t,l){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={name:"app"}},function(e,t,l){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=l(17),a=l(21),s=l(10),n=l(11),o=l(20),d=l(15),r=l(12),c=(l(19),l(8)),p=l(7),u=l(9),_=l(16),y=l(18),b=l(14),f=l(13),v=l(24);l.n(v);t.default={data:function(){return{display_object:{},render_wallpaper_color:{},render_array:[],testx:"0",subscriber_id:0,profilling_id:0,_json_data:[],_json_data2:[],chk_agree:!1}},created:function(){var e=this,t=$("#hid_profilling").html(),l=$("#hid_subdata").html();this._json_data=JSON.parse(t),this._json_data.profilling_actions.sort(function(e,t){return e.seq_no-t.seq_no}),this._json_data2=JSON.parse(l),this.subscriber_id=this._json_data.subscriber_id,this.profilling_id=this._json_data.profilling_id,console.log(["_json_data",this._json_data]),this._json_data.profilling_actions.map(function(e,t){e.auto_fill=""}),this._json_data2.length>0&&this._json_data.profilling_actions.map(function(t,l){if(t.field_id>0){var i=e._json_data2.find(function(e){return e.field_id==t.field_id});void 0!=i&&(t.auto_fill=i.value)}}),this._json_data.profilling_actions.map(function(e,t){if(""!=e.id){console.log(["v.field",e.field]);var l=null===e.field;if(null!=e.field||0==l){if(e.field.field_items.length>0){var v=[];e.field.field_items.map(function(e,t){v.push({id:e.id,title:e.value})}),e.data_set=v}else e.data_set=[];"boolean"==e.field.type&&(e.data_set=[{id:1,title:"Yes"},{id:0,title:"No"}]),"textbox"==e.type?e.html_render=o.a.textbox_template(e):"selectbox"==e.type?e.html_render=d.a.select_template(e):"radio"==e.type?e.html_render=r.a.radio_template(e):"checkbox"==e.type?e.html_render=c.a.chk_template(e):"textarea"==e.type?e.html_render=y.a.textarea_template(e):"date"==e.type?e.html_render=b.a.date_template(e):"radio_bool"==e.type&&(e.html_render=f.a.radio_bool_template(e))}else"text"==e.type?e.html_render=i.a.text_template(e):"textlink"==e.type?e.html_render=a.a.textlink_template(e):"submit"==e.type?e.html_render=_.a.submit_template(e):"images"==e.type?e.html_render=s.a.img_template(e):"images_link"==e.type?e.html_render=n.a.imglink_template(e):"checkbox_agree"==e.type?e.html_render=p.a.chk_agree_template(e):"fwd_popup"==e.type&&(e.html_render=u.a.hellosoda_popup_template(e))}}),this.render_array=this._json_data.profilling_actions},mounted:function(){var e=$('meta[name="hostname"]').attr("content");if($("#main_form").prop("action",e+"/activity"),$('input[type="submit"]').length>0){var t=$('input[type="submit"]').css("height");t=t.split("px");var l=$(window).height();$("#render_element").css("height",l-t[0]),$("#render_element").slimScroll({height:$("#render_element").css("height")})}this.set_body_wallpaper_color(this._json_data.color_wallpaper);var i=$(".chk_arg");if(i.length>0){$(i[0]).is(":checked")?$('input[type="submit"]').prop("disabled",!1):$('input[type="submit"]').prop("disabled",!0),$(i[0]).change(function(){this.checked?$('input[type="submit"]').prop("disabled",!1):$('input[type="submit"]').prop("disabled",!0)})}},methods:{cssToObject:function(e){var t=[];return $.each(e,function(e,l){var i="",a=["width","height","font-size"],s=(a.indexOf(l.key),l.values.split("px"));2==s.length?i+=l.key+":"+1*parseInt(s[0])+"px;":i+=l.key+":"+l.values+";",t.push(i)}),t},get_object_from_api:function(){var e=$("#api_values").html();return $("#api_values").remove(),JSON.parse(e)},set_body_wallpaper_color:function(e){$("body").css("background-color",this._json_data.page_color),$("#main_render").css("background-color",e),navigator.userAgent.match(/Android/i)||navigator.userAgent.match(/webOS/i)||navigator.userAgent.match(/iPhone/i)||navigator.userAgent.match(/iPod/i)||navigator.userAgent.match(/BlackBerry/i)||navigator.userAgent.match(/Windows Phone/i)?$("#main_render").css("width","100%"):$("#main_render").css("width",this._json_data.max_page_width)},convert_css_and_setting:function(e){var t="{";return $.each(e,function(e,l){e>0&&(t+=","),t+='"'+l.key+'":"'+l.values.toString().replace(/"/g,'\\"')+'"'}),t+="}",JSON.parse(t)},checkSubmit:function(){var e=!0;if($.each($(".valid_textbox"),function(){0==$(this).val().length&&(e=!1)}),$.each($(".valid_selectbox"),function(){"-1"==$(this).val()&&(e=!1)}),!e)return alert("กรุณากรอกข้อมูลให้ครบถ้วน"),!1;document.getElementById("main_form").submit()}}}},function(e,t,l){"use strict";var i=l(0),a={type:"checkbox_agree",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Checkbox Agree",settings:[{id:0,type:"textarea",key:"text_title",values:"Option 1",label:"คำอธิบาย"}],css:[{id:0,type:"colorpicker",key:"border-color",values:"#d9d9d9",label:"สีของขอบ"},{id:0,type:"select",key:"border-width",values:"1px",label:"ความหนาของขอบ"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}]};t.a={push_object:function(e){return a.id=e,a},chk_agree_template:function(e){console.log(["checkbox-agree",e]);var t=i.a.convertCss(e),l=t._css;return"\n    <div style='float:left;"+t._parent_css+'\'>\n        <label class="mt-checkbox mt-checkbox-outline" style="float:left;word-break: break-all;'+t._label_css+'">\n            <input class="chk_arg" type="checkbox">'+e.settings[0].values+'\n            <span style="'+l+'"></span>\n        </label>\n    </div>'}}},function(e,t,l){"use strict";var i=l(0),a={type:"checkbox",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Checkbox",settings:[{id:0,type:"text",key:"text_title",values:"Option 1",label:"หัวข้อ"},{id:0,type:"select",key:"selectfield",values:"1",label:"Field"}],css:[{id:0,type:"colorpicker",key:"border-color",values:"#d9d9d9",label:"สีของขอบ"},{id:0,type:"select",key:"border-width",values:"1px",label:"ความหนาของขอบ"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}]};t.a={push_object:function(e){return a.id=e,a.title=_data.name,a.settings[0].values=_data.field_name,a.field_id=_data.id,a},chk_template:function(e){console.log(["checkbox",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css;return 1==e.field.is_required&&("valid_textbox",'<span class="text-danger">*</span> '),"\n    <div style='float:left;"+a+'\'>\n        <label class="mt-checkbox mt-checkbox-outline" style="'+s+'">\n            <input type="checkbox" name="field_items['+e.field_id+']">'+e.settings[0].values+'\n            <span style="'+l+'"></span>\n        </label>\n    </div>'}}},function(e,t,l){"use strict";var i={type:"fwd_popup",id:"-",pri_id:0,field_id:0,seq_no:0,title:"เงื่อนไขและข้อตกลง",setting:[{id:0,type:"textarea",key:"text_content",values:'ข้าพเจ้ายินยอมให้บริษัท บริษัทในเครือ และ บริษัทคู่ค้า ("บริษัท") ประมวลผล ใช้ เปิดเผย หรือจัดเก็บข้อมูลส่วนบุคคลของข้าพเจ้าเพื่อประโยชน์ใดๆ ตามวัตถุประสงค์ของบริษัท รวมทั้งยินยอมให้บริษัทติดต่อข้าพเจ้าด้วยวิธีการใดๆ เพื่อประชาสัมพันธ์ หรือให้คำปรึกษาทางการเงิน และบริการต่างๆ ของบริษัท',label:"ข้อความ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#16bae3",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"center",label:"การชิดตัวอักษรของ Label"}],parent_css:[{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"},{id:0,type:"colorpicker",key:"color",values:"#FFFFFF",label:"สีตัวอักษรของ Popup"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Popup"}],label_css:[]};t.a={push_object:function(e){return i.id=e,i},hellosoda_popup_template:function(e){return console.log(e),'\n<div style="float:left;width:100%;'+e.parent_css[0].key+":"+e.parent_css[0].values+';">\n    <p style="'+e.css[2].key+":"+e.css[2].values+';"><a href="javascript:$(\'#'+e.id+'_contraniner\').show()" style="text-decoration: underline;'+e.css[0].key+":"+e.css[0].values+";"+e.css[1].key+":"+e.css[1].values+';">'+e.title+'</a></p>\n    <div style="\nfloat: left;\nheight: 100%;\nclear: none;\nwidth: 100%;\nbackground-color: rgba(0, 0, 0, 0.75);\nmin-width: 0px;\nmin-height: 0px;\nposition: fixed;\ntop:0px;\nleft: 0px;\nz-index: 10;\ndisplay:none;" class="clearfix" id="'+e.id+'_contraniner">\n        <div style="\nmax-width: 90%;\nfloat: none;\nheight: auto;\nmargin: 10% auto;\nclear: none;\nwidth: 412px;\nbackground-color: #e06c22;\nmin-height: 40px;\npadding: 20px;" class="clearfix">\n            <div style="\nfloat: none;\nheight: auto;\nmargin: 0px auto;\nclear: none;\nwidth: 100%;\nmin-width: 0px;" class="clearfix">\n                <p style="\nfloat: left;\nwidth: auto;\nheight: auto;\ntext-align: left;\nfont-weight: 100;\nline-height: 30px;\nmargin: 0px;\nclear: none;\nmin-height: 0px;'+e.parent_css[1].key+":"+e.parent_css[1].values+";"+e.parent_css[2].key+":"+e.parent_css[2].values+'" id="'+e.id+'">'+e.settings[0].values+'</p>\n            </div>\n            <div style="\nfloat: left;\nheight: auto;\nmargin: 0px;\nclear: none;\nwidth: 100%;\nmin-width: 0px;\npadding: 10px;" class="clearfix">\n                <div style="\nfloat: none;\nheight: auto;\nmargin: 0px auto;\nclear: both;\nwidth: 80px;\nborder: 1px solid rgb(255, 255, 255);\nborder-radius: 8px !important;\ncursor:pointer;\npadding: 10px;" class="clearfix" onclick="$(\'#'+e.id+'_contraniner\').hide()">\n                    <p style="\nfloat: left;\nfont-size: 1em;\nwidth: 100%;\nheight: auto;\ntext-align: center;\nfont-weight: normal;\nline-height: 1em;\nmargin: 0px;\nclear: none;\nmin-height: 0px;\nmin-width: 0px;\ncolor: rgb(255, 255, 255);" >ปิด</p>\n                </div>\n            </div>\n        </div>\n    </div>\n</div>\n'}}},function(e,t,l){"use strict";var i=l(0),a={type:"images",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Images",settings:[{id:0,type:"textarea",key:"image_src",values:"https://daily.rabbitstatic.com/wp-content/uploads/2017/02/FWD-Life-Insurance-Logo.png",label:"Images URL"}],css:[{id:0,type:"text",key:"width",values:"100px",label:"ความกว้างของภาพ"},{id:0,type:"text",key:"height",values:"60px",label:"ความยาวของภาพ"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"},{id:0,type:"select",key:"text-align",values:"center",label:"การชัดของรูป"}],label_css:[]};t.a={push_object:function(e){return a.id=e,a},img_template:function(e){console.log(["images",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css;t._label_css;return"\n    <div style='float:left;width:100%;"+a+"'>\n        <img src=\""+e.settings[0].values+'" style="'+l+'" alt="">\n    </div>'}}},function(e,t,l){"use strict";var i=l(0),a={type:"images_link",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Images Link",settings:[{id:0,type:"textarea",key:"image_src",values:"https://daily.rabbitstatic.com/wp-content/uploads/2017/02/FWD-Life-Insurance-Logo.png",label:"Images URL"},{id:0,type:"textarea",key:"url_link",values:"https://www.google.co.th",label:"Link URL"}],css:[{id:0,type:"text",key:"width",values:"100px",label:"ความกว้างของภาพ"},{id:0,type:"text",key:"height",values:"60px",label:"ความยาวของภาพ"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"},{id:0,type:"select",key:"text-align",values:"center",label:"การชัดของรูป"}],label_css:[]};t.a={push_object:function(e){return a.id=e,a},imglink_template:function(e){console.log(["imageslink",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css;t._label_css;return"\n    <div style='float:left;width:100%;"+a+"'>\n        <a href=\""+e.settings[1].values+'">\n            <img src="'+e.settings[0].values+'" style="'+l+'" alt="">\n        </a>\n    </div>'}}},function(e,t,l){"use strict";var i=l(0),a={type:"radio",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Radio Button",settings:[{id:0,type:"textdis",key:"text_title",values:"ชื่อ - สกุล",label:"หัวข้อ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"30px",label:"ขนาดตัวอักษร"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"30px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}],data_set:[]};t.a={push_object:function(e,t){a.id=e,a.title=t.name,a.settings[0].values=t.field_name,a.field_id=t.id;var l=[];return t.field_items.map(function(e,t){l.push({id:e.id,title:e.value})}),a.data_set=l,a},radio_template:function(e){console.log(["radio",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css,n="",o="";1==e.field.is_readonly&&""!=e.auto_fill&&(o="disabled"),e.data_set.map(function(t,i){var a="";""!=e.auto_fill?t.id.toString()===e.auto_fill&&(a='checked="checked"'):0==i&&(a='checked="checked"'),n+='<label style="'+l+'width: 100%;margin-bottom: 10px;margin-left:0px;" class="checkbox-inline"><input '+a+" "+o+' type="radio" name="field_items['+e.field_id+']" value="'+t.id+'"> '+t.title+"</label>"});var d="";return 1==e.field.is_required&&("valid_radio",d='<span class="text-danger">*</span> '),"\n    <div style='float:left;"+a+"'>\n        <label style='width:100%;"+s+"'>"+d+e.settings[0].values+"</label>\n        "+n+"\n    </div>"}}},function(e,t,l){"use strict";var i=l(0),a={type:"radio",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Radio Button",settings:[{id:0,type:"textdis",key:"text_title",values:"ชื่อ - สกุล",label:"หัวข้อ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"30px",label:"ขนาดตัวอักษร"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"30px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}],data_set:[]};t.a={push_object:function(e,t){a.id=e,a.title=t.name,a.settings[0].values=t.field_name,a.field_id=t.id;var l=[{id:1,title:"Yes"},{id:0,title:"No"}];return a.data_set=l,a},radio_bool_template:function(e){console.log(["radio_bool",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css,n="",o="";1==e.field.is_readonly&&""!=e.auto_fill&&(o="disabled"),e.data_set.map(function(t,i){var a="";""!=e.auto_fill?t.id.toString()==e.auto_fill&&(a='checked="checked"'):0==i&&(a='checked="checked"'),n+='<label style="'+l+'width: 100%;margin-bottom: 10px;margin-left:0px;" class="checkbox-inline"><input type="radio" '+o+" "+a+' name="field_items['+e.field_id+']" value="'+t.id+'"> '+t.title+"</label>"});var d="";return 1==e.field.is_required&&("valid_radio",d='<span class="text-danger">*</span> '),"\n    <div style='float:left;"+a+"'>\n        <label style='width:100%;"+s+"'>"+d+e.settings[0].values+"</label>\n        "+n+"\n    </div>"}}},function(e,t,l){"use strict";var i=l(0),a={type:"date",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Date",settings:[{id:0,type:"textdis",key:"text_title",values:"ชื่อ - สกุล",label:"หัวข้อ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}]};t.a={push_object:function(e,t){return a.id=e,a.title=t.name,a.setting[0].values=t.field_name,a.field_id=t.id,a},date_template:function(e){var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css,n="",o="";1==e.field.is_required&&(n="valid_textbox",o='<span class="text-danger">*</span> ');var d="";return 1==e.field.is_readonly&&""!=e.auto_fill&&(d="disabled"),"\n    <div style='float:left;"+a+"'>\n        <label style='"+s+"'>"+o+e.settings[0].values+'</label>\n        <input type=\'date\' autocomplete="off" name="field_items['+e.field_id+']" '+d+" id='"+e.id+"' class='form-control "+n+"' style='"+l+"' value=\""+e.auto_fill+'"/>\n    </div>'}}},function(e,t,l){"use strict";var i=l(0),a={type:"selectbox",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Dropdown List",settings:[{id:0,type:"text",key:"text_title",values:"Dropdown",label:"หัวข้อ"},{id:0,type:"select",key:"selectfield",values:"1",label:"Field"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}],data_set:[]};t.a={push_object:function(e,t){a.id=e,a.title=t.name,a.settings[0].values=t.field_name,a.field_id=t.id;var l=[];return t.field_items.map(function(e,t){l.push({id:e.id,title:e.value})}),a.data_set=l,a},select_template:function(e){console.log(["selectbox",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css,n="";e.data_set.map(function(t,l){var i="";""!=e.auto_fill?t.id.toString()===e.auto_fill&&(i='selected="selected"'):0==l&&(i='selected="selected"'),n+='<option value="'+t.id+'" '+i+">"+t.title+"</option>"});var o="",d="";1==e.field.is_required&&(o="valid_selectbox",d='<span class="text-danger">*</span> ');var r="";return 1==e.field.is_readonly&&""!=e.auto_fill&&(r="disabled"),"\n    <div style='float:left;"+a+"'>\n        <label style='"+s+"'>"+d+e.settings[0].values+"</label>\n        <select id='"+e.id+"' name=\"field_items["+e.field_id+']" '+r+" class='form-control "+o+"' style='"+l+'\'><option value="-1">-- Select --</option>'+n+"</select>\n    </div>"}}},function(e,t,l){"use strict";var i=l(0),a={type:"submit",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Submit Button",settings:[{id:0,type:"text",key:"text_content",values:"Save",label:"ข้อความ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#565656",label:"สีตัวอักษร"},{id:0,type:"colorpicker",key:"background-color",values:"#ffd535",label:"สีของปุ่ม"},{id:0,type:"colorpicker",key:"border-color",values:"#e4bf33",label:"สีขอบของปุ่ม"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษร"},{id:0,type:"select",key:"padding",values:"15px",label:"ความหนาของปุ่ม"}],parent_css:[],label_css:[]};t.a={push_object:function(e){return a.id=e,a},submit_template:function(e){console.log(["submit",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css;t._label_css;return"\n    <div style='width:100%;;float:left;"+a+"'>\n        <input type=\"submit\" id='"+e.id+"' style=\"border: none;cursor: pointer;margin: 0px;width: 100%;"+l+'" value="'+e.settings[0].values+'">\n    </div>'}}},function(e,t,l){"use strict";var i=l(0),a={type:"text",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Text",settings:[{id:0,type:"textarea",key:"text_content",values:"ขอบคุณที่ใช้บริการ",label:"ข้อความ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"},{id:0,type:"select",key:"text-align",values:"center",label:"การชิดตัวอักษร"},{id:0,type:"select",key:"text-indent",values:"0px",label:"การเยื้องย่อหน้า"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[]};t.a={push_object:function(e){return a.id=e,a},text_template:function(e){console.log(["text",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css;t._label_css;return"\n    <div style='float:left;"+a+"'>\n        <p id='"+e.id+"' style='word-break: break-all;margin:0px;"+l+"'>"+e.settings[0].values+"</p>\n    </div>"}}},function(e,t,l){"use strict";var i=l(0),a={type:"textbox",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Textbox",settings:[{id:0,type:"textdis",key:"text_title",values:"ชื่อ - สกุล",label:"หัวข้อ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}]};t.a={push_object:function(e,t){return a.id=e,a.title=t.name,a.settings[0].values=t.field_name,a.field_id=t.id,a},textarea_template:function(e){console.log(["textarea",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css,n="",o="";1==e.field.is_required&&(n="valid_textbox",o='<span class="text-danger">*</span> ');var d="";return 1==e.field.is_readonly&&""!=e.auto_fill&&(d="disabled"),"\n    <div style='float:left;"+a+"'>\n        <label style='"+s+"'>"+o+e.settings[0].values+"</label>\n        <textarea id='"+e.id+'\' autocomplete="off" name="field_items['+e.field_id+']" '+d+' rows="5" class=\'form-control '+n+"' style='"+l+"'>"+e.auto_fill+"</textarea>\n    </div>"}}},function(e,t,l){"use strict";l(0)},function(e,t,l){"use strict";var i=l(0),a={type:"textbox",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Textbox",settings:[{id:0,type:"text",key:"text_title",values:"ชื่อ - สกุล",label:"หัวข้อ"}],css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"}],label_css:[{id:0,type:"colorpicker",key:"color",values:"#595959",label:"สีตัวอักษรของ Label"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษรของ Label"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษรของ Label"},{id:0,type:"select",key:"font-weight",values:"normal",label:"ความหนาของตัวอักษรของ Label"}]};t.a={push_object:function(e,t){return a.id=e,a.title=t.name,a.settings[0].values=t.field_name,a.field_id=t.id,a},textbox_template:function(e){console.log(["textbox",e]);var t=i.a.convertCss(e),l=t._css,a=t._parent_css,s=t._label_css,n="",o="";1==e.field.is_required&&(n="valid_textbox",o='<span class="text-danger">*</span> ');var d="";return 1==e.field.is_readonly&&""!=e.auto_fill&&(d="disabled"),"\n    <div style='float:left;"+a+"'>\n        <label style='"+s+"'>"+o+e.settings[0].values+'</label>\n        <input type=\'text\' autocomplete="off" name="field_items['+e.field_id+"]\" id='"+e.id+"' "+d+" class='form-control "+n+"' style='"+l+"' value=\""+e.auto_fill+'" />\n    </div>'}}},function(e,t,l){"use strict";var i=l(0),a={type:"textlink",id:"-",pri_id:0,field_id:0,seq_no:0,title:"Text Link",settings:[{id:0,type:"text",key:"text_content",values:"Click",label:"ข้อความ"},{id:0,type:"text",key:"url_link",values:"http://google.co.th",label:"URL Address"}],css:[{id:0,type:"colorpicker",key:"color",values:"#1500ff",label:"สีตัวอักษร"},{id:0,type:"select",key:"font-size",values:"14px",label:"ขนาดตัวอักษร"},{id:0,type:"select",key:"text-indent",values:"0px",label:"การเยื้องย่อหน้า"}],parent_css:[{id:0,type:"select",key:"width",values:"100%",label:"ขนาด"},{id:0,type:"select",key:"padding",values:"10px",label:"ระยะห่างของขอบกับวัตถุ"},{id:0,type:"select",key:"text-align",values:"left",label:"การชิดตัวอักษร"}],label_css:[]};t.a={push_object:function(e){return a.id=e,a},textlink_template:function(e){var t=i.a.convertCss(e),l=t._css,a=t._parent_css;t._label_css;return console.log(["textlink",e]),"<div style='float:left;"+a+"'>\n        <a id='"+e.id+"' style='margin:0px;"+l+"' href='"+e.settings[1].values+"' >"+e.settings[0].values+"</a>\n    </div>"}}},function(e,t,l){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=l(1),a=l(4),s=l.n(a),n=l(3);i.a.config.productionTip=!1,new i.a({el:"#app",router:n.a,template:"<App/>",components:{App:s.a}})},function(e,t){},,function(e,t,l){var i=l(2)(l(6),l(27),null,null,null);e.exports=i.exports},function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,l=e._self._c||t;return l("div",{attrs:{id:"app"}},[l("router-view")],1)},staticRenderFns:[]}},function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,l=e._self._c||t;return l("div",[l("form",{attrs:{id:"main_form",action:"",method:"POST"},on:{submit:function(t){t.preventDefault(),e.checkSubmit()}}},[l("div",{staticStyle:{margin:"0px auto"},attrs:{id:"main_render"}},[l("div",{staticStyle:{width:"100%:"},attrs:{id:"render_element"}},e._l(e.render_array,function(t,i){return"submit"!=t.type?l("div",{domProps:{innerHTML:e._s(t.html_render)}}):e._e()})),e._v(" "),l("div",{staticStyle:{width:"100%:"},attrs:{id:"render_element2"}},e._l(e.render_array,function(t,i){return"submit"==t.type?l("div",{domProps:{innerHTML:e._s(t.html_render)}}):e._e()})),e._v(" "),l("input",{directives:[{name:"model",rawName:"v-model",value:e.subscriber_id,expression:"subscriber_id"}],attrs:{type:"hidden",name:"subscriber_id"},domProps:{value:e.subscriber_id},on:{input:function(t){t.target.composing||(e.subscriber_id=t.target.value)}}}),e._v(" "),l("input",{directives:[{name:"model",rawName:"v-model",value:e.profilling_id,expression:"profilling_id"}],attrs:{type:"hidden",name:"profilling_id"},domProps:{value:e.profilling_id},on:{input:function(t){t.target.composing||(e.profilling_id=t.target.value)}}})])])])},staticRenderFns:[]}}],[22]);
//# sourceMappingURL=app.187b5a40e0444ecd0ff8.js.map