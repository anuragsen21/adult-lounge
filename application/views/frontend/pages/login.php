<section class="modal">
    <span class="overlay"></span>
	<span class="close_reg" id="cancel_btn">X</span>
    <div class="modal-body">
        <div class="modal-widget mdl-lft">
            <h2><img src="<?=base_url('assets/images/line-logo.png')?>" alt="logo" /> Sign In</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam rutrum lorem nisl. convallis nec semper quis, tincidunt non est. </p>
            <form id="login-form" method="post" autocomplete="off">
                <div class="form-group">
                    <input type="email" placeholder="Email" class="form-control username requiredCheck" data-check="Email" name="login_email" id="login_email" />
                </div>
                <div class="form-group">
                    <input type="password" placeholder="*********" class="form-control password requiredCheck" data-check="Password" name="login_pwd" id="login_pwd" />
                </div>
                <div class="form-group">
                    <ul>
                        <!--<li>
    <div class="form-check">
        <label class="check">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Done
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
    </div>
</li>-->
                        <li><a href="javascript:void(0)">Forgot Password?</a></li>
                    </ul>
                </div>
                <div class="form-action">
                    <ul>
                        <li><input type="submit" value="Login" class="" id="login_btn" /></li>
                        <li><input type="button" value="New User?" id="login_reg_btn" /></li>
                        <!--<li><a href="javascript:void(0)" class="assist">NEED ASSISTANCE</a></li>-->
                    </ul>
                </div>
            </form>
            <span style="color:red;" class="login-message"></span>
            <h4>LEGAL DISCLAIMER:</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam rutrum lorem nisl. Aliquam erat volutpat. Proin vulputate enim ac hendrerit sagittis. </p>
        </div>
        <div class="modal-widget mdl-rgt">
            <img src="<?=base_url('assets/images/img-001.jpg')?>" alt="img" />
        </div>
    </div>
</section>
