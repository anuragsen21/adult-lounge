<?php $this->load->view('admin/layout/header'); ?>
<?php $this->load->view('admin/layout/sidemenu'); ?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                <li><a><?=$mode?> Show Type</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="col-sm-12 col-md-12">
            <?php if($this->session->flashdata('success_msg')){ ?>
            <!--<div class="alert alert-success fade in">
    <a href="#" class="close" data-dismiss="alert">×</a>
    <h4><i class="icon fa fa-check"></i> Success!</h4>
    <?=$this->session->flashdata('success_msg')?>
</div>-->
            <?php } ?>
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="<?=base_url('admin/show-type')?>" class="form-horizontal" method="post">
                                <input type="hidden" name="show_edit_id" value="<?php if(isset($show)){ print $show[0]['id']; }?>" />
                                <div class="form-group">
                                    <label for="email2" class="col-sm-4 control-label">Show Type Name *</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="showname" class="form-control commonRequired" value="<?php if(isset($show)){ print $show[0]['name']; }?>" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="submit" class="btn btn-wide btn-primary form-submit-button"><?php if($mode == 'Add'){ ?>Save<?php }else{ ?>Save Changes<?php } ?> </button>
                                        <a href="<?=base_url('admin/show-type')?>"><button type="button" class="btn btn-danger">Cancel</button></a>
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
