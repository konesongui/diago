<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du bon de livraison #<?= $delivery['delivery_number'] ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/deliveryitem') ?>" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Retour
                        </a>
                        <?php if ($delivery['status'] == 1): ?>
                        <a href="<?= base_url('admin/deliveryitem/edit/' . $delivery['id']) ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Modifier
                        </a>
                        <?php endif; ?>
                        <a href="<?= base_url('admin/deliveryitem/print/' . $delivery['id']) ?>" class="btn btn-info btn-sm" target="_blank">
                            <i class="fa fa-print"></i> Imprimer
                        </a>
                        <button type="button" class="btn btn-success btn-sm" id="sendEmailBtn">
                            <i class="fa fa-envelope"></i> Envoyer par email
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informations générales</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>N° BL</th>
                                    <td><?= $delivery['delivery_number'] ?></td>
                                </tr>
                                <tr>
                                    <th>Désignation</th>
                                    <td><?= $delivery['designation'] ?></td>
                                </tr>
                                <tr>
                                    <th>Date de livraison</th>
                                    <td><?= date('d/m/Y', strtotime($delivery['delivery_date'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Validité</th>
                                    <td><?= date('d/m/Y', strtotime($delivery['valid_until'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="label <?= $this->getStatusClass($delivery['status']) ?>">
                                            <?= $this->getStatusLabel($delivery['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Informations client</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Client</th>
                                    <td><?= $delivery['customer_name'] ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= $delivery['customer_email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td><?= $delivery['customer_phone'] ?></td>
                                </tr>
                                <tr>
                                    <th>Lieu de livraison</th>
                                    <td><?= $delivery['delivery_location'] ?></td>
                                </tr>
                                <tr>
                                    <th>Conditions de paiement</th>
                                    <td><?= $delivery['payment_terms'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Articles</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Description</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire HT</th>
                                        <th>Total HT</th>
                                        <th>TVA</th>
                                        <th>Total TTC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($delivery['items'] as $item): ?>
                                    <tr>
                                        <td><?= $item['product_name'] ?></td>
                                        <td><?= $item['description'] ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= number_format($item['unit_price'], 2, ',', ' ') ?> €</td>
                                        <td><?= number_format($item['total_ht'], 2, ',', ' ') ?> €</td>
                                        <td><?= number_format($item['vat_amount'], 2, ',', ' ') ?> €</td>
                                        <td><?= number_format($item['total_ttc'], 2, ',', ' ') ?> €</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total HT</th>
                                        <td><?= number_format($delivery['amount_ht'], 2, ',', ' ') ?> €</td>
                                        <td><?= number_format($delivery['vat_amount'], 2, ',', ' ') ?> €</td>
                                        <td><?= number_format($delivery['amount_ttc'], 2, ',', ' ') ?> €</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <?php if ($delivery['status'] == 3 && !empty($delivery['rejection_reason'])): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h5>Motif du rejet</h5>
                                <p><?= nl2br(htmlspecialchars($delivery['rejection_reason'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#sendEmailBtn').click(function() {
        Swal.fire({
            title: 'Envoi du bon de livraison par email',
            text: "Voulez-vous envoyer ce bon de livraison par email ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, envoyer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin/deliveryitem/sendEmail/' . $delivery['id']) ?>',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if(response.status == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email envoyé avec succès',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message || 'Erreur lors de l\'envoi',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur lors de l\'envoi',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 