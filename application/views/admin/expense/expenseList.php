<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('depense', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_expense'); ?>  </h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/expense"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label> <small class="req">*</small>


                                    <select autofocus="" id="exp_head_id" name="exp_head_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($expheadlist as $exphead) {
                                            ?>
                                            <option value="<?php echo $exphead['id'] ?>"<?php
                                            if (set_value('exp_head_id') == $exphead['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $exphead['exp_category'] ?></option>

                                            <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exp_head_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Caisses</label> <small class="req">*</small>

                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($incomelist as $exphead) {
                                            ?>
                                            <option value="<?php echo $exphead['id'] ?>"<?php
                                            if (set_value('exp_head_id') == $exphead['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $exphead['name'] ?></option>

                                            <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('inc_head_id'); ?></span>
                                </div>
                                <div class="form-group" hidden>
                                    <label for="exampleInputEmail1">Utilisateurs</label> <small class="req">*</small>
                                    <input id="user" name="user" placeholder="" type="text" class="form-control"  value="<?php echo $this->customlib->getAdminSessionUserName(); ?>" />
                                    <span class="text-danger"><?php echo form_error('user'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label> <small class="req">*</small>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group" hidden>
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_no'); ?></label>
                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('invoice_no'); ?>" />
                                    <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                                    <input id="date" name="date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label> <small class="req">*</small>
                                    <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount'); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('documents'); ?>" />
                                    <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                <button type="reset" class="btn btn-secondary bg-red">Annuler</button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('depense', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line(''); ?> <b>DEPENSE GENERALE : <?= number_format(floatval($expenseTotal->amount??0), 0, ',', ' ') ;?>  FCA </b></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('expense_list'); ?></div>
                            <div class="table-responsive"> 
                                <table class="table table-striped table-bordered table-hover expense-list" data-export-title="<?php echo $this->lang->line('expense_list'); ?>">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('name'); ?>
                                            </th>
                                             <!--<th><?php echo $this->lang->line('description'); ?>
                                            </th>
                                           <th><?php echo $this->lang->line('invoice_no'); ?>
                                            </th>-->
                                            <th><?php echo $this->lang->line('date'); ?>
                                            </th>
                                            <th><?php echo $this->lang->line('expense_head'); ?>
                                            </th>
                                            <th>Caisse impactée
                                            </th>
                                            <th><?php echo $this->lang->line('amount'); ?>
                                            </th>

                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table><!-- /.table -->

                            </div>  

                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('expense-list','admin/expense/getexpenselist',[],[],10);
    });
} ( jQuery ) )
</script>