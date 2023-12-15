<!-- Bootstrap CSS -->
<?php if($language_dir == 'rtl'): ?>
    <link href="<?php echo site_url('assets/playing-page/') ?>css/bootstrap.rtl.min.css" rel="stylesheet" />
<?php else: ?>
    <link href="<?php echo site_url('assets/playing-page/') ?>css/bootstrap.min.css" rel="stylesheet" />
<?php endif; ?>

<!-- Animation CSS -->
<link rel="stylesheet" href="<?php echo site_url('assets/playing-page/') ?>css/animate.min.css" />
<!-- Main CSS -->
<link href="<?php echo site_url('assets/playing-page/') ?>css/style.css" rel="stylesheet" />

<?php if(addon_status('certificate')): ?>
	<!-- Progress Bar Css -->
	<link rel="stylesheet" href="<?php echo site_url('assets/playing-page/') ?>css/jQuery-plugin-progressbar.css" />
	<!-- Custome Css -->
	<link href="<?php echo site_url('assets/playing-page/') ?>css/custom.css" rel="stylesheet" />
<?php endif; ?>

<!-- font awesome 5 -->
<link rel="stylesheet" href="<?php echo base_url().'assets/frontend/default-new/css/fontawesome-all.min.css'; ?>">
<link rel="stylesheet" href="<?php echo base_url().'assets/global/toastr/toastr.css' ?>">

<?php if($language_dir == 'rtl'): ?>
    <link href="<?php echo site_url('assets/playing-page/') ?>css/rtl.css" rel="stylesheet" />
<?php endif; ?>

<script src="<?php echo base_url('assets/backend/js/jquery-3.3.1.min.js'); ?>"></script>

<!-- Lesson page specific styles are here -->
<style type="text/css">
    
