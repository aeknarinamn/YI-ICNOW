var firebase_config = {
    apiKey: "AIzaSyCfR49NXcyyP3Lf6PIARrnWVdad03n4gow",
    authDomain: "yellowv2-707d8.firebaseapp.com",
    databaseURL: "https://yellowv2-707d8.firebaseio.com",
    storageBucket: "yellowv2-707d8.appspot.com",
    messagingSenderId: "51409631368"
  };
  firebase.initializeApp(firebase_config);

  var firebasex = firebase.database();
  
function DT_Load_Data(oTableX, _aaData) {
    var oSettings = oTableX.fnSettings();
    oTableX.fnClearTable(this);

    if (_aaData.length > 0) {

        for (var i = 0; i < _aaData.length; i++) {
            oTableX.oApi._fnAddData(oSettings, _aaData[i]);
        }
        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
        oTableX.fnDraw();
    }
}

function DT_ActiveClass(val) {
    var isActive = "dtbtn_active";
    if (val == 0) {
        isActive = "dtbtn_unactive";
    }
    return isActive;
}

var myApp = angular.module('myApp', ['ui.router']);

/*--Navbar Controller --*/
myApp.controller('navbarCtrl', ['$scope', '$location', function ($scope, $location) {

        $scope.isActive = function (viewLocation) {

            var isActiveReturn = false;
            var locationPath = $location.path();
            for (var i = 0; i < viewLocation.length; i++) {
                if (viewLocation[i] === locationPath) {
                    isActiveReturn = true;
                    break;
                }
            }

            return isActiveReturn;
        };

    }]);

myApp.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {
    //$locationProvider.hashPrefix('');

    var path_component = "./angular_view/component_main/";

    $stateProvider
    // Dashboard Route
    .state('0x00001',{
      url : "/" ,
      templateUrl : path_component+"home.html",
      controller : "HomeController"
    })
    // Line Route
    .state('0x00002',{
      url : "/line" ,
      templateUrl : path_component+"line.html",
      controller : "LineFuncationalController"
    })
    .state('0x00003',{
      url : "/line/campagin" ,
      templateUrl : path_component+"line_campagin.html",
      controller : "LineCampaginController"
    })
    .state('0x00004',{
      url : "/line/autoreply_defualt" ,
      templateUrl : path_component+"line_autoreply_defualt.html",
      controller : "LineAutoReplyDefualtController"
    })
    .state('0x00005',{
      url : "/line/autoreply_keyword" ,
      templateUrl : path_component+"line_autoreply_keyword.html",
      controller : "LineAutoreplyKeywordController"
    })
    .state('0x00006',{
      url : "/line/chatsupport" ,
      templateUrl : path_component+"line_chatsupport.html",
      controller : "LineChatSupportController"
    })
    .state('0x00007',{
      url : "/line/folder/campagin" ,
      templateUrl : path_component+"line_folder_campagin.html",
      controller : "LineFolderCampaginController"
    })
    .state('0x00008',{
      url : "/line/folder/autoreply" ,
      templateUrl : path_component+"line_folder_autoreply.html",
      controller : "LineFolderAutoreplyController"
    })
    .state('0x00009',{
      url : "/line/setting/token" ,
      templateUrl : path_component+"line_setting_token.html",
      controller : "LineSettingTokenController"
    })
    .state('0x00010',{
      url : "/line/setting/report_tag" ,
      templateUrl : path_component+"line_setting_report_tag.html",
      controller : "LineSettingReportTagController"
    })
    .state('0x00011',{
      url : "/line/setting/profilling" ,
      templateUrl : path_component+"line_setting_profilling.html",
      controller : "LineSettingProfillingController"
    })
    // Segment Route
    .state('0x00012',{
      url : "/segment" ,
      templateUrl : path_component+"segment.html",
      controller : "SegmentController"
    })
    .state('0x00013',{
      url : "/segment/core" ,
      templateUrl : path_component+"segment_core.html",
      controller : "SegmentCoreController"
    })
    .state('0x00014',{
      url : "/segment/folder" ,
      templateUrl : path_component+"segment_folder.html",
      controller : "SegmentFolderController"
    })
    // Setting Route
    .state('0x00015',{
      url : "/setting" ,
      templateUrl : path_component+"setting.html",
      controller : "SettingController"
    })
    .state('0x00016',{
      url : "/setting/flag" ,
      templateUrl : path_component+"setting_flag.html",
      controller : "SettingFlagController"
    })
    .state('0x00017',{
      url : "/setting/field" ,
      templateUrl : path_component+"setting_field.html",
      controller : "SettingFieldController"
    })
    .state('0x00018',{
      url : "/setting/subscriber" ,
      templateUrl : path_component+"setting_subscriber.html",
      controller : "SettingSubscriberController"
    })
    .state('0x00019',{
      url : "/setting/otp" ,
      templateUrl : path_component+"setting_otp.html",
      controller : "SettingOtpController"
    })
    .state('0x00020',{
      url : "/setting/location" ,
      templateUrl : path_component+"setting_location.html",
      controller : "SettingLocationController"
    })
    .state('0x00021',{
      url : "/setting/image_personalization" ,
      templateUrl : path_component+"setting_image_personalization.html",
      controller : "SettingImagePersonalizationController"
    })
    // Admin Route
    .state('0x00022',{
      url : "/admin" ,
      templateUrl : path_component+"admin.html",
      controller : "AdminController"
    })
    .state('0x00023',{
      url : "/admin/user" ,
      templateUrl : path_component+"admin_user.html",
      controller : "AdminUserController"
    })
    .state('0x00024',{
      url : "/admin/log" ,
      templateUrl : path_component+"admin_log.html",
      controller : "AdminLogController"
    })
    .state('0x00025',{
      url : "/admin/msg_error" ,
      templateUrl : path_component+"admin_msg_error.html",
      controller : "AdminMessageErrorController"
    })
    .state('0x00026',{
      url : "/line/campagin_triger" ,
      templateUrl : path_component+"line_campagin_triger.html",
      controller : "LineCampaginTrigerController"
    })
    $urlRouterProvider.otherwise("/");
});

