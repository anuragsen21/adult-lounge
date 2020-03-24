<section class="modal"> <span class="overlay"></span>
    <div class="modal-body mdl-style1">
        <div class="mdl-style1-widget">
            <div class="top-header">
                <h2>PERSONAL DETAILS</h2>
                <p>You currently have
                    <?php if(empty($wallet)){ ?> <span>0.00 Credits</span>
                    <?php }else{ ?> <span><?=number_format((float)$wallet[0]->credit, 2, '.', '')?> Credits</span>
                    <?php } ?>
                </p>
            </div>
            <div class="body-cont">
                <div class="tab_wrapper second_tab">
                    <ul class="tab_list">
                        <li class="active">PERSONAL DETAILS</li>
                        <li>ACCOUNT SETTINGS</li>
                        <li>SUBSCRIPTIONS</li>
                        <li>HISTORY</li>
                        <li>PRIVACY</li>
                        <li>INVOICES</li>
                    </ul>
                    <div class="content_wrapper">
                        <!--PERSONAL DETAILS-->
                        <div class="tab_content active">
                            <form id="personaldetails-form" method="post" autocomplete="off">
                                <div class="sub-title">
                                    <h3>PERSONAL DETAILS</h3>
                                    <!--<a href="javascript:void(0)" class="assist">NEED ASSISTANCE</a>-->
                                </div>
                                <div class="form-widget">
                                    <div class="form-two-col">
                                        <div class="form-group">
                                            <input type="text" placeholder="Fullname" class="form-control username requiredCheck restrictSpecial" data-check="Name" name="editpro_name" id="editpro_name" value="<?=$user[0]['name']?>" />
                                        </div>
                                        <div class="form-group">
                                            <input type="email" placeholder="Email Address" class="form-control email requiredCheck" data-check="Email" name="editpro_email" id="editpro_email" value="<?=$user[0]['email']?>" />
                                        </div>
                                    </div>
                                    <div class="form-two-col">
                                        <div class="form-group">
                                            <select class="form-control username requiredCheck" data-check="Gender" name="editpro_gender" id="editpro_gender">
                                                <option value="">Gender</option>
                                                <option value="Male" <?php if($user[0][ 'gender']=='Male' ){ print 'selected'; }?>>Male</option>
                                                <option value="Female" <?php if($user[0][ 'gender']=='Female' ){ print 'selected'; }?>>Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="phone" placeholder="Mobile" class="form-control phone" name="editpro_phone" value="<?=$user[0]['phone_no']?>" onkeypress="return isNumber(this.event)" />
                                        </div>
                                    </div>
                                    <div class="form-two-col">
                                        <div class="form-group">
                                            <input type="password" placeholder="Password" name="editpro_pwd" id="editpro_pwd" class="form-control password" />
                                        </div>
                                        <div class="form-group">
                                            <input type="password" placeholder="Confirm Password" name="editpro_cnfpwd" id="editpro_cnfpwd" class="form-control password" />
                                        </div>
                                    </div>
                                </div>
                                <div class="sub-title">
                                    <h3>Billing</h3>
                                </div>
                                <div class="form-widget">
                                    <div class="form-two-col">
                                        <div class="form-group">
                                            <input type="text" placeholder="Card Number" class="form-control cardnumber" onkeypress="return isNumber(this.event)" name="editpro_card" id="editpro_card" />
                                        </div>
                                        <div class="form-group">
                                            <div class="inline-items vcenter-items">
                                                <!--<span>Expiry</span>-->
                                                <input type="text" placeholder="Expiry Month (MM)" class="form-control month" style="width: 48%;" onkeypress="return isNumber(this.event)" name="editpro_cardm" id="editpro_cardm" />
                                                <input type="text" placeholder="Expiry Year (YYYY)" class="form-control year" style="width: 48%;" onkeypress="return isNumber(this.event)" name="editpro_cardy" id="editpro_cardy" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-two-col">
                                        <div class="form-group">
                                            <input type="password" placeholder="CVV" class="form-control cvv" onkeypress="return isNumber(this.event)" name="editpro_card_cvv" id="editpro_card_cvv" />
                                        </div>
                                    </div>
                                    <div class="form-two-col">
                                        <div class="form-group">
                                            <input type="text" placeholder="Address" class="form-control addrs" name="editpro_address" id="editpro_address" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="Postcode" class="form-control postcode" onkeypress="return isNumber(this.event)" name="editpro_pin" id="editpro_pin" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-action inline-items hright-items">
                                    <input type="submit" value="Update Details" id="edit_personal_details_btn" />
                                </div>
                            </form>
                            <div style="text-align:center;"><span class="personal-details-message" style="color:red;font-style: italic;"></span>
                            </div>
                        </div>
                        <!--PERSONAL DETAILS-->
                        <!--ACCOUNT SETTINGS-->
                        <div class="tab_content">
                            <div class="sub-title">
                                <h3>ACCOUNT SETTINGS</h3>
                            </div>
                            <div class="paging-list acc_act">
                                <form id="ac_settings-form" method="post">
                                    <label class="acc_setng">
                                        <p>Recieve Emails From Adult Lounge</p>
                                        <div class="on">
                                            <input type="radio" name="ac_email" value="Y" <?php if($user[0]['receiveEmail'] == 'Y'){ print 'checked';}?>>ON <span class="check_red"></span>
                                        </div>
                                        <div class="off">
                                            <input type="radio" name="ac_email" value="N" <?php if($user[0]['receiveEmail'] == 'N'){ print 'checked';}?>>OFF <span class="check_red"></span>
                                        </div>
                                    </label>
                                    <label class="acc_setng">
                                        <p>Allow Performers to contact me</p>
                                        <div class="on">
                                            <input type="radio" name="ac_contact" value="Y" <?php if($user[0]['allowContact'] == 'Y'){ print 'checked';}?>>ON <span class="check_red"></span>
                                        </div>
                                        <div class="off">
                                            <input type="radio" name="ac_contact" value="N" <?php if($user[0]['allowContact'] == 'N'){ print 'checked';}?>>OFF <span class="check_red"></span>
                                        </div>
                                    </label>
                                    <label class="acc_setng">
                                        <p>Save My History</p>
                                        <div class="on">
                                            <input type="radio" name="ac_history" value="Y" <?php if($user[0]['saveHistory'] == 'Y'){ print 'checked';}?>>ON <span class="check_red"></span>
                                        </div>
                                        <div class="off">
                                            <input type="radio" name="ac_history" value="N" <?php if($user[0]['saveHistory'] == 'N'){ print 'checked';}?>>OFF <span class="check_red"></span>
                                        </div>
                                    </label>
                                    <label class="acc_setng">
                                        <div class="col_2">
                                            <p>Set a maxium Credit per month</p>
                                            <div class="on">
                                                <input type="radio" class="ac_credit" name="ac_credit" value="Y" <?php if($user[0]['maxCredit'] == 'Y'){ print 'checked';}?>>ON <span class="check_red"></span>
                                            </div>
                                            <div class="off">
                                                <input type="radio" class="ac_credit" name="ac_credit" value="N" <?php if($user[0]['maxCredit'] == 'N'){ print 'checked';}?>>OFF <span class="check_red"></span>
                                            </div>
                                        </div>
                                        <div class="col_2 set_crdt <?php if($user[0]['maxCredit'] == 'N'){ print 'hide_content';}?>">
                                            <p>Set Limit</p>
                                            <input type="text" class="maxcrdt man" name="ac_maxcrdt" id="ac_maxcrdt" data-check="Max Credits" value="<?=$user[0]['creditLimit']?>" placeholder="MAX CREDITS" onkeypress="return isNumber(this.event)">
                                        </div>
                                    </label>
                                    <label class="acc_setng">
                                        <p>Block Messages</p>
                                        <div class="on">
                                            <input type="radio" name="ac_block" value="Y" <?php if($user[0]['blockMessage'] == 'Y'){ print 'checked';}?>>ON <span class="check_red"></span>
                                        </div>
                                        <div class="off">
                                            <input type="radio" name="ac_block" value="N" <?php if($user[0]['blockMessage'] == 'N'){ print 'checked';}?>>OFF <span class="check_red"></span>
                                        </div>
                                    </label>
                                    <input type="submit" value="Update Details" id="edit_acc_act_btn" class="btn">
                                </form>
                                <div style="text-align:center;">
                                    <span class="account-settings-message" style="color:red; font-style: italic;"></span>
                                </div>
                            </div>
                        </div>
                        <!--ACCOUNT SETTINGS-->
                        <!--SUBSCRIPTION-->
                        <div class="tab_content">
                            <div class="sub-title">
                                <h3>SUBSCRIPTIONS</h3>
                                <form>
                                    <input type="text" placeholder="Search" id="subs_search" style="color: #b8a368;" onkeyup="search_suggestion(this.value)" />
                                </form>
                            </div>
                            <div class="paging-list">
                                <ul class="subs-ul">
                                    <?php
                                    if(!empty($subs)){
                                        for($k=0;$k<count($subs);$k++){
                                    ?>
                                    <li>
                                        <span class="pfrm_pic">
                                            <?php if($subs[$k]['image'] != ''){ ?>
                                            <img src="<?=base_url('assets/profile_image/'.$subs[$k]['image'])?>" alt="<?=($subs[$k]['display_name'] != '') ? $subs[$k]['display_name'] : $subs[$k]['name']?>" height="50" width="65" />
                                            <?php }else{ ?>
                                            <img src="<?=base_url('assets/images/noimage.png')?>" alt="<?=($subs[$k]['display_name'] != '') ? $subs[$k]['display_name'] : $subs[$k]['name']?>" height="50" width="65" />
                                            <?php } ?>
                                        </span>
                                        <span class="pfrm_name"><?=($subs[$k]['display_name'] != '') ? $subs[$k]['display_name'] : $subs[$k]['name']?> <?=$subs[$k]['usernm']?></span>
                                        <span class="">
                                            <a href="<?=base_url('performer/'.$subs[$k]['id'].'/'.strtolower(str_replace(' ', '_', ($subs[$k]['display_name'] != '') ? $subs[$k]['display_name'] : $subs[$k]['name'])).'/')?>" class="btn radius0 text-center">View</a>
                                        </span>
                                    </li>
                                    <?php } } else{ ?>
                                    <h3 class="no-subs">No Subscription Found !!!</h3>
                                    <?php } ?>
                                </ul>
                                <?php if(!empty($subs)){ ?> <a href="<?=base_url('subscriptions-list')?>" class="btn radius0 text-center">View All</a>
                                <?php } ?>
                            </div>
                        </div>
                        <!--SUBSCRIPTION-->
                        <!--HISTORY-->
                        <div class="tab_content">
                            <div class="sub-title">
                                <h3>HISTORY</h3>
                            </div>
                            <div class="paging-list">
                                <ul class="subs-ul">
                                    <?php
                                    if(!empty($history)){
                                        for($k=0;$k<count($history);$k++){
                                    ?>
                                    <li> <span class="pfrm_pic">
                                            <?php if($history[$k]['image'] != ''){ ?>
                                            <img src="<?=base_url('assets/profile_image/'.$history[$k]['image'])?>" alt="<?=($history[$k]['display_name'] != '') ? $history[$k]['display_name'] : $history[$k]['name']?>" height="50" width="65" />
                                            <?php } else{ ?>
                                            <img src="<?=base_url('assets/images/noimage.png')?>" alt="<?=($history[$k]['display_name'] != '') ? $history[$k]['display_name'] : $history[$k]['name']?>" height="50" width="65" />
                                            <?php } ?>
                                        </span>
                                        <span class="pfrm_name"><?=($history[$k]['display_name'] != '') ? $history[$k]['display_name'] : $history[$k]['name']?></span>
                                        <span class="pfrm_name"><?=$history[$k]['usernm']?></span>
                                        <div>
                                            <a href="<?=base_url('performer/'.$history[$k]['id'].'/'.strtolower(str_replace(' ', '_', ($history[$k]['display_name'] != '') ? $history[$k]['display_name'] : $history[$k]['name'])).'/')?>" class="btn radius0 text-center">View</a>
                                        </div>
                                    </li>
                                    <?php } }else{ ?>
                                    <h3 class="no-subs-ac">No History Found !!!</h3>
                                    <?php } ?>
                                </ul>
                                <?php if(!empty($history)){ ?> <a href="<?=base_url('my-shows')?>" class="btn radius0 text-center">View All</a>
                                <?php } ?>
                            </div>
                        </div>
                        <!--HISTORY-->
                        <div class="tab_content">
                            <h3>PRIVACY</h3>
                        </div>
                        <div class="tab_content">
                            <div class="sub-title">
                                <h3>INVOICES</h3>
                                <form>
                                    <input type="text" placeholder="Search" />
                                </form>
                            </div>
                            <div class="paging-list">
                                <ul>
                                    <li>
                                        <div> <span class="date">09 MARCH 19</span>
                                            <span class="invoice">CREDITS INVOICE</span>
                                        </div>
                                        <div> <a href="javascript:void(0)" class="brdrbtn text-center">View</a>
                                            <a href="javascript:void(0)" class="btn radius0 text-center">Download</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div> <span class="date">09 MARCH 19</span>
                                            <span class="invoice">CREDITS INVOICE</span>
                                        </div>
                                        <div> <a href="javascript:void(0)" class="brdrbtn text-center">View</a>
                                            <a href="javascript:void(0)" class="btn radius0 text-center">Download</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div> <span class="date">09 MARCH 19</span>
                                            <span class="invoice">CREDITS INVOICE</span>
                                        </div>
                                        <div> <a href="javascript:void(0)" class="brdrbtn text-center">View</a>
                                            <a href="javascript:void(0)" class="btn radius0 text-center">Download</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div> <span class="date">09 MARCH 19</span>
                                            <span class="invoice">CREDITS INVOICE</span>
                                        </div>
                                        <div> <a href="javascript:void(0)" class="brdrbtn text-center">View</a>
                                            <a href="javascript:void(0)" class="btn radius0 text-center">Download</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div> <span class="date">09 MARCH 19</span>
                                            <span class="invoice">CREDITS INVOICE</span>
                                        </div>
                                        <div> <a href="javascript:void(0)" class="brdrbtn text-center">View</a>
                                            <a href="javascript:void(0)" class="btn radius0 text-center">Download</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div> <span class="date">09 MARCH 19</span>
                                            <span class="invoice">CREDITS INVOICE</span>
                                        </div>
                                        <div> <a href="javascript:void(0)" class="brdrbtn text-center">View</a>
                                            <a href="javascript:void(0)" class="btn radius0 text-center">Download</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="mdl-ftr">
                    <p><span>LEGAL DISCLAIMER: </span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam rutrum lorem nisl. Aliquam erat volutpat. Proin vulputate enim ac hendrerit sagittis. </p>
                </div>-->
                <div class="form-action">
                    <ul>
                        <li>
                            <input type="button" value="Cancel" id="cancel_btn" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