/* /////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ */
     .title2
    {
    text-align: center; font-size: 1.925em; color: #1eaace;
    }
    
    .bigone
    {
    color: #ff0000;
    font-size: 1.4em
    }
    
    .one
    {
    color: #339966;
    font-size: 1.4em;
    	padding:1em;
    }
    .a
    {
    	color: #2991d6; font-size: 1.4em;
    		padding:2em;
    }
    /* Racine  */
    .radical {
        position: relative;
        font-size: 1.6em;
        vertical-align: middle;
    }
    .n-root {
        position: absolute;
        top: -0.333em;
        left: 0.333em;
        font-size: 45%;
    }
    .radicand {
        padding: 0.25em 0.25em;
        border-top: thin black solid;
    }
    /*   Somme et intégrales */
    .intsuma {
        position: relative;
        display: inline-block;
        vertical-align: middle;
        text-align: center;
    }
    .intsuma>span {
        display: block;
        font-size: 70%;
    }
    .intsuma .lim-up {
        margin-bottom: -1.0ex;
    }
    .intsuma .lim {
        margin-top: -0.5ex;
    }
    .intsuma .sum {
        font-size: 1.5em;
        font-weight: lighter;
    }
    .intsuma .sum-frac {
        font-size: 1.5em;
        font-weight: 100;
    }
    /*  Limites et nombre de masse  */
    .limes {
        position: relative;
        display: inline-block;
        margin: 0 0.2em;
        vertical-align: middle;
        text-align: center;
    }
    .limes>span {
        display: block;
        margin: -0.5ex auto;
    }
    .limes span.numup,
    .limes span.overdn {
        font-size: 70%;
    }
    /*  Vecteurs       */
    .sy {
        position: relative;
        text-align: center;
    }
    .oncapital,
    .onsmall {
        position: absolute;
        top: -1em;
        left: 0px;
        width: 100%;
        font-size: 70%;
        text-align: center;
    }
    .onsmall {
        top: -0.7em;
    }
    /* Écrire les nombres d'oxydation des éléments   */
    .sy,
    .sy-r,
    .sy-g,
    .sy-b {
        position: relative;
        text-align: center;
    }
    .sy-r {
        color: #f00;
    }
    .sy-g {
        color: #4f8c4f;
    }
    .sy-b {
        color: #00f;
    }
    .oxbr,
    .oncapital,
    .onsmall {
        position: absolute;
        top: -1em;
        left: 0px;
        width: 100%;
        font-size: 70%;
        text-align: center;
    }
    .onsmall {
        top: -0.7em;
    }
    .fraction {
        display: inline-block;
        vertical-align: middle;
        margin: 0 0.2em 0.4ex;
        text-align: center;
    }
    .fraction>span {
        display: block;
        padding-top: 0.15em;
    }
    .fraction span.fdn {
        border-top: thin solid black;
    }
    .fraction span.bar {
        display: none;
    }
    .dblarrow {
        font-size: 125%;
        top: -0.4ex;
        margin: 0 2px;
    }
    .dblarrow:after {
        content: "\2190";
        position: absolute;
        left: 0;
        top: 0.5ex;
    }
    /*  equivalence chimie  */
    .dblarrow {
        font-size: 125%;
        top: -0.4ex;
        margin: 0 2px;
    }
    .dblarrow:after {
        content: "\2190";
        position: absolute;
        left: 0;
        top: 0.5ex;
    }
    /* ANNASS CSS */
    .an.relative,
    .relative {
        position: relative;
    }
    .an-equal-45 {
        position: absolute;
        display: inline-block;
        transform: rotate(45deg);
        top: 50%;
        left: 90%;
    }
    .an-0 {
        position: absolute;
        transform: rotate(-45deg);
        left: 93%;
        top: 0%;
    }
    .an-0-2 {
        position: absolute;
        transform: rotate(-45deg);
        left: 93%;
        top: -61%;
        width: 50px;
    }
    .an-0-3 {
        position: absolute;
        transform: rotate(-45deg);
        left: 93%;
        top: -61%;
        width: 60px;
    }
    /* C=0 Vertical */
    .an-relative-vertical {
        position: relative;
    }
    .an-equal-vertical {
        position: absolute;
        display: inline-block;
        transform: rotate(90deg);
        left: 1px;
        top: 10px;
    }
    .an-equal-vertical>span {
        position: absolute;
    }
    .oxbr {
        left: 8px;
        top: -5px;
    }
    .an-equal-45-reverse {
        position: absolute;
        display: inline-block;
        transform: rotate(-45deg);
        top: -70%;
        left: 90%;
    }
    .an-0-reverse {
        position: absolute;
        transform: rotate(45deg);
        left: 110%;
        top: 5%;
    }
    .fdn-quote {
        display: inline-block;
        transform: translateY(-50%);
    }
    .an-table {
        width: 20rem;
    }
    .an-table,
    tr,
    td,
    th {
        border: 1px solid black;
        border-collapse: collapse
    }
    #result-final {
        width: 20rem;
        height: auto;
        min-height: 7rem;
        border: 2px solid #ccc;
        display: flex;
        padding-bottom: 2.5rem;
        align-items: center;
    }
    .an .grid {
        display: grid;
        grid-template-columns: 3fr auto;
        justify-items: end;
    }
    .an .let-grid {
        grid-template-columns: 1fr 3fr;
    }
    .an-0-reverse {
        position: absolute;
        transform: rotate(45deg);
        left: 110%;
        top: 5%;
    }
    .reverse-45-left {
        display: inline-block;
        transform: rotate(135deg) translate(1rem, 0.7rem);
    }
    .hyphen-relative {
        position: relative;
    }
    .hyphen-relative__absolute {
        position: absolute;
        transform: rotate(-135deg) translate(1.5rem, 0.4rem);
        width: 3rem;
    }
    /* .down {
        position: relative;
        display: inline-block;
        transform: rotate(-45deg) translate(-1.5rem, -0.8rem);
    } */
    .last-part {
        position: absolute;
        display: inline-block;
        transform: rotate(45deg) translate(-3rem, 2rem);
        width: 80px;
    }
    .margin-right-3rem {
        margin-right: 1rem;
    }
    .margin-right-6rem {
        margin-right: 3rem;
    }
    .an-0-2-down {
        width: 55px;
    }
    .radical {
        position: relative;
        font-size: 1.6em;
        vertical-align: middle;
    }
    .n-root {
        position: absolute;
        top: -0.333em;
        left: 0.333em;
        font-size: 45%;
    }
    .radicand {
        padding: 0.25em 0.25em;
        border-top: thin black solid;
    }
</style>