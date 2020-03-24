<section class="modal">
    <span class="overlay"></span>
    <span class="close_reg" id="cancel_btn">X</span>
    <div class="modal-body reg_first">
        <div class="modal-widget mdl-lft">
            <h2><img src="<?=base_url('assets/images/line-logo.png')?>" alt="logo" /> REGISTER</h2>
            <p>Please fill in this form to create an account.</p>
            <form id="registration-form" method="post" autocomplete="off">
                <div class="form-group">
                    <input type="text" placeholder="Name" name="reg_name" id="reg_name" class="form-control username requiredCheck restrictSpecial" data-check="Name" />
                </div>
                <div class="form-group">
                    <input type="text" name="reg_email" id="reg_email" placeholder="Email Address" class="form-control email requiredCheck" data-check="Email" />
                </div>
                <div class="form-group">
                    <select class="form-control password requiredCheck" name="reg_type" id="reg_type" data-check="User Type">
                        <option value="">Select User Type</option>
                        <option value="1">User</option>
                        <option value="2">Performer</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control password requiredCheck" name="reg_gender" id="reg_gender" data-check="Gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="password" name="reg_pwd" id="reg_pwd" placeholder="Password" class="form-control password requiredCheck" data-check="Password" />
                </div>
                <div class="form-group">
                    <input type="password" name="reg_cnf_pwd" id="reg_cnf_pwd" placeholder="Confirm Password" class="form-control password requiredCheck" data-check="Confirm Password" />
                </div>
                <!--<div class="form-group">
		<div class="form-check">
			<label class="check">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Done
				<input type="checkbox" checked="checked">
				<span class="checkmark"></span>
			</label>
		</div>
		<div class="form-check">
			<label class="check">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Done
				<input type="checkbox">
				<span class="checkmark"></span>
			</label>
		</div>
	</div>-->
                <div class="form-action wid">
                    <ul>
                        <li><input type="submit" value="Sign Up" id="reg_btn" /></li>
                        <li><input type="button" value="Existing User?" id="reg_login_btn" /></li>
                        <!--<li><a href="javascript:void(0)" class="assist">NEED ASSISTANCE</a></li>-->
                    </ul>
                </div>
            </form>
            <span style="color:red;" class="signup-message"></span>
            <h4>LEGAL DISCLAIMER:</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam rutrum lorem nisl. Aliquam erat volutpat. Proin vulputate enim ac hendrerit sagittis. </p>
        </div>
        <div class="modal-widget mdl-rgt">
            <img src="<?=base_url('assets/images/img-002.jpg')?>" alt="img" />
        </div>
    </div>
    <div class="modal-body reg_success hide_content">
        <div class="modal-widget mdl-lft">
            <div class="confrm_hed">
                <p>THANK YOU FOR REGISTERING</p>
            </div>
            <div class="confrm_body">
                <p>Your information has been submitted and an email sent to <span id="reg_success_email"></span>. This email contains a link to verify your email address and to continue processing your account information.</p>
                <p>If you don't see an email from INFO@ADULTLOUNGE.COM in your inbox, please check your spam folder before trying to signup again.</p>
                <p>In addition to creating an account, you must upload the following documentation when prompted:</p>
                <p>A scanned copy or photo of your driver's license, passport (picture page), or other official photo identification as proof of age.</p>
                <p>A picture of you holding your ID.</p>
                <p>Thank you,</p>
                <img src="<?=base_url('assets/images/problem.png')?>" alt="Adult Lounge" />
                <p>The ADULT LOUNGE Support Staff</p>
				<a href="<?=base_url('login')?>" class="btn">OK</a>
            </div>
        </div>
    </div>
</section>