myApp.service('DataDemoService', function ($http, $q) {

    this.data = "";

    this.get = function () {
        var q = $q.defer();
        q.resolve(this.data);
        return q.promise;
    }

    this.create = function (data) {
        var q = $q.defer();
        this.data.push(data);
        q.resolve({data:"OK"});
        return q.promise;
    }

});

myApp.service('HelperDatatablesService', function () {
    var vm = this;

    vm.DataTables = {
        oTable: [],
        table_id: "",
        datafield: [],
        datafieldpk: "",
        array_list: [],
        option: {
            "bStateSave": true,
            "lengthMenu": [
                [10, 50, 100, -1],
                [10, 50, 100, "All"]
            ],
            "pageLength": 10,
            "pagingType": "bootstrap_full_number",
            "bDestroy": true,
            "info": false,
            "searching": false,
            "aoColumns": []
        },
        selected: {
            tr: [] ,
            index : -1
        },
        init: function () {
            vm.DataTables.oTable = $(vm.DataTables.table_id).dataTable(vm.DataTables.option);
        },
        init_event: function ($compile, $scope) {
            $(vm.DataTables.table_id + " tbody").on("click", "tr", function () {
                vm.DataTables.oTable.$("tr.dt_selected").removeClass("dt_selected");
                $(this).addClass("dt_selected");
                vm.DataTables.selected.tr = $(this);
            });

            $(vm.DataTables.table_id).on('draw.dt', function () {
                $compile(".ngCRUDAction")($scope);
            });
        },
        render_data: function (type, btnType) {
            var _aaData = [];
            if (type == 1) {
                _aaData = vm.DataTables.each_data.type1(btnType);
            }
            DT_Load_Data(vm.DataTables.oTable, _aaData);
        },
        each_data: {
            type1: function (btnType) {
                var _aaData = [];

                $.each(vm.DataTables.array_list, function (i, v) {
                    var _subaaData = [];
                    _subaaData.push(i+1);
                    $.each(vm.DataTables.datafield, function (i2, v2) {
                        _subaaData.push(v[v2]);
                    });
                    if (btnType == 1) {
                        _subaaData.push(vm.DataTables.btnAction(i));
                    }
                    _aaData.push(_subaaData);
                });
                return _aaData;
            }
        } ,
        btnAction : function () {}
    }

});

