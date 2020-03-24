<?php $this->load->view('admin/layout/header'); ?>
<?php $this->load->view('admin/layout/sidemenu'); ?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?=base_url('admin/dashboard')?>">Dashboard</a></li>
                <li><a>Performer's Vote</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="col-xs-10">
                    <h3 class="page-heding-title">

                    </h3>
                </div>
            </div>
            <div class="panel">
                <div class="panel-content">
                    <div class="table-responsive">
                        <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Vote</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($vote)) {
                                    $i = 1;
                                    foreach ($vote as $vt) {
                                ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td>
                                        <?php if($vt->image != ''){ ?>
                                        <img src="<?=base_url('assets/profile_image/'.$vt->image)?>" style="height:70px; width:100px;">
                                        <?php }else{ ?>
                                        <img src="<?=base_url('assets/images/noimage.png')?>" style="height:70px; width:100px;">
                                        <?php } ?>
                                    </td>
                                    <td><?=stripslashes($vt->name)?></td>
                                    <td><?=stripslashes($vt->display_name)?></td>

                                    <td><?=$vt->vote?></td>
                                </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/layout/footer'); ?>
<script src="<?=base_url()?>backend/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>backend/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
<script src="<?=base_url()?>backend/javascripts/examples/tables/data-tables.js"></script>
