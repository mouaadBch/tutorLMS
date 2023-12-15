<?php

$CI    = &get_instance();
$CI->load->model('addons/affiliate_course_model');

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";

$url .= $_SERVER['HTTP_HOST'];


$title = slugify($course_details['title']);


$url = site_url("home/course/" . $title . "/" . $course_id);



$user_data = $CI->affiliate_course_model->get__affiliator_status_table_info_by_user_id($this->session->userdata('user_id'));

if (isset($user_data['unique_identifier'])) :
    $ref = $user_data['unique_identifier'];
else :
    $ref = '';
endif;

$full_url = $url . "/?ref=" . $ref;


?>



<div class="modal fade affiliate_modal" id="myModel" tabindex="-1" aria-labelledby="myModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModelLabel">Refer Course </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p>Copy the referral link </p>
                <div class="d-flex align-items-center icons">
                </div>
                <div class="field d-flex align-items-center justify-content-between copy-field">
                    <i class="fas fa-link text-center"></i>
                    <input type="text" value=" <?= $full_url ?>" id='copyData'>
                    <button>Copy</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(e) {
        let field = document.querySelector('.copy-field');
        let input = document.querySelector('input');
        let copyBtn = document.querySelector('.copy-field button');


        copyBtn.onclick = () => {
            var str = $("#copyData").val();
            input.select();
            navigator.clipboard.writeText(str)
            if (document.execCommand("copy")) {
                field.classList.add('active');
                copyBtn.innerText = 'Copied';
                setTimeout(() => {
                    field.classList.remove('active');
                    copyBtn.innerText = 'Copy';
                }, 3500)
            }
        }
    });
</script>

<style>
    .modal {
        top: 20%;
    }

    .btn-close {
        box-shadow: none;
        border: none;
        outline: none;
    }

    .modal-body .icons {
        margin: 15px 0px 20px 0px;
    }

    .modal-body .icons a {
        text-decoration: none;
        border: 1px solid transparent;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 20px;
        transition: all 0.3s ease-in-out;
    }

    .modal-body .icons a:nth-child(1) {
        color: #1877F2;
        border-color: #B7D4FB;
    }

    .modal-body .icons a:nth-child(1):hover {
        background-color: #1877F2;
        color: #fff;
    }

    .modal-body .icons a:nth-child(2) {
        color: #46C1F6;
        border-color: #b6e7fc;
    }

    .modal-body .icons a:nth-child(2):hover {
        background-color: #46C1F6;
        color: #fff;
    }

    .modal-body .icons a:nth-child(3) {
        color: #e1306c;
        border-color: #f5bccf;
    }

    .modal-body .icons a:nth-child(3):hover {
        background-color: #e1306c;
        color: #fff;
    }

    .modal-body .icons a:nth-child(4) {
        color: #25d366;
        border-color: #bef4d2;
    }

    .modal-body .icons a:nth-child(4):hover {
        background-color: #25d366;
        color: #fff;
    }


    .modal-body .icons a:nth-child(5) {
        color: #0088cc;
        border-color: #b3e6ff;
    }

    .modal-body .icons a:nth-child(5):hover {
        background-color: #0088cc;
        color: #fff;
    }

    .modal-body .icons a:hover {
        border-color: transparent;
    }

    .modal-body .icons a span {
        transition: all 0.09s ease-in-out;
    }

    .modal-body .icons a:hover span {
        transform: scaleX(1.1);
    }

    .modal-body .field {
        height: 45px;
        border: 1px solid #dfdfdf;
        border-radius: 5px;
        margin: 0 20px;
        padding-left: 13px;
    }
    .copy-field i {
	  margin-right: 10px;
      color:#fff;
   }
    .modal-body .field.active {
        border-color: #7d2ae8;
    }

    .field span {
        width: 50px;
        font-size: 1.1rem;
    }

    .field.active span {
        color: #7d2ae8;
    }

    .field input {
        border: none;
        outline: none;
        font-size: 0.89rem;
        width: 100%;
        height: 100%;
        background: #261954;
        color: #fff;
        border-left: 1px solid #fff;
  }

    .field button {
	padding: 5px 16px;
	color: #fff;
	background: #754FFE;
	border: 2px solid transparent;
	border-radius: 0 5px 5px 0;
	font-weight: 500;
	height: 100%;
}

.affiliate_modal{}
.affiliate_modal .modal-body {
   padding-bottom: 34px !important;
}
.affiliate_modal .modal-body p{
    padding-left: 20px;
    color:#fff;
}
.affiliate_modal .btn-close {
	line-height: 1em;
	color: #000;
	background:#eee;
	opacity: 1;
}
    @media (max-width: 330px) {
        .modal-body .icons a {
            margin-right: 15px;
            width: 35px;
            height: 35px;
        }
    }
</style>