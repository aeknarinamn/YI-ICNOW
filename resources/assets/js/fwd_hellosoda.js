$(function () {
  HelloSoda.init({
    productId: "568e4591d045f29af5fadd53",
    buttons: true,
    form: '#hs-form'
  }, [
			{formfield: 'application_id', api: 'application_id'}
		]);

    var socialnetworks = [
        ["facebook", "Facebook"],
        ["twitter", "Twitter"],
        ["linkedin", "LinkedIn"],
        ["instagram", "Instagram"],
        ["google", "Google+"],
        ["weibo", "Weibo"]
    ];

    $.each(socialnetworks, function (index, socialnetwork) {
        var networkId = socialnetwork[0];
        var networkName = socialnetwork[1];
        $("#" + networkId + "_connect").attr("data-button-text", "connect").attr("data-button-connected-text", "connected");
    });
 /*
    setTimeout(function () {
        //$(".social-box").fadeToggle();
    }, 2000);

    window.onload = function () {

        HelloSoda.init({
            productId: "5697601c314613c7342c30b9",
            buttons: true,
            form: '#hs-form',
            events: {
                onDashboardActive: function () {
                    $("#btn_continue").removeAttr("disabled");
                    $(".connected-message").removeClass("hidden");
                }
            }
            //commit: true,
            //log: 'debug'
        }, [
            {formfield: 'application_id', api: 'application_id'}
        ]);

    };

    var socialnetworks = [
        ["facebook", "Facebook"],
        ["twitter", "Twitter"],
        ["linkedin", "LinkedIn"],
        ["instagram", "Instagram"],
        ["google", "Google+"],
        ["weibo", "Weibo"]
    ];

    $.each(socialnetworks, function (index, socialnetwork) {
        var networkId = socialnetwork[0];
        var networkName = socialnetwork[1];
        $("#" + networkId + "_connect").attr("data-button-text", "connect").attr("data-button-connected-text", "connected");
    });

});


HelloSoda.preinit({
  "productId": "568e4591d045f29af5fadd53",
  "client": {"domains":"*","csrfToken":"058bc1dc-9d5d-42a3-9d1d-06b493e4a23b","instant_logout":false,"loadNavigatorIo":false,"oauthLanding":true},
  "server": {
    "api":"https://profile-v2.hellosoda.com",
    "public":"https://profile-v2.hellosoda.com",
    "is_live":"false"
  },
  "connections": {
    "weibo":{
      "app_id":"249027866",
      "enabled":true,
      "dom_id":"#weibo_connect",
      "scope":"email"
    },
    "linkedin":{
      "app_id":"755hqcxva1me2c",
      "enabled":true,
      "dom_id"
      :"#linkedin_connect",
      "scope":"r_basicprofile r_emailaddress"
    },"google":{
      "app_id":"284423710126-knjvunteeegbepigmi0g2ap6p86mu3cp.apps.googleusercontent.com",
      "enabled":true,
      "dom_id":"#google_connect",
      "scope":"email profile https://www.googleapis.com/auth/plus.login"
    },"facebook":{
      "app_id":"513189105536527",
      "enabled":true,"dom_id":
      "#facebook_connect","scope":"user_about_me,user_birthday,user_education_history,user_hometown,user_likes,user_location,user_photos,user_status,user_work_history,user_posts,user_friends,email"},"twitter":{"app_id":"IJaaGaH6bnwvJlPkNPDCK5y0P","enabled":true,"dom_id":"#twitter_connect","scope":""},"instagram":{"app_id":"5e5302f7fd2d480cb36aa2996a0fab54","enabled":true,"dom_id":"#instagram_connect","scope":"basic"}},
  "helpers": {
    "build_job_url": function(job_id) {
        if (typeof(job_id) !== "string") {
            job_id = "";
        }

        return "https://profile-v2.hellosoda.com/api/v1/jobs/" + job_id;
    },
    "build_simple_status_url": function(job_id) {
        return "https://profile-v2.hellosoda.com/api/v1/jobs/"
            + (job_id || "")
            + "/simple_status";
    },
    "build_report_url": function(job_id) {
        return "https://profile-v2.hellosoda.com/reports/" + job_id;
    }
  }
  */
});
