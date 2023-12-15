<div id="jitsiMeet"></div>
<script src="<?php echo base_url('assets/backend/js/jquery-3.3.1.min.js'); ?>" charset="utf-8"></script>
<script src="<?php echo base_url('assets/global/jitsi/jitsi.js'); ?>"></script>

<script src='https://8x8.vc/vpaas-magic-cookie-36cdf099c8cc4ba1b4d57977db5d15d1/external_api.js' async></script>
<!-- check moderator or student -->
<?php if($this->session->userdata('user_id') == $course_details['user_id'] || $this->session->userdata('role_id') == 1): ?>
    <script type="text/javascript">
        window.onload = () => {
            //8x8.vc,vpaas-magic-cookie-36cdf099c8cc4ba1b4d57977db5d15d1/
            const api = new JitsiMeetExternalAPI("meet.jit.si", {
                roomName: "<?php echo get_settings('system_name');?> - <?php echo $live_class_details['class_topic'];?>",
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#jitsiMeet'),
                // Make sure to include a JWT if you intend to record,
                // make outbound calls or use any other premium features!
                // jwt: "eyJraWQiOiJ2cGFhcy1tYWdpYy1jb29raWUtMzZjZGYwOTljOGNjNGJhMWI0ZDU3OTc3ZGI1ZDE1ZDEvNDZhNzNiLVNBTVBMRV9BUFAiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJqaXRzaSIsImlzcyI6ImNoYXQiLCJpYXQiOjE2OTAyNjE5NTcsImV4cCI6MTY5MDI2OTE1NywibmJmIjoxNjkwMjYxOTUyLCJzdWIiOiJ2cGFhcy1tYWdpYy1jb29raWUtMzZjZGYwOTljOGNjNGJhMWI0ZDU3OTc3ZGI1ZDE1ZDEiLCJjb250ZXh0Ijp7ImZlYXR1cmVzIjp7ImxpdmVzdHJlYW1pbmciOmZhbHNlLCJvdXRib3VuZC1jYWxsIjpmYWxzZSwic2lwLW91dGJvdW5kLWNhbGwiOmZhbHNlLCJ0cmFuc2NyaXB0aW9uIjpmYWxzZSwicmVjb3JkaW5nIjpmYWxzZX0sInVzZXIiOnsiaGlkZGVuLWZyb20tcmVjb3JkZXIiOmZhbHNlLCJtb2RlcmF0b3IiOnRydWUsIm5hbWUiOiJUZXN0IFVzZXIiLCJpZCI6Imdvb2dsZS1vYXV0aDJ8MTAxNDAzODU2NTc4OTkwMzczNzcwIiwiYXZhdGFyIjoiIiwiZW1haWwiOiJ0ZXN0LnVzZXJAY29tcGFueS5jb20ifX0sInJvb20iOiIqIn0.XsLkW1NZ5V83bnWKyZBV6lZK9RMxjQDFNAFV5lRsfRQB9jVmPHJOiLS96Fq18zM4UP3B1UXdozagUwXbzse4tNb_O_asR4MQ2F8UAfkl92WighZGaZsvZ1WpCLgU5urIGaGCeJ7orl4LPt4nfiFgF1cEKZZxkCQYsFOqSygxAmbbR6GEofNoE-i0W6TVvXO_rpfpMNFcGkcj-3JzwGF7riBMbH-JpnbcR7JSecJgpUP_dohD5JiINIoVgXUdXhASNbuFzBG2pfQrqyTf3E3nYO0WDE5I_PrizPYpm73gIrFQl7K4ghbyvLrtbFxR6us9ktsvWZuwPlpExuMwRZ2DUQ"
                devices: {
                    audioInput: '<deviceLabel>',
                    audioOutput: '<deviceLabel>',
                    videoInput: '<deviceLabel>'
                },
                configOverwrite: {
                    startWithAudioMuted: true,
                    startWithVideoMuted: true,
                    prejoinPageEnabled: false,
                    remoteVideoMenu: 
                    {
                        disableKick: false,
                    },
                    disableRemoteMute: false,
                    toolbarButtons: [
                    'camera',
                    'chat',
                    'closedcaptions',
                    'desktop',
                    'download',
                    'embedmeeting',
                    'etherpad',
                    'feedback',
                    'filmstrip',
                    'fullscreen',
                    'hangup',
                    'help',
                    'invite',
                    'livestreaming',
                    'microphone',
                    'mute-everyone',
                    'mute-video-everyone',
                    'participants-pane',
                    'profile',
                    'raisehand',
                    'recording',
                    'security',
                    'select-background',
                    'settings',
                    'shareaudio',
                    'sharedvideo',
                    'shortcuts',
                    'stats',
                    'tileview',
                    'toggle-camera',
                    'videoquality',
                    '__end'
                    ],
                    // allow:[camera], 
                },
                videoInputerfaceConfigOverwrite: { DISABLE_DOMINANT_SPEAKER_INDICATOR: true },
                userInfo: {
                    moderator: true,
                    email: '<?php echo $logged_user_details['email']; ?>',
                    displayName: '<?php echo $logged_user_details['first_name'].' '.$logged_user_details['last_name']; ?>'
                },

            });
        }
        var api = new JitsiMeetExternalAPI(domain, options);
        
        // set new password for channel
        api.addEventListener('participantRoleChanged', function(event) {
            if (event.role === "moderator") {
                api.executeCommand('password', '<?php echo $live_class_details['jitsi_meeting_password']; ?>');
            }
        });

        api.executeCommand('subject', '<?php echo $live_class_details['class_topic']; ?>');

        api.on('readyToClose', () => {
            window.close();
        });
    </script>
