<!DOCTYPE html>
<html lang="en">
<?php
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$active_url = $controller.'/'.$method;
?>

<head>
    <?php $site_setting_data = siteSettingsData(); //print_r($site_setting_data); die;?>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php 
        if(!isset($title) && $site_setting_data['site_title'] !=''){ 
            echo $site_setting_data['site_title']; 
        } elseif(isset($title)){
            echo $title;
        } else {
            echo 'Adult Lounge';
        }
        ?></title>
    <meta property="og:title" content="<?php 
        if(!isset($title) && $site_setting_data['site_title'] !=''){ 
            echo $site_setting_data['site_title']; 
        } elseif(isset($title)){
            echo $title;
        } else {
            echo 'Adult Lounge';
        }
        ?>">
    <meta name="author" content="Anurag Sen">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="<?=base_url('assets/css/jquery.mCustomScrollbar.css')?>">
    <link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?=base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?=base_url('assets/css/sweetalert2.min.css')?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css')?>">
    <script>
        var base_url = "<?=base_url()?>";

    </script>
    <!--<link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
<script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
<script src="<?=base_url('assets/js/bootstrap.min.js')?>"></script>-->
    <script src="<?=base_url('assets/js/DetectRTC.js')?>"></script>
    <!--<script src="<?=base_url('assets/js/common-script.js')?>"></script>-->
</head>

