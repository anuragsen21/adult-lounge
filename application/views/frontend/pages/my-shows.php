<main class="content-wrapper">
    <section class="content-sec">
        <div class="subs_thumb">
            <div class="subs_thumb_hed">
                <p>My Shows</p>
            </div>
            <div class="subs_thumb_inner">
                <?php
                if(!empty($myShows)){
                ?>
                <ul>
                    <?php for($i=0; $i<count($myShows); $i++){ ?>
                    <li>
                        <?php if($myShows[$i]['image'] != ''){ ?>
                        <img src="<?=base_url('assets/profile_image/'.$myShows[$i]['image'])?>" alt="<?=($myShows[$i]['display_name'] != '') ? $myShows[$i]['display_name'] : $myShows[$i]['name']?>">
                        <?php }else{ ?>
                        <img src="<?=base_url('assets/images/noimage.png')?>" alt="<?=($myShows[$i]['display_name'] != '') ? $myShows[$i]['display_name'] : $myShows[$i]['name']?>">
                        <?php } ?>
                        <figure>
                            <span class="subs_name"><?=($myShows[$i]['display_name'] != '') ? $myShows[$i]['display_name'] : $myShows[$i]['name']?></span>
                            <span class="subs_uname"><?=$myShows[$i]['usernm']?></span>
                            <span class="subs_uname"><?=date_format(date_create($myShows[$i]['created_at']), 'd-m-Y')?></span>
                            <span class="subs_uname"><?=$myShows[$i]['elapsed_time']?></span>
                        </figure>
                    </li>
                    <?php if($this->session->userdata('UserId') == 1){ ?>
                    <a href="<?=base_url('performer/'.$myShows[$k]['id'].'/'.strtolower(str_replace(' ', '_', ($myShows[$i]['display_name'] != '') ? $myShows[$i]['display_name'] : $myShows[$i]['name'])).'/')?>" class="btn radius0 text-center">VIEW PROFILE</a>
                    <?php } ?>
                    <?php } ?>
                </ul>
                <?php }else{ ?>
                <h1 class="no-subs">Sorry !!! No Shows Found !!!</h1>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
