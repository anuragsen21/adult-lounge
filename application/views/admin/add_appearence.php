<?php $this->load->view('admin/layout/header'); ?>
<?php $this->load->view('admin/layout/sidemenu'); ?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                <li><a><?=$mode?> Appearence</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="<?=base_url('admin/appearence')?>" class="form-horizontal" method="post">
                                <input type="hidden" name="aprnc_edit_id" value="<?php if(isset($appearence)){ print $appearence[0]['id']; }?>" />
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Appearence Name *</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="aprncname" class="form-control commonRequired" value="<?php if(isset($appearence)){ print $appearence[0]['name']; }?>" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="submit" class="btn btn-wide btn-primary form-submit-button"><?php if($mode == 'Add'){ ?>Save<?php }else{ ?>Save Changes<?php } ?> </button>
                                        <a href="<?=base_url('admin/appearence')?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/layout/footer'); ?>
