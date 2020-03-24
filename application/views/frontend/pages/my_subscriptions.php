<main class="content-wrapper">
    <section class="content-sec">
        <div class="title-sec">
            <h2>MY SUBSCRIPTIONS</h2>
            <!--<a href="javascript:void(0)" class="btn text-center">Subscription Settings</a>-->
        </div>
        <div class="list-widget">
            <div class="col gridview">
                <?php
                if(!empty($subs)){
                    for($k=0; $k<count($subs);$k++){
                        if($subs[$k]['display_name'] !='' ){
                            $nm = $subs[$k]['display_name'];
                        }else{
                            $nm = $subs[$k]['name'];
                        }
                ?>
                <div class="col-grid">
                    <figure class="active">
                        <!--<span class="strapbox">In Group</span>-->
                        <a href="<?=base_url('performer/'.$subs[$k]['id'].'/'.strtolower(str_replace(' ', '_', $nm)).'/')?>">
                            <img src="<?=base_url('assets/profile_image/'.$subs[$k]['image'])?>" alt="<?=$nm?>" width="350" height="250" />
                        </a>
                        <figcaption>
                            <h4>
                                <!--<span class="active-circle"></span>-->
                                <a href="<?=base_url('performer/'.$subs[$k]['id'].'/'.strtolower(str_replace(' ', '_', $nm)).'/')?>">
                                    <?=$nm?>
                                </a>
                            </h4>
                        </figcaption>
                    </figure>
                    <a href="<?=base_url('performer/'.$subs[$k]['id'].'/'.strtolower(str_replace(' ', '_', $nm)).'/')?>" class="btn radius0 text-center">VIEW PROFILE</a>
                    <!--<a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>-->
                </div>
                <?php 
                    }
                }else{
                ?>
                <h3 class="no-subs">Sorry!!! No Subscription Found !!!</h3>
                <?php } ?>
                <!--<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>
<div class="col-grid">
    <figure class="active">
        <span class="strapbox">In Group</span>
        <img src="images/img-001_d.jpg" alt="img" />
        <figcaption>
            <h4><span class="active-circle"></span><a href="javascript:void(0)">AlisMorris</a></h4>
        </figcaption>
    </figure>
    <a href="javascript:void(0)" class="btn radius0 text-center">VIEW PROFILE</a>
    <a href="javascript:void(0)" class="brdrbtn text-center">MANAGE SUBSCIPTION</a>
</div>-->
            </div>
        </div>
    </section>
</main>
</section>