<body id="body-content" class="hide">
    <section class="pagewrapper">
        <section class="header-wrap">
            <div class="header-layout">
                <header class="main-header">
                    <div class="hdr-lft">
                        <a href="<?=base_url()?>" class="sitelogo"><img src="<?=base_url('assets/images/logo.png')?>" alt="Logo" /></a>
                    </div>
                    <div class="hdr-rgt">
                        <div class="hdr-rwidgt">
                            <ul class="inline-styled text-right">
                                <!--<li><a href="javascript:void(0)" title="country"><img src="images/icon-flag.png" alt="uk"></a></li>-->
                                <li><a href="<?=base_url()?>" title="Home"><img src="<?=base_url('assets/images/icon-home.png')?>" alt="Home"></a></li>
                                <?php if($this->session->userdata('UserId') || $this->session->userdata('UserId') != ''){ ?>
                                <li>
                                    <a href="<?=base_url('profile')?>" title="User">
                                        <img src="<?=base_url('assets/images/icon-user.png')?>" alt="User">
                                    </a>
                                </li>
                                <?php } ?>
                                <!--<li><a href="javascript:void(0)" title="Briefcase"><img src="images/icon-briefcase.png" alt="briefcase"></a></li>-->
                                <?php if($this->session->userdata('UserType') && ($this->session->userdata('UserType') == 1 || $this->session->userdata('UserType') == 2)){ ?>
                                <li>
                                    <a href="javascript:void(0);" id="msg">
                                        <i class="fa fa-comments" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 1){ ?>
                                <li>
                                    <a href="<?=base_url('personal-details')?>" title="Setting">
                                        <img src="<?=base_url('assets/images/icon-setting.png')?>" alt="setting">
                                    </a>
                                </li>

                                <?php } ?>

                            </ul>
                        </div>
                        <div class="hdr-rwidgt">
                            <div class="btn-group">
                                <a href="javascript:void(0)" class="btn buybtn"><img src="<?=base_url('assets/images/icon-buycrd.png')?>" alt="BUY CREDITS" /> BUY CREDITS</a>
                                <?php if(!$this->session->userdata('UserId') || $this->session->userdata('UserId') == ''){ ?>
                                <a href="<?=base_url('login')?>" class="btn logbtn"><img src="<?=base_url('assets/images/icon-lock.png')?>" alt="login" /> LOGIN</a>
                                <?php }else{ ?>
                                <a href="<?=base_url('logout')?>" class="btn logbtn"><img src="<?=base_url('assets/images/icon-lock.png')?>" alt="signup" /> LOGOUT</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </header>
                <section class="header-bottom">
                    <nav>
                        <ul>
                            <li><a href="javascript:void(0)">categories</a>
                                <?php if(!empty($show)){ ?>
                                <div class="submenu">
                                    <h3>Filter By: Catagories</h3>
                                    <ul>
                                        <?php foreach($show as $shw){ ?>
                                        <li><a href="javascript:void(0)" onclick="filter('category', '<?=$shw->id?>')">#<?=$shw->name?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php } ?>
                            </li>
                            <li><a href="javascript:void(0)">Show Types</a></li>
                            <li><a href="javascript:void(0)">awards</a></li>
                            <li><a href="javascript:void(0)">loyalty</a></li>
                            <?php if(!$this->session->userdata('UserType') || $this->session->userdata('UserType') == ''){ ?>
                            <li><a href="<?=base_url('signup')?>">Sign up</a></li>
                            <?php } ?>
                            <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 1){ ?>
                            <!--<li><a href="<?=base_url('video-chat')?>">Video Chat</a></li>-->
                            <?php } ?>
                            <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 2 && $this->session->userdata('AccountVerified') == 'No'){ ?>
                            <li><a href="<?=base_url('verification')?>">Verification</a></li>
                            <?php } ?>
                        </ul>
                    </nav>
                    <div class="right-filters">
                        <?php if($this->session->userdata('UserId') || $this->session->userdata('UserId') != ''){ ?>
                        <div class="switch-view" style="color:#fff;">
                            <span class="list">Welcome <?=$this->session->userdata('UserName')?></span>
                        </div>
                        <?php } ?>
                        <!--<div class="search"><span><img src="<?=base_url('assets/images/icon-search.png')?>" alt="search" /></span></div>
<div class="drop-list">
    <span>recommended</span>
    <ul>
        <li><a href="javascript:void(0)">recommended</a></li>
        <li><a href="javascript:void(0)">recommended 1</a></li>
        <li><a href="javascript:void(0)">recommended 2</a></li>
    </ul>
</div>
<div class="switch-view">
    <span class="list"><img src="<?=base_url('assets/images/icon-list.png')?>" alt="list" /></span>
    <span class="grid"><img src="<?=base_url('assets/images/icon-grid.png')?>" alt="grid" /></span>
</div>-->
                    </div>
                </section>
            </div>
        </section>
        <section class="msg-bg">
            <div class="msg-container">
                <div class="msg-hed">
                    <h3>Messages</h3>
                    <span id="msg-close">X</span>
                </div>
                <div class="msg-body">
                    <div class="msg-body-nav">
                        <span>Recent Messages</span>
                        <ul>
                            <li>
                                <a href="javascript:void(0)" class="btn">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="btn">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="msg-list">
                        <div class="msg-list-row userOne" id="msglst2" onclick="openMsg('2')">
                            <div class="user-photo">
                                <div class="photo-circle">
                                    <img src="<?=base_url('assets/images/130x130.jpg')?>" alt="User">
                                </div>
                            </div>
                            <div class="msg-list-lft">
                                <span class="user-nm">Megan Kroft @megankroft</span>
                                <span class="recnt-msg">CALL ME THEN XXX</span>
                            </div>
                            <div class="msg-list-rht">
                                <span class="msg-del">
                                    <img src="<?=base_url('assets/images/cross.png')?>" alt="Delete">
                                </span>
                                <p class="msg-time">
                                    23/04/19.
                                    <span>9.50am</span>
                                </p>
                            </div>
                        </div>
                        <div style="display:none" id="userOneMsg2">
                            <div class="allMsg">
                                <a href="javascript:void(0)" class="btn" id="backMsg2" onclick="backMsg('2')">All Messages</a>
                            </div>
                            <div class="chat-sec" id="chatSec2">
                                <ul>
                                    <li class="align-right">
                                        <img src="<?=base_url('assets/images/130x130.jpg')?>" alt="User">
                                        <span>Morning, how r u xxx</span>
                                    </li>
                                    <li class="align-left">
                                        <img src="<?=base_url('assets/images/130x130.jpg')?>" alt="User">
                                        <span>Hey, how r u xxx</span>
                                    </li>
                                    <li class="align-right">
                                        <img src="<?=base_url('assets/images/130x130.jpg')?>" alt="User">
                                        <span>Morning, how r u xxx</span>
                                    </li>
                                    <li class="align-left">
                                        <img src="<?=base_url('assets/images/130x130.jpg')?>" alt="User">
                                        <span>Hey, how r u xxx</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="rply-sec">

                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="msg-foo">
					<p> LEGAL DISCLAIMER: 
						<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam rutrum lorem nisl. Aliquam erat volutpat. Proin vulputate enim ac hendrerit sagittis. </span>
					</p>
				</div>-->
            </div>
        </section>
