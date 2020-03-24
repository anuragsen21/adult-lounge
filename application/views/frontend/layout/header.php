<!DOCTYPE html>
<html lang="en">
<?php
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$active_url = $controller.'/'.$method;
?>

<head>
    <?php $site_setting_data = siteSettingsData();?>
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        var base_url = "<?=base_url()?>";
        var UserId = "<?=$this->session->userdata('UserId')?>";
        var UserType = "<?=$this->session->userdata('UserType')?>";

    </script>
    <script src="<?=base_url('assets/js/DetectRTC.js')?>"></script>
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
                                    <a href="javascript:void(0);" id="msg" class="msg">
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
                                <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 1){ ?>
                                <a href="javascript:void(0);" class="btn buybtn"><img src="<?=base_url('assets/images/icon-buycrd.png')?>" alt="BUY CREDITS" /> BUY CREDITS</a>
                                <?php } ?>
                                <?php if(!$this->session->userdata('UserId') || $this->session->userdata('UserId') == ''){ ?>
                                <a href="<?=base_url('signup')?>" class="btn logbtn"><img src="<?=base_url('assets/images/icon-lock.png')?>" alt="signup" /> SIGNUP</a>
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
                            <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 1){ ?>
                            <li><a href="javascript:void(0)">categories</a>
                                <?php if(!empty($show)){ ?>
                                <div class="submenu">
                                    <h3>Filter By: Catagories</h3>
                                    <ul>
                                        <?php foreach($show as $shw){ ?>
                                        <li><a href="javascript:void(0);" onclick="refine_performer('category', '<?=$shw->id?>')">#<?=$shw->name?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php } ?>
                            </li>
                            <li><a href="javascript:void(0);">Show Types</a></li>
                            <li><a href="javascript:void(0);">awards</a></li>
                            <li><a href="javascript:void(0);">loyalty</a></li>
                            <?php }elseif($this->session->userdata('UserType') && $this->session->userdata('UserType') == 2){ ?>
                            <li><a href="<?=base_url('content')?>">CONTENT</a></li>
                            <li><a href="<?=base_url('manage-users')?>">MANAGE USERS</a></li>
                            <li><a href="<?=base_url('financial')?>">FINANCIAL</a></li>
                            <li><a href="<?=base_url('my-subscriptions')?>">SUBSCRIPTIONS</a></li>
                            <li><a href="<?=base_url('profile')?>">PROFILE</a></li>
                            <li><a href="<?=base_url('my-network')?>">MY NETWORK</a></li>
                            <li><a href="<?=base_url('loyalty')?>">LOYALTY</a></li>
                            <!--<li><a href="<?=base_url('settings')?>">SETTINGS</a></li>-->
                            <li><a href="<?=base_url('help')?>">HELP</a></li>
                            <?php if($this->session->userdata('AccountVerified') == 'No'){ ?>
                            <li><a href="<?=base_url('verification')?>">Verification</a></li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                    </nav>
                    <div class="right-filters">
                        <?php if(($this->session->userdata('UserType') || $this->session->userdata('UserType') != '') && $this->session->userdata('UserType') == '1'){ ?>
                        <div class="switch-view" style="color:#fff;">
                            <span class="list welcome_unm">Welcome <?=$this->session->userdata('UserName')?></span>
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
                        <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 1){ ?>
                        <ul id="srchMsg">
                            <li>
                                <a href="javascript:void(0)" class="btn">
                                    <i class="fa fa-search m-srch" onclick="openSearch('Search Messages')" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="btn">
                                    <i class="fa fa-plus m-new" onclick="openSearch('New Message')" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                        <span class="srchBar">
                            <input type="text" id="msgSearch" class="restrictSpecial" placeholder="">
                            <ul class="suggList hide_content"></ul>
                        </span>
                        <?php } ?>
                    </div>
                    <div class="msg-list"></div>
                </div>
                <!--<div class="msg-foo">
					<p> LEGAL DISCLAIMER: 
						<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam rutrum lorem nisl. Aliquam erat volutpat. Proin vulputate enim ac hendrerit sagittis. </span>
					</p>
				</div>-->
            </div>
        </section>
        <?php if($header == 'one'){ ?>
        <main class="content-wrapper">
            <aside>
                <div class="sidebar">
                    <?php if($this->session->userdata('UserType') && $this->session->userdata('UserType') == 1){ ?>
                    <ul class="sidebar-menu">
                        <?php
                        if(!empty($categories)){
                            if(isset($user) && $user[0]['category'] != ''){
                        ?>
                        <li class="performers"><a href="javascript:void(0);">PERFORMERS</a>
                            <ul>
                                <?php
                                foreach($categories as $cat){
                                    if(count(explode($cat->id, $user[0]['category'])) > 1){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('category', '<?=$cat->id?>')">#<?=$cat->name?></a>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                            }
                        }
                        if(!empty($show)){
                            if(isset($user) && $user[0]['attribute'] != ''){
                        ?>
                        <li class="types"><a href="javascript:void(0);">SHOW TYPES</a>
                            <ul>
                                <?php
                                foreach($show as $shw){
                                    if(count(explode($shw->id, $user[0]['attribute'])) > 1){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('attribute', '<?=$shw->id?>')">#<?=$shw->name?></a>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                            }
                        }
                        if(!empty($age)){
                        ?>
                        <li class="age"><a href="javascript:void(0);">AGE</a>
                            <ul>
                                <?php foreach($age as $ag){ ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('age', '<?=$ag->age?>')"><?=$ag->age?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php
                        }
                        if(!empty($will)){
                            if(isset($user) && $user[0]['willingness'] != ''){
                        ?>
                        <li class="willingers"><a href="javascript:void(0);">WILLINGNESS</a>
                            <ul>
                                <?php
                                foreach($will as $wll){
                                    if(count(explode($wll->id, $user[0]['willingness'])) > 1){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('willingness', '<?=$wll->id?>')">#<?=$wll->name?></a>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                            }
                        }
                        if(!empty($appearence)){
                            if(isset($user) && $user[0]['appearance'] != ''){
                        ?>
                        <li class="appearance"><a href="javascript:void(0);">APPEARANCE</a>
                            <ul>
                                <?php
                                foreach($appearence as $aprnc){
                                    if(count(explode($aprnc->id, $user[0]['appearance'])) > 1){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('appearance', '<?=$aprnc->id?>')">#<?=$aprnc->name?></a>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                    <?php }else{ ?>
                    <ul class="sidebar-menu">
                        <?php
                        if(!empty($categories)){
                        ?>
                        <li class="performers"><a href="javascript:void(0);">PERFORMERS</a>
                            <ul>
                                <?php
                                foreach($categories as $cat){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('category', '<?=$cat->id?>')">#<?=$cat->name?></a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        }
                        if(!empty($show)){
                        ?>
                        <li class="types"><a href="javascript:void(0);">SHOW TYPES</a>
                            <ul>
                                <?php
                                foreach($show as $shw){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('attribute', '<?=$shw->id?>')">#<?=$shw->name?></a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        }
                        if(!empty($age)){
                        ?>
                        <li class="age"><a href="javascript:void(0);">AGE</a>
                            <ul>
                                <?php foreach($age as $ag){ ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('age', '<?=$ag->age?>')"><?=$ag->age?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php
                        }
                        if(!empty($will)){
                        ?>
                        <li class="willingers"><a href="javascript:void(0);">WILLINGNESS</a>
                            <ul>
                                <?php
                                foreach($will as $wll){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('willingness', '<?=$wll->id?>')">#<?=$wll->name?></a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        }
                        if(!empty($appearence)){
                        ?>
                        <li class="appearance"><a href="javascript:void(0);">APPEARANCE</a>
                            <ul>
                                <?php
                                foreach($appearence as $aprnc){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" onclick="refine_performer('appearance', '<?=$aprnc->id?>')">#<?=$aprnc->name?></a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                    <?php } ?>
                </div>
            </aside>
            <?php } ?>
