<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Web View -->
    <title>Yellow Idea V2</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Yellow idea V2" name="description" />
    <meta content=name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="hostname" content="{{ Request::root() }}">
    <meta name="comeback_url" content="{{ Request::root() }}/cms-lineofficialadmin"> 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/animate/animate.css') ?>" rel="stylesheet"/>
    <link href="<?= asset('assets/global/css/components.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/jquery-select-areas/resources/jquery.selectareas.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/simple-line-icons/simple-line-icons.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/pages/css/error.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/jstree/dist/themes/default/style.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/datatables/datatables.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') ?>" rel="stylesheet" type="text/css" />

    <link href="<?= asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') ?>" rel="stylesheet" type="text/css" />

    <link href="<?= asset('assets/global/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/css/components.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/css/plugins.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/layouts/layout/css/layout.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/layouts/layout/css/themes/darkblue.css') ?>" rel="stylesheet">

    <!--2017-07-02-->
    <link href="<?= asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/bootstrap-summernote/summernote.css') ?>" rel="stylesheet" type="text/css" />

     <!--2017-07-22-->
    <link href="<?= asset('assets/global/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css') ?>" rel="stylesheet" type="text/css" />

    <!--2017-08-08-->
    <link href="<?= asset('assets/global/plugins/bootstrap-table/bootstrap-table.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

    <!--[if lt IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="page-wrapper">
          <headerbar></headerbar>
          <div class="clearfix"> </div>
          <div class="page-container">
              <sidebar></sidebar>
              <!-- BEGIN CONTENT -->
              <div class="page-content-wrapper">

                  <!-- BEGIN CONTENT BODY -->
                  <div ui-view="" class="page-content"></div>
                  <!-- END CONTENT BODY -->
              </div>
              <!-- END CONTENT -->
              <quicksidebar></quicksidebar>
          </div>
      </div>
      <div id="list_permission"></div>
      
    <div>
        <?php 
            $datas = [];
            $auth  = Auth::user();
            $rolePermission = $auth->rolePermission;
            //$datas = $auth->toArray();
            if($rolePermission){
                //$datas['id'] = $rolePermission->id;
                //$datas['name'] = $rolePermission->name;
                $rolePermissionItems = $rolePermission->permissionRoleItems;
                if($rolePermissionItems){
                    foreach ($rolePermissionItems as $index => $rolePermissionItem) {
                        //$datas[$index]['id'] = $rolePermissionItem->id;
                        //$datas[$index]['menu_id'] = $rolePermissionItem->menu_id;
                        //$datas[$index]['role_id'] = $rolePermissionItem->role_id;
                        //$datas[$index]['access_id'] = $rolePermissionItem->access_id;
                        //$datas[$index]['active'] = $rolePermissionItem->is_active;
                        // if($rolePermissionItem->is_active==1 && $rolePermissionItem->access_id>-1){
                            $datas[$index]['id'] = $rolePermissionItem->id;
                            $datas[$index]['menu_id'] = $rolePermissionItem->menu_id;
                            if($rolePermissionItem->access_id==2){
                                $datas[$index]['action'] = "page_access";
                            }else{
                                $datas[$index]['action'] = $rolePermissionItem->access_id;
                            }
                            $datas[$index]['active'] = $rolePermissionItem->is_active;
                        // }
                        $datas[$index]['action'] = "page_access";
                    }
                }
            }
            $datas = json_encode($datas);
        ?>
        <input type="hidden" id="AIzaSyDdGhYKH0oS9h3qpV-dtKuIwkN5Y_ZhYn0Key" value="yellowidea">
        <input type="hidden" id="hid_project_typp" value="Core">
        <input type="hidden" id="hid_position" value="">
        <input type="hidden" id="hid_userprofile" value="{{ Auth::user() }}">
        <input type="hidden" id="hid_xxxaosdkwla2" value="{{ Auth::user()->id }}">
        {{-- <input type="hidden" id="xhid_permission" value="{{ Auth::user()->userPermissionRoles }}"> --}}
        <input type="hidden" id="hid_permission" value="{{ $datas }}">
    </div>
    <!--[if lt IE 9]>
    <script src="<?= asset('assets/global/plugins/respond.min.js') ?>"></script>
    <script src="<?= asset('assets/global/plugins/excanvas.min.js') ?>"></script>
    <script src="<?= asset('assets/global/plugins/ie8.fix.min.js') ?>"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?= asset('js/jqueryx.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery-ui.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery.csv.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery-select-areas/jquery.selectareas.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/firebase.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') ?>"type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/js.cookie.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>"type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/linq.js') ?>" type="text/javascript"></script>
    <!-- DATATABLE PLUGINS -->
    <script src="<?= asset('assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>

    <script src="<?= asset('assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/moment.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"  type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') ?>"  type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" type="text/javascript"></script>

    <script src="<?= asset('assets/global/plugins/counterup/jquery.waypoints.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/counterup/jquery.counterup.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/amcharts.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/serial.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/pie.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/radar.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/themes/light.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/themes/patterns.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amcharts/themes/chalk.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/ammap/ammap.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/amcharts/amstockcharts/amstock.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/horizontal-timeline/horizontal-timeline.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/flot/jquery.flot.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/flot/jquery.flot.pie.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/flot/jquery.flot.resize.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/flot/jquery.flot.categories.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery.sparkline.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js') ?>" type="text/javascript"></script>


    <script src="<?= asset('assets/global/plugins/select2/js/select2.full.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/pages/scripts/components-bootstrap-switch.min.js') ?>"= type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js') ?>" type="text/javascript"></script>

    <script src="<?= asset('assets/global/plugins/jstree/dist/jstree.min.js') ?>"type="text/javascript"></script>
    <script src="<?= asset('assets/global/scripts/app.min.js') ?>" type="text/javascript"></script>

    <script src="<?= asset('assets/layouts/layout/scripts/layout.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/layouts/layout/scripts/demo.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/layouts/global/scripts/quick-sidebar.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/layouts/global/scripts/quick-nav.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/clipboard.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/dmuploader.min.js') ?>" type="text/javascript"></script>

    <!--2017-07-02-->
    <script src="<?= asset('assets/global/plugins/bootstrap-markdown/lib/markdown.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-summernote/summernote.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput2.js') ?>" type="text/javascript"></script>
    <!--2017-07-11-->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHKwJb9QKjnI9M0KUOVwosdF7JqVXO_Kc">
    </script>
     <!--2017-07-22-->
    <script src="<?= asset('assets/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') ?>" type="text/javascript"></script>

    <!--2017-08-08-->
    <script src="<?= asset('assets/global/plugins/bootstrap-table/bootstrap-table.min.js') ?>" type="text/javascript"></script>
    
    <!--2017-10-20-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/funnel.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script src="<?= elixir('js/application.js') ?>" type="text/javascript"></script>
    <script type="text/javascript">
        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            if(antiClickjack){
                antiClickjack.parentNode.removeChild(antiClickjack);
            }
        } else {
            top.location = self.location;
        }
    </script>
  </body>
</html>