myApp.service('HttpCRUDService', function ($http, $q) {

    this.url = "";

    this.methodpost = function (_data){
      var q = $q.defer();
      $http({
          header : {
            'Content-Type' : 'application/json; charset=utf-8'//,
            //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: this.url,
          data: _data
      }).then(function (response) {
          q.resolve(response);
      }, function (response) {
          swal("Error!", "Can't Read Data Some Thing Wrong!", "success");
      });
      return q.promise;
    }

    this.get = function () {
        var q = $q.defer();
        $http({
            method: 'POST',
            url: this.url,
            data: {type: "Get"}
        }).then(function (response) {
            q.resolve(response);
        }, function (response) {
            swal("Error!", "Can't Read Data Some Thing Wrong!", "success");
        });
        return q.promise;
    }

    this.read = function (id) {
        var q = $q.defer();
        $http({
            method: 'POST',
            url: this.url,
            data: {type: "Read", id: id}
        }).then(function (response) {
            q.resolve(response);
        }, function (response) {
            swal("Error!", "Can't Read Data Some Thing Wrong!", "success");
        });
        return q.promise;
    }

    this.create = function (input) {
        var q = $q.defer();
        $http({
            method: 'POST',
            url: this.url,
            data: {type: "Create", input: input}
        }).then(function (response) {
            q.resolve(response);
        }, function (response) {
            swal("Error!", "Can't Create Data Some Thing Wrong!", "success");
        });
        return q.promise;
    }

    this.update = function (id, input) {
        var q = $q.defer();
        $http({
            method: 'POST',
            url: this.url,
            data: {type: "Update", id: id, input: input}
        }).then(function (response) {
            q.resolve(response);
        }, function (response) {
            swal("Error!", "Can't Update Data Some Thing Wrong!", "success");
        });
        return q.promise;
    }

    this.delete = function (id) {
        var q = $q.defer();
        $http({
            method: 'POST',
            url: this.url,
            data: {type: "Delete", id: id}
        }).then(function (response) {
            q.resolve(response);
        }, function (response) {
            swal("Error!", "Can't Delete Data Some Thing Wrong!", "success");
        });
        return q.promise;
    }

});

myApp.controller('HomeController', function ($scope, $timeout, HttpCRUDService) {
    $scope.page_title = "Dashboard";
    $scope.config = {
      isfirstTime : true ,
      firebaselink : "todolist"
    }

    $scope.fetch_data = {
        todo: []
    }
    $scope.txt_keyword = "";
    $scope.todo_list_add = function () {
        var text = $scope.txt_keyword;
        $scope.firebase_crud.post(text);
    }

    $scope.firebase_crud = {
        get: function () {
            firebasex.ref($scope.config.firebaselink).on('value', function (snapshot) {
                $scope.fetch_data.todo = snapshot.val();
                if ($scope.config.isfirstTime == true) {
                    $scope.$digest();
                    $scope.config.isfirstTime = false;
                }
            });
        },
        read: function (_index) {

        },
        post: function (_data) {
            firebasex.ref($scope.config.firebaselink).push(_data);//.getKey();
        },
        update: function (_index) {
            firebasex.ref($scope.config.firebaselink + "/" + _index).set($scope.fetch_data.todo[_index]);
        },
        delete: function (_index) {
            firebasex.ref($scope.config.firebaselink + "/" + _index).remove();
        }
    }

    $timeout(function () {
      $scope.firebase_crud.get();
    }, 50);

    /*
       HttpCRUDService.url = "http://yellow-develop.app/setting_line";
       HttpCRUDService.methodpost({"channel_id":"1234","channel_secret":"channel_secretx","channel_access_token":"channel_access_tokenx","name":"namex"}).then(function(res){
       //swal("Error!", res.data, "success");
       });
     */

});

myApp.service('HomeService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});
      

myApp.controller('LineFuncationalController', function ($scope, $timeout) {
  $scope.page_title = "Line Funcational";

  $scope.menuLine = [
    {"icon" : "icon-user" , link : "line/campagin" , color : "bg-red" , type:"Core" , "title":"Campagin"} ,
    {"icon" : "icon-user" , link : "line/campagin_triger" , color : "bg-red" , type:"Core" , "title":"Trigger Campagin"} ,
    {"icon" : "icon-user" , link : "line/autoreply_defualt" , color : "bg-red", type:"Core" , "title":"Auto Reply Default"} ,
    {"icon" : "icon-user" , link : "line/autoreply_keyword" , color : "bg-red", type:"Core" , "title":"Auto Reply Keyword"} ,
    {"icon" : "icon-user" , link : "line/chatsupport" , color : "bg-red", type:"Core" , "title":"1-on-1 Chat"},

    {"icon" : "icon-folder" , link : "line/folder/campagin" , color : "bg-green-meadow", type:"Folder" , "title":"Campagin"} ,
    {"icon" : "icon-folder" , link : "line/folder/autoreply" , color : "bg-green-meadow", type:"Folder" , "title":"Auto Reply"} ,

    {"icon" : "icon-settings" , link : "line/setting/token" , color : "bg-blue", type:"Setting" , "title":"Line Token"} ,
    {"icon" : "icon-settings" , link : "line/setting/report_tag" , color : "bg-blue", type:"Setting" , "title":"Report Tag"} ,
    {"icon" : "icon-settings" , link : "line/setting/profilling" , color : "bg-blue", type:"Setting" , "title":"Profilling"}
  ];

  $scope.goto = function(link){
    location.href = link;
  }

    $timeout(function () {

    }, 50);

});

myApp.service('LineFuncationalService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});
      

myApp.controller('LineCampaginController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
    $scope.page_title = "Line Campagin";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Campaign Name"},
                {sTitle: "Segment"},
                {sTitle: "Sent Time"},
                {sTitle: "Message Type"},
                {sTitle: "Status"},
                {sTitle: "Last Sent"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3", "c4", "c5", "c6"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            $("#ddl_segment").select2({
                placeholder: "Select Segment",
                width: null
            });
            $("#ddl_folder").select2({
                placeholder: "Select Campagin Folder",
                width: null
            });
            $("#ddl_report_tag").select2({
                placeholder: "Select Report Tag"
            });
            $("#ddl_recurrence_every").select2({
                placeholder: "Select Recurrence"
            });

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];

        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            setting_open: true,
            message_open: false,
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();

                $.each($(".rdo_sent_time"), function (i, v) {
                    var _radio = $(this).find("input[type=radio]");
                    if (_radio.prop("checked") == true) {
                        $scope.box.sent_time.condition_open(_radio);
                    }
                });
                $.each($(".rdo_schedule_type"), function (i, v) {
                    var _radio = $(this).find("input[type=radio]");
                    if (_radio.prop("checked") == true) {
                        $scope.box.schedule_type.condition_open(_radio);
                    }
                });
                $.each($(".rdo_recurrence"), function (i, v) {
                    var _radio = $(this).find("input[type=radio]");
                    if (_radio.prop("checked") == true) {
                        $scope.box.recurrence.condition_open(_radio);
                    }
                });

            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            },
            sent_time_selected: function (val) {
                alert(val);
            }
        }
    }

    $scope.box = {
        sent_time: {
            sent_now: true,
            schedule: false,
            click: function ($event) {
                $event.preventDefault();
                $scope.box.sent_time.find_radio_value($event.target);
            },
            span_click: function ($event) {
                $event.preventDefault();
                var _parent = $($event.target).parent();
                $scope.box.sent_time.find_radio_value(_parent);
            },
            find_radio_value: function (_element) {
                var _radio = $(_element).find("input[type=radio]");
                $scope.box.sent_time.condition_open(_radio);
            },
            condition_open: function (_radio) {
                _radio.prop('checked', true);
                var _radio_option = _radio.val();
                if (_radio_option == "1") {
                    $scope.box.sent_time.sent_now_open();
                } else if (_radio_option == "2") {
                    $scope.box.sent_time.schedule_open();
                }
            },
            sent_now_open: function () {
                $scope.box.sent_time.sent_now = true;
                $scope.box.sent_time.schedule = false;
            },
            schedule_open: function () {
                $scope.box.sent_time.sent_now = false;
                $scope.box.sent_time.schedule = true;
            }
        },
        schedule_type: {
            ontime: true,
            recurring: false,
            click: function ($event) {
                $event.preventDefault();
                $scope.box.schedule_type.find_radio_value($event.target);
            },
            span_click: function ($event) {
                $event.preventDefault();
                var _parent = $($event.target).parent();
                $scope.box.schedule_type.find_radio_value(_parent);
            },
            find_radio_value: function (_element) {
                var _radio = $(_element).find("input[type=radio]");
                $scope.box.schedule_type.condition_open(_radio);
            },
            condition_open: function (_radio) {
                _radio.prop('checked', true);
                var _radio_option = _radio.val();
                if (_radio_option == "1") {
                    $scope.box.schedule_type.onetime_open();
                } else if (_radio_option == "2") {
                    $scope.box.schedule_type.recurring_open();
                }
            },
            onetime_open: function () {
                $scope.box.schedule_type.ontime = true;
                $scope.box.schedule_type.recurring = false;
            },
            recurring_open: function () {
                $scope.box.schedule_type.ontime = false;
                $scope.box.schedule_type.recurring = true;
            }
        },
        recurrence: {
            daliy: false,
            every: false,
            click: function ($event) {
                $event.preventDefault();
                $scope.box.recurrence.find_radio_value($event.target);
            },
            span_click: function ($event) {
                $event.preventDefault();
                var _parent = $($event.target).parent();
                $scope.box.recurrence.find_radio_value(_parent);
            },
            find_radio_value: function (_element) {
                var _radio = $(_element).find("input[type=radio]");
                $scope.box.recurrence.condition_open(_radio);
            },
            condition_open: function (_radio) {
                _radio.prop('checked', true);
                var _radio_option = _radio.val();
                if (_radio_option == "1") {
                    $scope.box.recurrence.daliy_open();
                } else if (_radio_option == "2") {
                    $scope.box.recurrence.every_open();
                }
            },
            daliy_open: function () {
                $scope.box.recurrence.daliy = true;
                $scope.box.recurrence.every = false;
            },
            every_open: function () {
                $scope.box.recurrence.daliy = false;
                $scope.box.recurrence.every = true;
            }
        }
    }

    $timeout(function () {
        x1.install();
        x1.Get();
    }, 50);

});


