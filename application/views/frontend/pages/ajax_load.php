<?php
if(isset($subs)){
    for($k=0;$k<count($subs);$k++){
        if($subs[$k]['display_name'] !='' ){
            $nm = $subs[$k]['display_name'];
        }else{
            $nm = $subs[$k]['name'];
        }
?>
<li>
    <div>
        <span class="pfrm-pic"><img src="<?=base_url('assets/profile_image/'.$subs[$k]['image'])?>" alt="<?=$nm?>" height="50" width="65" /></span>
        <span class="pfrm-name"><?=$nm?></span>
    </div>
    <div>
        <a href="<?=base_url('performer/'.$subs[$k]['id'].'/'.strtolower(str_replace(' ', '_', $nm)).'/')?>" class="btn radius0 text-center">View</a>
    </div>
</li>
<?php
    }
}
if(isset($performer)){
    for($k=0;$k<count($performer);$k++){
        if($performer[$k]['display_name'] !='' ){
            $nm = $performer[$k]['display_name'];
        }else{
            $nm = $performer[$k]['name'];
        }
?>
<div class="col-grid">
    <figure class="active">
        <a href="<?=base_url('performer/'.$performer[$k]['id'].'/'.strtolower(str_replace(' ', '_', $nm)).'/')?>">
            <img src="<?=base_url('assets/profile_image/'.$performer[$k]['image'])?>" alt="<?=$nm?>" width="350" height="250" />
        </a>
        <figcaption>
            <h4>
                <a href="<?=base_url('performer/'.$performer[$k]['id'].'/'.strtolower(str_replace(' ', '_', $nm)).'/')?>">
                    <?=$nm?>
                </a>
            </h4>
            <ul>
                <li>PRIVATE: <span>£6.99</span> p/m</li>
                <li>GROUP: <span>£3.99</span> p/M</li>
            </ul>
        </figcaption>
    </figure>
</div>
<?php
    }
}
if(isset($chatList)){
    for($k=0;$k<count($chatList);$k++){
?>
<div class="msg-list-row userOne" id="msglst<?=$chatList[$k]->id?>">
    <div class="user-photo" onclick="openMsg('<?=$chatList[$k]->id?>')">
        <div class="photo-circle">
            <?php if($chatList[$k]->image != ''){ ?>
            <img src="<?=base_url('assets/profile_image/'.$chatList[$k]->image)?>" alt="<?php if($chatList[$k]->display_name != ''){ print $chatList[$k]->display_name;}else{ print $chatList[$k]->name; } ?>">
            <?php }else{ ?>
            <img src="<?=base_url('assets/images/no-image.png')?>" alt="<?php if($chatList[$k]->display_name != ''){ print $chatList[$k]->display_name;}else{ print $chatList[$k]->name; } ?>">
            <?php } ?>
        </div>
    </div>
    <div class="msg-list-lft" onclick="openMsg('<?=$chatList[$k]->id?>')">
        <span class="user-nm"><?php if($chatList[$k]->display_name != ''){ print $chatList[$k]->display_name;}else{ print $chatList[$k]->name; } ?> <?=$chatList[$k]->usernm?></span>
        <span class="recnt-msg"><?=$chatList[$k]->chat?></span>
    </div>
    <div class="msg-list-rht">
        <?php if($this->session->userdata('UserType') == '1'){ ?>
        <span class="msg-del">
            <img src="<?=base_url('assets/images/cross.png')?>" alt="Delete Chat" onclick="deleteChat('<?=$chatList[$k]->id?>')">
        </span>
        <?php } ?>
        <p class="msg-time" onclick="openMsg('<?=$chatList[$k]->id?>')">
            <?=date_format(date_create($chatList[$k]->time), 'd-m-Y')?>
            <span><?=date_format(date_create($chatList[$k]->time), 'h:i a')?></span>
        </p>
    </div>
</div>
<?php
    }
}
if(isset($fullChat)){
?>
<div id="userOneMsg<?=$usrImg[0]['id']?>">
    <div class="allMsg">
        <a href="javascript:void(0)" class="btn" id="backMsg<?=$usrImg[0]['id']?>" onclick="backMsg('<?=$usrImg[0]['id']?>')"><img src="<?=base_url('assets/images/left-arrow.png')?>">All Messages</a>
    </div>
    <div class="chat-sec" id="chatSec<?=$usrImg[0]['id']?>">
        <ul class="chatLstUl">
            <?php
            $oth_user_id = '';
            for($i=0; $i<count($fullChat); $i++){
                $chat_id = $fullChat[$i]->id;
                if($oth_user_id == ''){
                    if($fullChat[$i]->sender_id != $this->session->userdata('UserId')){
                        $oth_user_id = $fullChat[$i]->sender_id;
                    }else{
                        $oth_user_id = $fullChat[$i]->receiver_id;
                    }
                }
            ?>
            <li class="<?php if($fullChat[$i]->sender_id == $this->session->userdata('UserId')){ print 'align-right'; }else{ print 'align-left'; } ?>">
                <?php if($fullChat[$i]->sender_id == $this->session->userdata('UserId')){ ?>
                <?php if($sessImg[0]['image'] != ''){ ?>
                <img src="<?=base_url('assets/profile_image/'.$sessImg[0]['image'])?>" alt="<?php if($sessImg[0]['display_name'] != ''){ print $sessImg[0]['display_name'];}else{ print $sessImg[0]['name']; } ?>">
                <?php }else{ ?>
                <img src="<?=base_url('assets/images/no-image.png')?>" alt="<?php if($sessImg[0]['display_name'] != ''){ print $sessImg[0]['display_name'];}else{ print $sessImg[0]['name']; } ?>">
                <?php } ?>
                <?php }else{ ?>
                <?php if($usrImg[0]['image'] != ''){ ?>
                <img src="<?=base_url('assets/profile_image/'.$usrImg[0]['image'])?>" alt="<?php if($usrImg[0]['display_name'] != ''){ print $usrImg[0]['display_name'];}else{ print $usrImg[0]['name']; } ?>">
                <?php }else{ ?>
                <img src="<?=base_url('assets/images/no-image.png')?>" alt="<?php if($usrImg[0]['display_name'] != ''){ print $usrImg[0]['display_name'];}else{ print $usrImg[0]['name']; } ?>">
                <?php } ?>
                <?php } ?>
                <span><?=$fullChat[$i]->msg?></span>
            </li>
            <?php } ?>
            <input type="hidden" id="pop_last_chat" value="<?=$chat_id?>">
            <input type="hidden" id="pop_receiver_id" value="<?=$oth_user_id?>">
            <input type="hidden" id="pop_receiver_type" value="<?php if($this->session->userdata('UserType') == 1){ print 'performer';} else{ print 'user';} ?>">
            <input type="hidden" id="pop_sender_id" value="<?=$this->session->userdata('UserId')?>">
            <input type="hidden" id="pop_sender_type" value="<?php if($this->session->userdata('UserType') == 1){ print 'user';} else{ print 'performer';} ?>">
        </ul>
    </div>
    <div class="rply-sec">
        <form class="chatrply" id="chatrply_form" enctype="multipart/form-data">
            <ul>
                <li class="rply-optn-btn">
                    <button type="button"><img src="<?=base_url('assets/images/imgUp.png')?>" alt="User"></button>
                </li>
                <!--<li class="rply-optn-btn">
    <button type="button"><img src="<?=base_url('assets/images/voiceChat.png')?>" alt="User"></button>
</li>
<li class="rply-optn-btn">
    <button type="button"><img src="<?=base_url('assets/images/payMoney.png')?>" alt="User"></button>
</li>-->
            </ul>
            <input type="text" class="requiredCheck" id="pop_chat_msg">
            <input type="button" class="btn" id="pop_snd_msg" data-check="Chat Message" value="Send Message" onclick="send_pop_chat()">
        </form>
    </div>
</div>
<?php
}
if(isset($sndrImg)){
?>
<li class="align-right">
    <?php if($sndrImg[0]['image'] != ''){ ?>
    <img src="<?=base_url('assets/profile_image/'.$sndrImg[0]['image'])?>" alt="<?php if($sndrImg[0]['display_name'] != ''){ print $sndrImg[0]['display_name'];}else{ print $sndrImg[0]['name']; } ?>">
    <?php }else{ ?>
    <img src="<?=base_url('assets/images/no-image.png')?>" alt="<?php if($sndrImg[0]['display_name'] != ''){ print $sndrImg[0]['display_name'];}else{ print $sndrImg[0]['name']; } ?>">
    <?php } ?>
    <span><?=$msg?></span>
</li>
<?php
}
if(isset($newChat)){
    if(!empty($newChat)){
        for($i=0;$i<count($newChat);$i++){
?>
<li style="<?php if($newChat[$i]->sender_id == $this->session->userdata('UserId')){ print 'text-align: right'; } ?>"><?=$newChat[$i]->msg?></li>
<?php
        }
    }
}
if(isset($newChatTwo)){
    for($i=0; $i<count($newChatTwo); $i++){
?>
<li class="<?php if($newChatTwo[$i]->sender_id == $this->session->userdata('UserId')){ print 'align-right'; }else{ print 'align-left'; } ?>">
    <?php if($newChatTwo[$i]->sender_id == $this->session->userdata('UserId')){ ?>
    <?php if($sessImg[0]['image'] != ''){ ?>
    <img src="<?=base_url('assets/profile_image/'.$sessImg[0]['image'])?>" alt="<?php if($sessImg[0]['display_name'] != ''){ print $sessImg[0]['display_name'];}else{ print $sessImg[0]['name']; } ?>">
    <?php }else{ ?>
    <img src="<?=base_url('assets/images/no-image.png')?>" alt="<?php if($sessImg[0]['display_name'] != ''){ print $sessImg[0]['display_name'];}else{ print $sessImg[0]['name']; } ?>">
    <?php } ?>
    <?php }else{ ?>
    <?php if($usrImg[0]['image'] != ''){ ?>
    <img src="<?=base_url('assets/profile_image/'.$usrImg[0]['image'])?>" alt="<?php if($usrImg[0]['display_name'] != ''){ print $usrImg[0]['display_name'];}else{ print $usrImg[0]['name']; } ?>">
    <?php }else{ ?>
    <img src="<?=base_url('assets/images/no-image.png')?>" alt="<?php if($usrImg[0]['display_name'] != ''){ print $usrImg[0]['display_name'];}else{ print $usrImg[0]['name']; } ?>">
    <?php } ?>
    <?php } ?>
    <span><?=$newChatTwo[$i]->msg?></span>
</li>
<?php
    }
}
if(isset($vcNewChat)){
    for($i=0; $i<count($vcNewChat); $i++){
?>
<li <?php if($vcNewChat[$i]->sender_id == $this->session->userdata('UserId')){ ?> class="align-right" <?php }else{ ?> class="align-left" <?php } ?>>
    <span><?=$vcNewChat[$i]->msg?></span>
    <span><?=$vcNewChat[$i]->sender_unm?></span>
</li>
<?php
    }
}
if(isset($userSugg)){
    for($i=0; $i<count($userSugg); $i++){
?>
<li onclick="openMsg('<?=$userSugg[$i]->id?>')">
    <?php if($userSugg[$i]->image != ''){ ?>
    <span class="suggImg"><img src="<?=base_url('assets/profile_image/'.$userSugg[$i]->image)?>" alt="<?=($userSugg[$i]->display_name != '') ? $userSugg[$i]->display_name : $userSugg[$i]->name?>"></span>
    <?php }else{ ?>
    <span class="suggImg"><img src="<?=base_url('assets/images/demo-user.png')?>" alt="<?=($userSugg[$i]->display_name != '') ? $userSugg[$i]->display_name : $userSugg[$i]->name?>"></span>
    <?php } ?>
    <span class="suggName"><?=($userSugg[$i]->display_name != '') ? $userSugg[$i]->display_name : $userSugg[$i]->name?></span>
    <span class="suggUname"><?=$userSugg[$i]->usernm?></span>
</li>
<?php
    }
}
?>
