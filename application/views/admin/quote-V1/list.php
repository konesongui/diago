<?php

// var_dump($dateStart);
// exit;

// Set all the CRUD variables Stock entry Tool
$dtQuoteID = 'quoteDatatable';

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-file-text-o"></i> Devis
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Liste des devis</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('quote', 'can_add')) { ?>
                                <a href="<?php echo site_url('admin/quoteitem/form') ?>" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Nouveau devis
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover <?= $dtQuoteID ?>">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Désignation</th>
                                        <th>Client</th>
                                        <th>Date d'édition</th>
                                        <th>Montant total</th>
                                        <th>Statut</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?= base_url('assets/js/quote/index.js') ?>"></script>

<!-- Scripts -->
