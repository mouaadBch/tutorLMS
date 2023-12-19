<!DOCTYPE html>

<head>
    <title><?php echo get_phrase('Live class'); ?> | <?php echo get_settings('system_title'); ?></title>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="<?php echo base_url('uploads/system/').get_frontend_settings('favicon');?>">

    <?php include APPPATH.'views/backend/includes_top.php'; ?>

</head>

<body>

    <?php
        $course_details = $this->crud_model->get_course_by_id($param2)->row_array();
        $live_class_details = $this->db->where('course_id', $param2)->get('live_class');

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
    
    <form class="navbar-form navbar-right" id="meeting_form">
        <div class="form-group d-none">
            <input type="text" name="display_name" id="display_name" value="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>" maxLength="100"
                placeholder="Name" class="form-control" required>
        </div>
        <div class="form-group d-none">
            <input type="text" name="meeting_number" id="meeting_number" value="<?php echo $live_class_details->row('zoom_meeting_id'); ?>" maxLength="200"
                style="width:150px" placeholder="Meeting Number" class="form-control" required>
        </div>
        <div class="form-group d-none">
            <input type="text" name="meeting_pwd" id="meeting_pwd" value="<?php echo $live_class_details->row('zoom_meeting_password'); ?>" style="width:150px"
                maxLength="32" placeholder="Meeting Password" class="form-control">
        </div>
        <div class="form-group d-none">
            <input type="text" name="meeting_email" id="meeting_email" value="<?php echo $user_details['email']; ?>" style="width:150px"
                maxLength="32" placeholder="Email option" class="form-control">
        </div>

        <div class="form-group d-none">
            <select id="meeting_role" class="sdk-select">
                <option value="<?php echo $is_host; ?>">Host</option>
            </select>
        </div>
        <div class="form-group d-none">
            <select id="meeting_china" class="sdk-select">
                <option value=0>Global</option>
                <!-- <option value=1>China</option> -->
            </select>
        </div>
        <div class="form-group d-none">
            <select id="meeting_lang" class="sdk-select">
                <option value="en-US">English</option>
                <!-- <option value="de-DE">German Deutsch</option>
                <option value="es-ES">Spanish Español</option>
                <option value="fr-FR">French Français</option>
                <option value="jp-JP">Japanese 日本語</option>
                <option value="pt-PT">Portuguese Portuguese</option>
                <option value="ru-RU">Russian Русский</option>
                <option value="zh-CN">Chinese 简体中文</option>
                <option value="zh-TW">Chinese 繁体中文</option>
                <option value="ko-KO">Korean 한국어</option>
                <option value="vi-VN">Vietnamese Tiếng Việt</option>
                <option value="it-IT">Italian italiano</option> -->
            </select>
        </div>

        <input type="hidden" value="" id="copy_link_value" />

        <div class="row my-5">
            <div class="col-md-12 text-center">
                <h5 class="py-4">Are you sure?</h5>
            </div>
            <div class="col-md-12 text-center">
                <button type="button" link="" onclick="window.copyJoinLink('#copy_join_link')" class="btn btn-success" id="copy_join_link"><?php echo get_phrase('Copy link'); ?></button>
                <button class="btn">Or</button>
                <button type="submit" class="btn btn-success" id="join_meeting">Join Now</button>
            </div>
        </div>
    </form>

    <script src="https://source.zoom.us/2.18.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.18.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.18.0.min.js"></script>

    <script src="<?php echo site_url('assets/lessons/zoom-web-sdk-cdn/js/tool.js'); ?>"></script>
    <script src="<?php echo site_url('assets/lessons/zoom-web-sdk-cdn/js/vconsole.min.js'); ?>"></script>

    <script type="text/javascript">
        const setIntervalCalBack = setInterval(function() {
        var zmmtg_root = $('#zmmtg-root');

        if (zmmtg_root.length > 0) {
        zmmtg_root.remove();
        }else{
        clearInterval(setIntervalCalBack);
        }
        }, 1000);




        window.addEventListener('DOMContentLoaded', function(event) {
          console.log('DOM fully loaded and parsed');
          websdkready();
        });

        function websdkready() {
          var testTool = window.testTool;
          if (testTool.isMobileDevice()) {
            vConsole = new VConsole();
          }
          console.log("checkSystemRequirements");
          console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

          // it's option if you want to change the WebSDK dependency link resources. setZoomJSLib must be run at first
          // if (!china) ZoomMtg.setZoomJSLib('https://source.zoom.us/2.18.0/lib', '/av'); // CDN version default
          // else ZoomMtg.setZoomJSLib('https://jssdk.zoomus.cn/2.18.0/lib', '/av'); // china cdn option
          // ZoomMtg.setZoomJSLib('http://localhost:9999/node_modules/@zoomus/websdk/dist/lib', '/av'); // Local version default, Angular Project change to use cdn version
          ZoomMtg.preLoadWasm(); // pre download wasm file to save time.

          var CLIENT_ID = "<?php echo $credentials->row('client_id'); ?>";
          /**
           * NEVER PUT YOUR ACTUAL SDK SECRET OR CLIENT SECRET IN CLIENT SIDE CODE, THIS IS JUST FOR QUICK PROTOTYPING
           * The below generateSignature should be done server side as not to expose your SDK SECRET in public
           * You can find an example in here: https://developers.zoom.us/docs/meeting-sdk/auth/#signature
           */
          var CLIENT_SECRET = "<?php echo $credentials->row('client_secret'); ?>";

          // some help code, remember mn, pwd, lang to cookie, and autofill.
          // document.getElementById("display_name").value =
          //   "CDN" +
          //   ZoomMtg.getWebSDKVersion()[0] +
          //   testTool.detectOS() +
          //   "#" +
          //   testTool.getBrowserInfo();
          // document.getElementById("meeting_number").value = testTool.getCookie(
          //   "meeting_number"
          // );
          // document.getElementById("meeting_pwd").value = testTool.getCookie(
          //   "meeting_pwd"
          // );
          // if (testTool.getCookie("meeting_lang"))
          //   document.getElementById("meeting_lang").value = testTool.getCookie(
          //     "meeting_lang"
          //   );

          // document
          //   .getElementById("meeting_lang")
          //   .addEventListener("change", function (e) {
          //     testTool.setCookie(
          //       "meeting_lang",
          //       document.getElementById("meeting_lang").value
          //     );
          //     testTool.setCookie(
          //       "_zm_lang",
          //       document.getElementById("meeting_lang").value
          //     );
          //   });
          // copy zoom invite link to mn, autofill mn and pwd.
          // document
          //   .getElementById("meeting_number")
          //   .addEventListener("input", function (e) {
          //     var tmpMn = e.target.value.replace(/([^0-9])+/i, "");
          //     if (tmpMn.match(/([0-9]{9,11})/)) {
          //       tmpMn = tmpMn.match(/([0-9]{9,11})/)[1];
          //     }
          //     var tmpPwd = e.target.value.match(/pwd=([\d,\w]+)/);
          //     if (tmpPwd) {
          //       document.getElementById("meeting_pwd").value = tmpPwd[1];
          //       testTool.setCookie("meeting_pwd", tmpPwd[1]);
          //     }
          //     document.getElementById("meeting_number").value = tmpMn;
          //     testTool.setCookie(
          //       "meeting_number",
          //       document.getElementById("meeting_number").value
          //     );
          //   });


          // click join meeting button
          document
            .getElementById("join_meeting")
            .addEventListener("click", function (e) {
              e.preventDefault();
              var meetingConfig = testTool.getMeetingConfig();

              testTool.setCookie("meeting_number", meetingConfig.mn);
              testTool.setCookie("meeting_pwd", meetingConfig.pwd);

              var signature = ZoomMtg.generateSDKSignature({
                meetingNumber: meetingConfig.mn,
                sdkKey: CLIENT_ID,
                sdkSecret: CLIENT_SECRET,
                role: meetingConfig.role,
                success: function (res) {
                  meetingConfig.signature = res.result;
                  meetingConfig.sdkKey = CLIENT_ID;
                  var joinUrl = "<?php echo site_url('addons/liveclass/join/'.$param2.'?course_id='.$param2); ?>?" + testTool.serialize(meetingConfig);
                  window.location.replace(joinUrl);
                },
              });
            });

          function copyToClipboard(elementId) {
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(elementId).getAttribute('link'));
            document.body.appendChild(aux);  
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
          }
            
          // click copy jon link button
          window.copyJoinLink = function (element) {
            var meetingConfig = testTool.getMeetingConfig();
            if (!meetingConfig.mn || !meetingConfig.name) {
              alert("Meeting number or username is empty");
              return false;
            }
            var signature = ZoomMtg.generateSDKSignature({
              meetingNumber: meetingConfig.mn,
              sdkKey: CLIENT_ID,
              sdkSecret: CLIENT_SECRET,
              role: meetingConfig.role,
              success: function (res) {
                meetingConfig.signature = res.result;
                meetingConfig.sdkKey = CLIENT_ID;
                var joinUrl =
                  testTool.getCurrentDomain() +
                  "<?php echo site_url('addons/liveclass/join/'.$param2.'?course_id='.$param2); ?>?" +
                  testTool.serialize(meetingConfig);
                document.getElementById('copy_link_value').setAttribute('link', joinUrl);
                copyToClipboard('copy_link_value');
                document.getElementById('copy_join_link').innerHTML = 'Copyed! link';
                
              },
            });
          };

        }

    </script>
</body>

</html>