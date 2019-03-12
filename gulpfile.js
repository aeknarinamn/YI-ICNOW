var elixir = require('laravel-elixir');
elixir.config.sourcemaps = true;
elixir(function(mix) {

    mix.scripts([
      'ang_build/vendor.js',
      'ang_build/polyfills.js',
      'ang_build/app.js'
    ],'public/js/application.js');
    mix.version('public/js/application.js');

    /*
    mix.scripts([
      'fwd_hellosoda.js'
    ],'public/assets/hello_soda/fwd_hellosoda.js');
    mix.version('public/assets/hello_soda/fwd_hellosoda.js');
     */

});
/*
elixir(function(mix) {
    var main_path = "../../angular/component_main/";
    mix.scripts([
      main_path+'_library/*.js',

      main_path+'home/controller.js',
      main_path+'home/service.js',
      main_path+'line/controller.js',
      main_path+'line/service.js',
      main_path+'line_campagin/controller.js',
      main_path+'line_campagin/service.js',
      main_path+'line_autoreply_defualt/controller.js',
      main_path+'line_autoreply_defualt/service.js',
      main_path+'line_autoreply_keyword/controller.js',
      main_path+'line_autoreply_keyword/service.js',
      main_path+'line_chatsupport/controller.js',
      main_path+'line_chatsupport/service.js',
      main_path+'line_folder_campagin/controller.js',
      main_path+'line_folder_campagin/service.js',
      main_path+'line_folder_autoreply/controller.js',
      main_path+'line_folder_autoreply/service.js',
      main_path+'line_setting_token/controller.js',
      main_path+'line_setting_token/service.js',
      main_path+'line_setting_report_tag/controller.js',
      main_path+'line_setting_report_tag/service.js',
      main_path+'line_setting_profilling/controller.js',
      main_path+'line_setting_profilling/service.js',
      main_path+'line_setting_profilling/directive.js',
      main_path+'segment/controller.js',
      main_path+'segment/service.js',
      main_path+'segment_core/controller.js',
      main_path+'segment_core/service.js',
      main_path+'segment_folder/controller.js',
      main_path+'segment_folder/service.js',
      main_path+'setting/controller.js',
      main_path+'setting/service.js',
      main_path+'setting_flag/controller.js',
      main_path+'setting_flag/service.js',
      main_path+'setting_field/controller.js',
      main_path+'setting_field/service.js',
      main_path+'setting_subscriber/controller.js',
      main_path+'setting_subscriber/service.js',
      main_path+'setting_otp/controller.js',
      main_path+'setting_otp/service.js',
      main_path+'setting_location/controller.js',
      main_path+'setting_location/service.js',
      main_path+'setting_image_personalization/controller.js',
      main_path+'setting_image_personalization/service.js',
      main_path+'admin/controller.js',
      main_path+'admin/service.js',
      main_path+'admin_user/controller.js',
      main_path+'admin_user/service.js',
      main_path+'admin_log/controller.js',
      main_path+'admin_log/service.js',
      main_path+'admin_msg_error/controller.js',
      main_path+'admin_msg_error/service.js',
      main_path+'line_campagin_triger/controller.js',
      main_path+'line_campagin_triger/service.js',
    ],
      'public/js/angular_component_main.js');

    mix.version('public/js/angular_component_main.js');

    mix.sass([
        'app.scss'
    ], 'public/testcss');
});
*/