myApp.controller('LineAutoReplyDefualtController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Line Auto Reply Default";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "title"},
                {sTitle: "Status"},
                {sTitle: "Active Start"},
                {sTitle: "Active End"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3", "c4",];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Keyword 1", c2: "Active", c3: "01/01/2017 00:00", c4: "31/03/2017 23:59", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Keyword 2", c2: "Active", c3: "01/01/2017 00:00", c4: "31/03/2017 23:59", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Keyword 3", c2: "Active", c3: "01/01/2017 00:00", c4: "31/03/2017 23:59", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('LineAutoreplyDefualtService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});
      

myApp.controller('LineAutoreplyKeywordController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
    $scope.page_title = "Line Auto Reply Keyword";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "title"},
                {sTitle: "Keywords"},
                {sTitle: "Total Counts / Unique Counts"},
                {sTitle: "Status"},
                {sTitle: "Active Start"},
                {sTitle: "Active End"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3", "c4","c5","c6"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Keyword 1", c2: "[1],[2],[3]", c3: "1/1", c4: "Active", c5: "01/01/2017 00:00", c6: "31/03/2017 23:59"},
                {idx: "2", c1: "Keyword 2", c2: "[hello],[สวัสดี]", c3: "1/2", c4: "Active", c5: "01/01/2017 00:00", c6: "31/03/2017 23:59"},
                {idx: "3", c1: "Keyword 3", c2: "[test],[ทดสอบ]", c3: "2/1", c4: "Expire", c5: "01/01/2017 00:00", c6: "31/03/2017 23:59"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('LineAutoreplyKeywordService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineChatSupportController', function ($scope, $timeout) {
  $scope.page_title = "Line Chat Support";

    $timeout(function () {

    }, 50);

});

myApp.service('LineChatSupportService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineFolderCampaginController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Line Folder Campagin";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Folder Name"},
                {sTitle: "Campaign Sent" , sWidth: "15%"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Campagin Floder 1", c2: "3", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Campagin Floder 2", c2: "5", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Campagin Floder 3", c2: "10", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('LineFolderCampaginService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineFolderAutoreplyController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Line Folder Autoreply";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Folder Name"},
                {sTitle: "Campaign Sent"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('LineFolderAutoreplyService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineSettingTokenController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Line Setting Token";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "active"},
                {sTitle: "name"},
                {sTitle: "channel_id"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            $("#chk_token_active").bootstrapSwitch();
            
            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('LineSettingTokenService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineSettingReportTagController', function ($scope, $timeout) {
  $scope.page_title = "Line Setting Report Tag";

    $timeout(function () {

    }, 50);

});

myApp.service('LineSettingReportTagService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineSettingProfillingController', function ($scope, $timeout, $compile , LineSettingProfillingItemService
, LineSettingProfillingItemDataService) {
    $scope.page_title = "Line Setting Profilling";

    $scope.config = {
        isfirstTime : true ,
        setting_table_id: "#settingElement",
        setting_style_table_id: "#settingStyleElement",
        mobile_preview_id: "#render_element"
    }

    $scope.tab = {
      setting : true ,
      style : false
    }

    $scope.resDataForm = {
      leadformname : "",
      leadformroute : "",
      color_wallpaper : "#eaeaea",
      item : []
    }

    $scope.helper = {
        init_colorPicker: function (classx) {
            $(classx).minicolors({
                control: $(classx).attr('data-control') || 'hue',
                defaultValue: $(classx).attr('data-defaultValue') || '',
                inline: $(classx).attr('data-inline') === 'true',
                letterCase: $(classx).attr('data-letterCase') || 'lowercase',
                opacity: $(classx).attr('data-opacity'),
                position: $(classx).attr('data-position') || 'bottom left',
                theme: 'bootstrap'
            });
            $scope.helper.colorPicker_Redata(classx);
        },
        colorPicker_Redata : function (classx){
          /*===========================================================*/
          /*=== Re Data Color Picker ===*/
          /*===========================================================*/
          $.each($(classx), function (i, v) {
              var _this = $(this);
              var _value = _this.data("xvalue");

              _this.minicolors('value', _value);
          });
        },
        init_default_select: {
            fontsize: function () {
                var objx = [];
                for (var i = 8; i < 50; i++) {
                    objx.push({"id": i + "px", "title": i + "px"});
                }
                return objx;
            }
        }
    }

    $scope.selectDefaultOption = [
        {
            "key": "text-align", "items": [
                {"id": "left", "title": "ชิดซ้าย"},
                {"id": "center", "title": "กึ่งกลาง"},
                {"id": "right", "title": "ชิดขวา"}
            ]
        },
        {
            "key": "font-weight", "items": [
                {"id": "normal", "title": "ธรรมดา"},
                {"id": "bold", "title": "ตัวหนา"}
            ]
        },
        {
            "key": "selectroutes", "items": [
                {"id": "routes0001", "title": "Thank You Register"},
                {"id": "routes0002", "title": "Thank You OTP"},
                {"id": "routes0003", "title": "OTP"},
                {"id": "routes0004", "title": "iService"},
            ]
        },
        {
            "key": "selectfield", "items": [
                {"id": "1", "title": "user_fullname"},
                {"id": "2", "title": "user_age"},
                {"id": "3", "title": "user_gender"},
                {"id": "4", "title": "user_level"}
            ]
        },
        {
            "key": "padding", "items": [
                {"id": "0px", "title": "0px"},
                {"id": "5px", "title": "5px"},
                {"id": "10px", "title": "10px"},
                {"id": "15px", "title": "15px"},
                {"id": "20px", "title": "20px"},
                {"id": "25px", "title": "25px"},
                {"id": "30px", "title": "30px"},
                {"id": "35px", "title": "35px"},
                {"id": "40px", "title": "40px"},
                {"id": "45px", "title": "45px"},
                {"id": "50px", "title": "50px"}
            ]
        },
        {
            "key": "width", "items": [
                {"id": "10%", "title": "10%"},
                {"id": "20%", "title": "20%"},
                {"id": "30%", "title": "30%"},
                {"id": "40%", "title": "40%"},
                {"id": "50%", "title": "50%"},
                {"id": "60%", "title": "60%"},
                {"id": "70%", "title": "70%"},
                {"id": "80%", "title": "80%%"},
                {"id": "90%", "title": "90%"},
                {"id": "100%", "title": "100%"}
            ]
        },
        {
            "key": "font-size", "items": $scope.helper.init_default_select.fontsize()
        },
    ];

    $scope.settingDefaultElement = [
        {
            "type": "text",
            "item": [
                {"type": "text", "label": "ข้อความ", "ngmodel": "title"}
            ]
        },
        {
            "type": "textlink",
            "item": [
                {"type": "text", "label": "ข้อความ", "ngmodel": "title"},
                {"type": "text", "label": "URL Address", "ngmodel": "values"}
            ]
        },
        {
            "type": "textbox",
            "item": [
                {"type": "text", "label": "ข้อความ", "ngmodel": "title"}
            ]
        },
        {
            "type": "submit",
            "item": [
                {"type": "text", "label": "ข้อความ", "ngmodel": "title"},
                {"type": "text", "label": "URL Address", "ngmodel": "values"}
            ]
        }
    ];

    $scope.selected_values = {
        leadform_index : "-KgKGOtnjUChK-5Zadep" ,
        leadform_item_index: -1 ,
        ddl_item_list_add : "0"
    }

    $scope.form_add_item = function (){
      if($scope.selected_values.ddl_item_list_add != "0"){
        var queryResult = [];
        queryResult = Enumerable.From(LineSettingProfillingItemDataService.itemlistDefault)
                .Where(function (x) {
                    return x.type == $scope.selected_values.ddl_item_list_add
                })
                .Select(function (x) {
                    return x
                })
                .ToArray();

          if(queryResult.length > 0){
            var _new_id = queryResult[0].type+"_x_"+randomInt(1,99999);
            queryResult[0].id = _new_id;
            var _json = JSON.parse(angular.toJson(queryResult[0]));
            if($scope.resDataForm.item == undefined){
              $scope.resDataForm.item = [_json];
            }else{
              $scope.resDataForm.item.push(_json);
            }

          }
      }
    }

    $scope.eventx = {
        structure_click: function (index, $event) {

            $scope.selected_values.leadform_item_index = index;

            $($event.currentTarget).parent().find(".active").removeClass("active");
            $($event.currentTarget).addClass("active");

            var _temp = $scope.resDataForm.item[index];

            /*=== Render Setting Element ===*/
            var str_builder = "";
            str_builder += "<div class='row'><div class='col-md-12 div_form-control2'>";
            str_builder += $scope.template_string.settingDefaultElement({"label":"ชื่อวัตถุ" , "type" : "text"}, "resDataForm.item[" + index + "].title");
            str_builder += "</div></div>";

            $.each(_temp.setting, function (i, v) {
                str_builder += "<div class='row'><div class='col-md-12 div_form-control2'>";
                str_builder += $scope.template_string.settingDefaultElement(v, "resDataForm.item[" + index + "].setting[" + i + "].values");
                str_builder += "</div></div>";
            });

            $($scope.config.setting_table_id).html(str_builder);

            /*=== Render Style Element ===*/
            str_builder = "";
            $.each(_temp.css, function (i, v) {
                str_builder += "<div class='row'><div class='col-md-12 div_form-control2'>";
                str_builder += $scope.template_string.settingDefaultElement(v, "resDataForm.item[" + index + "].css[" + i + "].values");
                str_builder += "</div></div>";
            });
            $.each(_temp.parent_css, function (i, v) {
                str_builder += "<div class='row'><div class='col-md-12 div_form-control2'>";
                str_builder += $scope.template_string.settingDefaultElement(v, "resDataForm.item[" + index + "].parent_css[" + i + "].values");
                str_builder += "</div></div>";
            });

            if(_temp.label_css != undefined){
              if (_temp.label_css.length > 0) {
                  $.each(_temp.label_css, function (i, v) {
                      str_builder += "<div class='row'><div class='col-md-12 div_form-control2'>";
                      str_builder += $scope.template_string.settingDefaultElement(v, "resDataForm.item[" + index + "].label_css[" + i + "].values");
                      str_builder += "</div></div>";
                  });
              }
            }

            $($scope.config.setting_style_table_id).html(str_builder);

            /*=== Bind Event ===*/
            $compile($scope.config.setting_style_table_id + " input , " + $scope.config.setting_style_table_id + " select , " +$scope.config.setting_table_id + " input , " + $scope.config.setting_table_id + " select ")($scope);
            $scope.helper.init_colorPicker(".color_pickerx");

            /*=== Hightlight Element After Click ===*/
            $("#" + _temp.id).parent().addClass("shake animated");
            setTimeout(function () {
                $("#" + _temp.id).parent().removeClass("shake animated");
            }, 1500);
        },
        onchange_setting_and_style: function () {
            var str_builder = "";
            $.each($scope.resDataForm.item, function (i, v) {
                str_builder += $scope.template_string.mobilePreviewElement(v);
            });
            $($scope.config.mobile_preview_id).html(str_builder);
        },
        onclick_delete_item: function (index) {
            $scope.resDataForm.item.splice(index, 1);
            $($scope.config.setting_table_id).html("");
        }
    }

    $scope.template_string = {
        settingDefaultElement: function (v, xscope) {
            var str_builder = "";
            var select_data;

            if (v.type == "text") {
                str_builder += "<label>" + v.label + "</label>";
                str_builder += "<input type='text' class='form-control' ng-model='" + xscope + "' />";
            } else if (v.type == "colorpicker") {
                str_builder += "<label>" + v.label + "</label>";
                str_builder += '<input type="text" class="form-control color_pickerx" data-xvalue="' + v.values + '" data-control="hue" ng-model="' + xscope + '">';
            } else if (v.type == "select") {
                str_builder += $scope.reuseFunction.setting_select_template(v, $scope.selectDefaultOption, xscope);
            }

            return str_builder;
        },
        mobilePreviewElement: function (v) {
            var _css = "";
            var _parent_css = "";
            var _label_css = "";

            $.each(v.css, function (i2, v2) {
                _css += v2.key + ":" + v2.values + ";";
            });
            $.each(v.parent_css, function (i2, v2) {
                _parent_css += v2.key + ":" + v2.values + ";";
            });
            $.each(v.label_css, function (i2, v2) {
                _label_css += v2.key + ":" + v2.values + ";";
            });


            var str_builder = "";
            if (v.type == "text") {
                str_builder += "<div style='" + _parent_css + "float:left;'>";
                str_builder += "<p id='" + v.id + "' style='margin:0px;" + _css + "'>" + v.setting[0].values + "</p>";
                str_builder += "</div>";
            } else if (v.type == "textlink") {
                str_builder += "<div style='" + _parent_css + "float:left;'>";
                str_builder += "<a id='" + v.id + "' style='margin:0px;" + _css + "' href='" + v.setting[1].values + "' >" + v.setting[0].values + "</a>";
                str_builder += "</div>";
            } else if (v.type == "textbox") {
                str_builder += "<div style='" + _parent_css + "float:left;'>";
                str_builder += "<label style='" + _label_css + "'>" + v.setting[0].values + "</label><input type='text' id='" + v.id + "' class='form-control' style='" + _css + "' />";
                str_builder += "</div>";
            } else if (v.type == "submit") {
                str_builder += "<div style='" + _parent_css + "float:left;'>";
                str_builder += "<button class='btn' id='" + v.id + "' style='line-height: 1.44;" + _css + "'>" + v.setting[0].values + "</button>";
                str_builder += "</div>";
            }

            return str_builder;
        }
    }

    $scope.reuseFunction = {
        setting_select_template: function (v, json_data, xscope) {
            var str_builder = "";
            str_builder += "<label>" + v.label + "</label>";
            str_builder += "<select class='form-control' ng-model='" + xscope + "'>";

            select_data = Enumerable.From(json_data)
                    .Where(function (x) {
                        return x.key == v.key
                    })
                    .Select(function (x) {
                        return x
                    })
                    .ToArray();

            if (select_data.length > 0) {
                $.each(select_data[0].items, function (i2, v2) {
                    str_builder += "<option value='" + v2.id + "'>" + v2.title + "</option>";
                });
            }
            str_builder += "</select>";
            return str_builder;
        }
    }

    $scope.$watch('resDataForm.color_wallpaper', function (x, y) {
        $("#render_element").css("background-color", y);
    });

    $scope.$watch('resDataForm.item', $scope.eventx.onchange_setting_and_style, true);

    $scope.after_firebase_read = function (snapshot) {
        $scope.resDataForm = snapshot.val();
        if ($scope.config.isfirstTime == true) {
            $scope.$digest();
            $scope.config.isfirstTime = false;
        }
    }

    $scope.form_save = function(){
      var _json = JSON.parse(angular.toJson($scope.resDataForm));
      //var _data = $scope.resDataForm;
      LineSettingProfillingItemService.firebase_update($scope.selected_values.leadform_index,_json);
      swal("OK!", "Update Complete!", "success");
    }

    $timeout(function () {
        $scope.helper.init_colorPicker(".color_pickerx2");
        LineSettingProfillingItemService.firebase_read($scope.selected_values.leadform_index,$scope.after_firebase_read);
    }, 50);

});

function randomInt(min, range) {
    return Math.floor((Math.random() * (range + 1)) + min)
}

myApp.service('LineSettingProfillingItemService', function () {

  var vm = this;

  vm.firebaselink = "line/profilling";

  vm.firebase_get = function (callback) {
      firebasex.ref(vm.firebaselink).on('value', callback);
  }

  vm.firebase_read = function (_index , callback) {
      firebasex.ref(vm.firebaselink + "/" + _index).on('value', callback);
  }

  vm.firebase_insert = function (_data) {
      firebasex.ref(vm.firebaselink).push(_data);//.getKey();
  }

  vm.firebase_update = function (_index,_data) {
      firebasex.ref(vm.firebaselink + "/" + _index).set(_data);
  }

  vm.firebase_delete = function (_index) {
      firebasex.ref(vm.firebaselink + "/" + _index).remove();
  }

});

myApp.service('LineSettingProfillingItemDataService', function () {

  var vm = this;

  vm.randomInt = function(min, range) {
      return Math.floor((Math.random() * (range + 1)) + min)
  }

  vm.itemlistDefault = [
    {
      "type": "text", "id": "-", "title": "Text",
      "setting": [
          {"type": "text", "key": "", "values": "ขอบคุณที่ใช้บริการ", "label": "ข้อความ"}
      ],
      "css": [
          {"type": "colorpicker", "key": "color", "values": "#595959", "label": "สีตัวอักษร"},
          {"type": "select", "key": "font-size", "values": "14px", "label": "ขนาดตัวอักษร"},
          {"type": "select", "key": "text-align", "values": "left", "label": "การชิดตัวอักษร"}
      ],
      "parent_css": [
          {"type": "select", "key": "width", "values": "100%", "label": "ขนาด"},
          {"type": "select", "key": "padding", "values": "10px", "label": "ระยะห่างของขอบกับวัตถุ"},
          {"type": "colorpicker", "key": "background-color", "values": "#eaeaea", "label": "สีพื้นหลังของกรอบ"}
      ],
      "label_css": []
  },
  {
        "type": "textlink", "id": "-", "title": "Text Link",
        "setting": [
            {"type": "text", "key": "", "values": "Click", "label": "ข้อความ"},
            {"type": "text", "key": "", "values": "http://google.co.th", "label": "URL Address"}
        ],
        "css": [
            {"type": "colorpicker", "key": "color", "values": "#595959", "label": "สีตัวอักษร"},
            {"type": "select", "key": "font-size", "values": "14px", "label": "ขนาดตัวอักษร"}
        ],
        "parent_css": [
            {"type": "select", "key": "width", "values": "100%", "label": "ขนาด"},
            {"type": "select", "key": "padding", "values": "10px", "label": "ระยะห่างของขอบกับวัตถุ"},
            {"type": "colorpicker", "key": "background-color", "values": "#eaeaea", "label": "สีพื้นหลังของกรอบ"},
        ],
        "label_css": []
    },
    {
        "type": "textbox", "id": "-", "title": "Textbox",
        "setting": [
            {"type": "text", "key": "", "values": "ชื่อ - สกุล", "label": "หัวข้อ"},
            {"type": "select", "key": "selectfield", "values": "1", "label": "Field"}
        ],
        "css": [
            {"type": "colorpicker", "key": "color", "values": "#595959", "label": "สีตัวอักษร"},
            {"type": "select", "key": "font-size", "values": "14px", "label": "ขนาดตัวอักษร"}
        ],
        "parent_css": [
            {"type": "select", "key": "width", "values": "100%", "label": "ขนาด"},

            {"type": "select", "key": "padding", "values": "10px", "label": "ระยะห่างของขอบกับวัตถุ"},
            {"type": "colorpicker", "key": "background-color", "values": "#eaeaea", "label": "สีพื้นหลังของกรอบ"}
        ],
        "label_css": [
            {"type": "colorpicker", "key": "color", "values": "#595959", "label": "สีตัวอักษรของ Label"},
            {"type": "select", "key": "font-size", "values": "14px", "label": "ขนาดตัวอักษรของ Label"},
            {"type": "select", "key": "text-align", "values": "left", "label": "การชิดตัวอักษรของ Label"},
            {"type": "select", "key": "font-weight", "values": "normal", "label": "ความหนาของตัวอักษรของ Label"}
        ],
    },
    {
        "type": "submit", "id": "-", "title": "Submit Button",
        "setting": [
            {"type": "text", "key": "", "values": "Save", "label": "ข้อความ"},
            {"type": "select", "key": "selectroutes", "values": "routes0001", "label": "After Submit Redirect to"}
        ],
        "css": [
            {"type": "colorpicker", "key": "color", "values": "#565656", "label": "สีตัวอักษร"},
            {"type": "colorpicker", "key": "background-color", "values": "#ffd535", "label": "สีของปุ่ม"},
            {"type": "colorpicker", "key": "border-color", "values": "#e4bf33", "label": "สีขอบของปุ่ม"},
            {"type": "select", "key": "font-size", "values": "14px", "label": "ขนาดตัวอักษร"},
            {"type": "select", "key": "font-weight", "values": "normal", "label": "ความหนาของตัวอักษร"}
        ],
        "parent_css": [
            {"type": "select", "key": "width", "values": "100%", "label": "ขนาด"},
            {"type": "select", "key": "padding", "values": "10px", "label": "ระยะห่างของขอบกับวัตถุ"},
            {"type": "select", "key": "text-align", "values": "center", "label": "การชิดของปุ่ม"},
            {"type": "colorpicker", "key": "background-color", "values": "#eaeaea", "label": "สีพื้นหลังของกรอบ"}
        ],
        "label_css": []
    }
  ]

});


myApp.controller('SegmentController', function ($scope, $timeout) {
  $scope.page_title = "Segment";

  $scope.menuLine = [
    {"icon" : "icon-user" , link : "segment/core" , color : "bg-red" , type:"Core" , "title":"Segment"},

    {"icon" : "icon-folder" , link : "segment/folder" , color : "bg-green-meadow", type:"Folder" , "title":"Segment"}
  ];

  $scope.goto = function(link){
    location.href = link;
  }
    $timeout(function () {

    }, 50);

});

myApp.service('SegmentService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SegmentCoreController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Segment Core";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Segment Name"},
                {sTitle: "Subscriber"},
                {sTitle: "Count"},
                {sTitle: "Last Edit"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3", "c4"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SegmentCoreService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SegmentFolderController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Segment Folder";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Segment Folder Name"},
                {sTitle: "Total Segment"},
                {sTitle: "Total Subscribers"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SegmentFolderService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingController', function ($scope, $timeout) {
  $scope.page_title = "Setting";

  $scope.menuLine = [
    {"icon" : "icon-settings" , link : "setting/flag" , color : "bg-blue", type:"Setting" , "title":"Behaviour Flag"} ,
    {"icon" : "icon-settings" , link : "setting/field" , color : "bg-blue", type:"Setting" , "title":"Field"} ,
    {"icon" : "icon-settings" , link : "setting/subscriber" , color : "bg-blue", type:"Setting" , "title":"Subscriber Group"} ,
    {"icon" : "icon-settings" , link : "setting/otp" , color : "bg-blue", type:"Setting" , "title":"OTP"} ,
    {"icon" : "icon-settings" , link : "setting/location" , color : "bg-blue", type:"Setting" , "title":"Location"} ,
    {"icon" : "icon-settings" , link : "setting/image_personalization" , color : "bg-blue", type:"Setting" , "title":"Image Personalization"}
  ];

  $scope.goto = function(link){
    location.href = link;
  }

    $timeout(function () {

    }, 50);

});

myApp.service('SettingService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingFlagController', function ($scope, $timeout) {
  $scope.page_title = "Setting Flag";

    $timeout(function () {

    }, 50);

});

myApp.service('SettingFlagService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingFieldController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Setting Field";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Field Name"},
                {sTitle: "Field Type"},
                {sTitle: "Description"},
                {sTitle: "Personalize"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3", "c4"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SettingFieldService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingSubscriberController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Setting Subscriber Group";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Subscriber Group Name"},
                {sTitle: "Count"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SettingSubscriberService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingOtpController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Setting OTP";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "OTP Name"},
                {sTitle: "API OTP"},
                {sTitle: "OTP Time Limit"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SettingOtpService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingLocationController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Setting Location Group";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Location Group Name"},
                {sTitle: "Number"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SettingLocationService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('SettingImagePersonalizationController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Setting Image Personalization";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Image Name"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('SettingImagePersonalizationService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('AdminController', function ($scope, $timeout) {
  $scope.page_title = "Admin";

  $scope.menuLine = [
    {"icon" : "icon-settings" , link : "admin/user" , color : "bg-blue", type:"Setting" , "title":"User"} ,
    {"icon" : "icon-settings" , link : "admin/log" , color : "bg-blue", type:"Setting" , "title":"Log"} ,
    {"icon" : "icon-settings" , link : "admin/msg_error" , color : "bg-blue", type:"Setting" , "title":"Message Error"}
  ];

  $scope.goto = function(link){
    location.href = link;
  }

    $timeout(function () {

    }, 50);

});

myApp.service('AdminService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('AdminUserController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
  $scope.page_title = "Admin User";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Email"},
                {sTitle: "Name - Last Name"},
                {sTitle: "Position"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
      x1.install();
      x1.Get();
    }, 50);

});

myApp.service('AdminUserService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('AdminLogController', function ($scope, $timeout) {
  $scope.page_title = "Admin Log";

    $timeout(function () {

    }, 50);

});

myApp.service('AdminLogService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('AdminMessageErrorController', function ($scope, $timeout) {
  $scope.page_title = "Admin Message Error";

    $timeout(function () {

    }, 50);

});

myApp.service('AdminMsg_errorService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});

myApp.controller('LineCampaginTrigerController', function ($scope, $compile, $timeout, HttpCRUDService, HelperDatatablesService) {
    $scope.page_title = "Line Trigger Campagin";

    var x1 = $scope.crud = {
        onGet: true,
        onCreate: false,
        onUpdate: false,
        install: function () {
            HelperDatatablesService.DataTables.table_id = "#exampleDT";
            HelperDatatablesService.DataTables.option.aoColumns = [
                {sTitle: "#", sWidth: "5%"},
                {sTitle: "Campaign Name"},
                {sTitle: "Segment"},
                {sTitle: "Sent Time"},
                {sTitle: "Message Type"},
                {sTitle: "Status"},
                {sTitle: "Last Sent"},
                {sTitle: "Action", sWidth: "7%"}
            ];
            HelperDatatablesService.DataTables.datafield = ["c1", "c2", "c3", "c4", "c5", "c6"];
            HelperDatatablesService.DataTables.pkid = "idx";
            HelperDatatablesService.DataTables.btnAction = function (index) {
                var str_builder = "";

                str_builder += '<div class="btn-group">';
                str_builder += '<button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Action ';
                str_builder += '<i class="fa fa-angle-down"></i>';
                str_builder += '</button>';
                str_builder += '<ul class="dropdown-menu pull-left" role="menu" style="left:-117px;">';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.form.open_edit_form(\'' + index + '\')">';
                str_builder += '<i class="fa fa-pencil-square-o"></i> Edit </a>';
                str_builder += '</li>';

                str_builder += '<li>';
                str_builder += '<a href="javascript:;" class="ngCRUDAction" ng-click="crud.beforeDelete(\'' + index + '\')">';
                str_builder += '<i class="fa fa-trash"></i> Delete </a>';
                str_builder += '</li>';

                str_builder += '</ul>';
                str_builder += '</div>';

                return str_builder;
            };
            HelperDatatablesService.DataTables.init();
            HelperDatatablesService.DataTables.init_event($compile, $scope);

            //TODO DEMO
            HelperDatatablesService.DataTables.array_list = [
                {idx: "1", c1: "Demo1", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "2", c1: "Demo2", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"},
                {idx: "3", c1: "Demo3", c2: "Demo(0)", c3: "01/02/2017 14:00", c4: "Rich Message", c5: "Pendding", c6: "-"}
            ];
        },
        beforeGet: function () {

        },
        Get: function () {
            var res = HelperDatatablesService.DataTables.array_list; //DEMO
            x1.afterGet(res);
        },
        afterGet: function (json) {
            //HelperDatatablesService.DataTables.array_list = json;
            HelperDatatablesService.DataTables.render_data(1, 1);
        },
        beforeRead: function () {

        },
        Read: function (id) {
            x1.form.input = HelperDatatablesService.DataTables.array_list[HelperDatatablesService.DataTables.selected.index];
        },
        afterRead: function () {

        },
        beforeCreate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Create();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Create: function () {
            x1.afterCreate();
        },
        afterCreate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeUpdate: function () {
            var valid_form = true;
            if (valid_form) {
                x1.Update();
            } else {
                swal("Warnning!", "Not Validate!", "warnning");
            }
        },
        Update: function () {
            x1.afterUpdate();
        },
        afterUpdate: function () {
            swal("Success!", "Save Compelete!", "success");
            x1.Get();
        },
        beforeDelete: function (index) {
            swal({
                title: "Confirm Delete?",
                text: "Do you want to delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                x1.Delete(index);
            });

        },
        Delete: function (index) {
            delete HelperDatatablesService.DataTables.array_list[index];
            HelperDatatablesService.DataTables.array_list.length--;
            x1.afterDelete();
        },
        afterDelete: function () {
            HelperDatatablesService.DataTables.selected.index = -1;
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            x1.Get();
        },
        form: {
            input: {},
            clear_form: function () {
                x1.form.input = {idx: "0", c1: "", c2: "", c3: "", c4: "", c5: "", c6: ""}
            },
            open_add_form: function () {
                x1.onCreate = true;
                x1.onGet = false;
                x1.onUpdate = false;

                x1.form.clear_form();
            },
            open_edit_form: function (index) {
                HelperDatatablesService.DataTables.selected.index = index;
                var id = HelperDatatablesService.DataTables.array_list[index][HelperDatatablesService.DataTables.pkid];
                x1.Read(id);
                x1.onCreate = false;
                x1.onGet = false;
                x1.onUpdate = true;
            },
            save_form: function () {
                if (x1.onCreate == true && x1.onUpdate == false && x1.onGet == false) {
                    x1.beforeCreate();
                } else if (x1.onCreate == false && x1.onUpdate == true && x1.onGet == false) {
                    x1.beforeUpdate();
                }
            },
            close_form: function () {
                x1.onCreate = false;
                x1.onGet = true;
                x1.onUpdate = false;
            }
        }
    }

    $timeout(function () {
        x1.install();
        x1.Get();

    }, 50);

});

myApp.service('LineCampaginTrigerService', function ($http , $q) {

    $timeout(function () {

    }, 50);

});
