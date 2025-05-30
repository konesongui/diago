<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion des bons de livraison</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/deliveryitem/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Nouveau bon de livraison
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped deliveryDatatable">
                        <thead>
                            <tr>
                                <th>N° BL</th>
                                <th>Désignation</th>
                                <th>Client</th>
                                <th>Dates</th>
                                <th>Conditions de paiement</th>
                                <th>Lieu de livraison</th>
                                <th>Date de création</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/delivery/index.js') ?>"></script>
<?= $this->endSection() ?> 