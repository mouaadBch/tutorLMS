<!DOCTYPE html>
<head>
    <title><?php echo get_phrase('Live class'); ?> | <?php echo get_settings('system_title'); ?></title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.18.0/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.18.0/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="origin-trial" content="">
    <link rel="shortcut icon" href="<?php echo base_url('uploads/system/').get_frontend_settings('favicon');?>">
</head>

<body>
    <?php
        $user_id = $this->session->userdata('user_id');
        $user_details = $this->user_model->get_all_user($user_id)->row_array();
        $credentials = $this->db->where('user_id', $course_details['creator'])->get('   zoom_live_class_settings');

        if($this->crud_model->is_course_instructor($course_details['id'], $user_id) || $this->session->userdata('admin_login')){
            $is_host = 1;
        }elseif(enroll_status($course_details['id']) == 'valid'){
            $is_host = 0;
        }else{
            $this->session->flashdata('error_message', get_phrase('You do not have access to this course'));
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    ?>

    <?php if($is_host == 0): ?>
        <style type="text/css">
            .ax-outline-blue-important:first-child{
                display: none !important;
            }
        </style>
    <?php endif; ?>

    <script src="https://source.zoom.us/2.18.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.18.0.min.js"></script>
    <script src="<?php echo site_url('assets/lessons/zoom-web-sdk-cdn/js/tool.js'); ?>"></script>
    <script src="<?php echo site_url('assets/lessons/zoom-web-sdk-cdn/js/vconsole.min.js'); ?>"></script>

    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', function(event) {
          console.log('DOM fully loaded and parsed');
          websdkready();
        });

        function websdkready() {
          var testTool = window.testTool;
          // get meeting args from url
          var tmpArgs = testTool.parseQuery();
          var meetingConfig = {
            sdkKey: tmpArgs.sdkKey,
            meetingNumber: tmpArgs.mn,
            userName: (function () {
              if (tmpArgs.name) {
                try {
                  return testTool.b64DecodeUnicode(tmpArgs.name);
                } catch (e) {
                  return tmpArgs.name;
                }
              }
              return (
                // "CDN#" +
                // tmpArgs.version +
                // "#" +
                // testTool.detectOS() +
                // "#" +
                // testTool.getBrowserInfo()
                '<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>'
              );
            })(),
            passWord: tmpArgs.pwd,
            leaveUrl: "<?php echo $leave_url; ?>",
            role: parseInt(tmpArgs.role, 10),
            userEmail: (function () {
              try {
                return testTool.b64DecodeUnicode(tmpArgs.email);
              } catch (e) {
                return tmpArgs.email;
              }
            })(),
            lang: tmpArgs.lang,
            signature: tmpArgs.signature || "",
            china: tmpArgs.china === "1",
          };

          // a tool use debug mobile device
          if (testTool.isMobileDevice()) {
            vConsole = new VConsole();
          }
          console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

          // it's option if you want to change the WebSDK dependency link resources. setZoomJSLib must be run at first
          // ZoomMtg.setZoomJSLib("https://source.zoom.us/2.18.0/lib", "/av"); // CDN version defaul
          if (meetingConfig.china)
            ZoomMtg.setZoomJSLib("https://jssdk.zoomus.cn/2.18.0/lib", "/av"); // china cdn option
          ZoomMtg.preLoadWasm();
          ZoomMtg.prepareJssdk();
          function beginJoin(signature) {
            ZoomMtg.init({
              leaveUrl: meetingConfig.leaveUrl,
              webEndpoint: meetingConfig.webEndpoint,
              disableCORP: !window.crossOriginIsolated, // default true
              // disablePreview: false, // default false
              //externalLinkPage: './externalLinkPage.html',
              success: function () {
                console.log(meetingConfig);
                console.log("signature", signature);
                ZoomMtg.i18n.load(meetingConfig.lang);
                ZoomMtg.i18n.reload(meetingConfig.lang);
                ZoomMtg.join({
                  meetingNumber: meetingConfig.meetingNumber,
                  userName: meetingConfig.userName,
                  signature: signature,
                  sdkKey: meetingConfig.sdkKey,
                  userEmail: meetingConfig.userEmail,
                  passWord: meetingConfig.passWord,
                  success: function (res) {
                    console.log("join meeting success");
                    console.log("get attendeelist");
                    ZoomMtg.getAttendeeslist({});
                    ZoomMtg.getCurrentUser({
                      success: function (res) {
                        console.log("success getCurrentUser", res.result.currentUser);
                      },
                    });
                  },
                  error: function (res) {
                    console.log(res);
                  },
                });
              },
              error: function (res) {
                console.log(res);
              },
            });

            ZoomMtg.inMeetingServiceListener('onUserJoin', function (data) {
              console.log('inMeetingServiceListener onUserJoin', data);
            });
          
            ZoomMtg.inMeetingServiceListener('onUserLeave', function (data) {
              console.log('inMeetingServiceListener onUserLeave', data);
            });
          
            ZoomMtg.inMeetingServiceListener('onUserIsInWaitingRoom', function (data) {
              console.log('inMeetingServiceListener onUserIsInWaitingRoom', data);
            });
          
            ZoomMtg.inMeetingServiceListener('onMeetingStatus', function (data) {
              console.log('inMeetingServiceListener onMeetingStatus', data);
            });
          }

          beginJoin(meetingConfig.signature);
        };

    </script>

</body>
</html>