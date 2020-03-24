<main class="content-wrapper">
    <section class="content-sec">
        <div class="subs_thumb">
            <div class="subs_thumb_hed">
                <p>My Subscribers</p>
            </div>
            <div class="subs_thumb_inner">
                <?php
                if(!empty($mySubs)){
                ?>
                <ul>
                    <?php for($i=0; $i<count($mySubs); $i++){ ?>
                    <li>
                        <?php if($mySubs[$i]['image'] != ''){ ?>
                        <img src="<?=base_url('assets/profile_image/'.$mySubs[$i]['image'])?>" alt="<?=($mySubs[$i]['display_name'] != '') ? $mySubs[$i]['display_name'] : $mySubs[$i]['name']?>">
                        <?php }else{ ?>
                        <img src="<?=base_url('assets/images/noimage.png')?>" alt="<?=($mySubs[$i]['display_name'] != '') ? $mySubs[$i]['display_name'] : $mySubs[$i]['name']?>">
                        <?php } ?>
                        <figure>
                            <span class="subs_name"><?=($mySubs[$i]['display_name'] != '') ? $mySubs[$i]['display_name'] : $mySubs[$i]['name']?></span>
                            <span class="subs_uname"><?=$mySubs[$i]['usernm']?></span>
                        </figure>
                    </li>
                    <?php } ?>
                </ul>
                <?php }else{ ?>
                <h1 class="no-subs">Sorry !!! No Subscribers Found !!!</h1>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