<?php else: ?>
    <script type="text/javascript">

        window.onload = () => {
            const api = new JitsiMeetExternalAPI("meet.jit.si", {
                roomName: "<?php echo get_settings('system_name');?> - <?php echo $live_class_details['class_topic'];?>",
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#jitsiMeet'),
                devices: {
                    audioInput: '<deviceLabel>',
                    audioOutput: '<deviceLabel>',
                    videoInput: '<deviceLabel>'
                },
                configOverwrite: {
                    startWithAudioMuted: true,
                    startWithVideoMuted: true,
                    prejoinPageEnabled: false,
                    remoteVideoMenu: 
                    {
                        disableKick: true,
                    },
                    desktopSharingFirefoxDisabled: false,
                    desktopSharingChromeDisabled: false,
                    disableRemoteMute: true,
                    disableInviteFunctions: true,
                    toolbarButtons: [
                    'camera',
                    'chat',
                    'desktop',
                    'download',
                    'etherpad',
                    'filmstrip',
                    'fullscreen',
                    'hangup',
                    'livestreaming',
                    'microphone',
                    'participants-pane',
                    'profile',
                    'raisehand',
                    'recording',
                    'select-background',
                    'settings',
                    'shareaudio',
                    'sharedvideo',
                    'shortcuts',
                    'stats',
                    'tileview',
                    'toggle-camera',
                    'videoquality',
                    '__end'
                    ]
                },
                videoInputerfaceConfigOverwrite: { DISABLE_DOMINANT_SPEAKER_INDICATOR: true },
                userInfo: {
                    moderator: false,
                    email: '<?php echo $logged_user_details['email']; ?>',
                    displayName: '<?php echo $logged_user_details['first_name'].' '.$logged_user_details['last_name']; ?>'
                }
         
            
            });
        }
        var api = new JitsiMeetExternalAPI(domain, options);

        api.executeCommand('subject', '<?php echo $live_class_details['class_topic']; ?>');

        api.on('readyToClose', () => {
            window.close();
        });
    </script>
<?php endif; ?>


<script type="text/javascript">
  //Auto enter the password
  api.on('passwordRequired', function () {
    api.executeCommand('password', '<?php echo $live_class_details['jitsi_meeting_password']; ?>');
  });
</script>