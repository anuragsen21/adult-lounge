<script type='text/javascript' src="<?=base_url('assets/js/scaledrone.min.js')?>"></script>
<input type="hidden" id="vcStarted" value="Started">
<input type="hidden" id="url_hash">
<?php if($this->session->userdata('UserType') == 1){ ?>
<input type="hidden" id="videoChatId" value="<?=$this->session->userdata('vcChatId')?>">
<input type="hidden" id="vcPerformerId" value="<?=$this->session->userdata('vcPerformerId')?>">
<input type="hidden" id="vcUserId" value="<?=$this->session->userdata('UserId')?>">
<input type="hidden" id="vcSenderType" value="user">
<input type="hidden" id="vcReceiverType" value="performer">
<?php }else{ ?>
<input type="hidden" id="vcPerformerId" value="<?=$this->session->userdata('UserId')?>">
<input type="hidden" id="vcUserId" value="<?=$this->session->userdata('vcUserId')?>">
<input type="hidden" id="hangupButton">
<input type="hidden" id="vcSenderType" value="performer">
<input type="hidden" id="vcReceiverType" value="user">
<?php } ?>
<main class="content-wrapper">
    <section class="content-sec">
        <div class="vid_chat">
            <div class="performer_sec">
                <div class="performer_cam_vid">
                    <video id="remoteVideo" autoplay poster="<?=base_url('assets/images/giphy.gif')?>"></video>
                </div>
                <div class="show_controll">
                    <?php if($this->session->userdata('UserType') == '1'){ ?>
                    <ul>
                        <li>
                            <a href="javascript:void(0);" id="hangupButton" class="btn">HANG UP</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="btn btn_icon">SHOW TYPE</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="btn">SHOP NOW</a>
                        </li>
                    </ul>
                    <?php } ?>
                </div>
                <div class="user_msg">
                    <div class="msg_hed">
                        <p><?=$this->session->userdata('vcPerformerName')?> CHAT</p>
                    </div>
                    <div class="msg_body">
                        <ul class="vcChatList">
                            <?php
                            $vcLastChatId = 0;
                            if(!empty($chat)){
                                for($i = 0; $i<count($chat); $i++){
                                    $vcLastChatId = $chat[$i]->id;
                            ?>
                            <li <?php if($chat[$i]->sender_id == $this->session->userdata('UserId')){ ?> class="align-right" <?php }else{ ?> class="align-left" <?php } ?>>
                                <span><?=$chat[$i]->msg?></span>
                                <span><?=$chat[$i]->sender_unm?></span>
                            </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                        <input type="hidden" id="vcLastChatId" value="<?=$vcLastChatId?>">
                        <form class="msg_rply">
                            <input type="text" id="vcMsgBody">
                            <input type="button" value="SEND" id="vcSendMsg">
                        </form>
                    </div>
                </div>
            </div>
            <div class="user_sec">
                <div class="user_vid_show">
                    <div class="user_info">
                        <div class="col_2">
                            <p><?=(($usrnm[0]->display_name)) ? $usrnm[0]->display_name : $usrnm[0]->name?></p>
                            <span><img src="<?=base_url('assets/images/heart.png')?>" alt="img">320,000</span>
                            <span><img src="<?=base_url('assets/images/trophy.png')?>" alt="img">£100</span>
                            <?php if(!empty($subs)){ ?> <span>SUBSCRIBED</span> <?php } ?>
                        </div>
                        <div class="col_2">
                            <div class="chat_duration">
                                <span>Active</span>
                                <p id="vcDuration">00:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="user_cam_vid">
                        <video id="localVideo" autoplay muted poster="<?=base_url('assets/images/giphy.gif')?>"></video>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
