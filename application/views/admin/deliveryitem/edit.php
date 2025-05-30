<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier le bon de livraison #<?= $delivery['delivery_number'] ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/deliveryitem') ?>" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <form action="<?= base_url('admin/deliveryitem/update/' . $delivery['id']) ?>" method="POST" id="editDeliveryForm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery_number">N° BL</label>
                                    <input type="text" class="form-control" id="delivery_number" name="delivery_number" value="<?= $delivery['delivery_number'] ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="designation">Désignation</label>
                                    <input type="text" class="form-control" id="designation" name="designation" value="<?= $delivery['designation'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_date">Date de livraison</label>
                                    <input type="date" class="form-control" id="delivery_date" name="delivery_date" value="<?= date('Y-m-d', strtotime($delivery['delivery_date'])) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="valid_until">Validité</label>
                                    <input type="date" class="form-control" id="valid_until" name="valid_until" value="<?= date('Y-m-d', strtotime($delivery['valid_until'])) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Client</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value="">Sélectionner un client</option>
                                        <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['id'] ?>" <?= $customer['id'] == $delivery['customer_id'] ? 'selected' : '' ?>>
                                            <?= $customer['name'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_location">Lieu de livraison</label>
                                    <input type="text" class="form-control" id="delivery_location" name="delivery_location" value="<?= $delivery['delivery_location'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="payment_terms">Conditions de paiement</label>
                                    <input type="text" class="form-control" id="payment_terms" name="payment_terms" value="<?= $delivery['payment_terms'] ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Articles</h4>
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Description</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire HT</th>
                                            <th>TVA (%)</th>
                                            <th>Total HT</th>
                                            <th>Total TTC</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($delivery['items'] as $index => $item): ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="items[<?= $index ?>][product_name]" value="<?= $item['product_name'] ?>" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="items[<?= $index ?>][description]" value="<?= $item['description'] ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="items[<?= $index ?>][quantity]" value="<?= $item['quantity'] ?>" min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control unit-price" name="items[<?= $index ?>][unit_price]" value="<?= $item['unit_price'] ?>" step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control vat-rate" name="items[<?= $index ?>][vat_rate]" value="<?= $item['vat_rate'] ?>" step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total-ht" value="<?= number_format($item['total_ht'], 2, ',', ' ') ?> €" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total-ttc" value="<?= number_format($item['total_ttc'], 2, ',', ' ') ?> €" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <button type="button" class="btn btn-success btn-sm" id="addItem">
                                                    <i class="fa fa-plus"></i> Ajouter un article
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">Total HT</th>
                                            <td colspan="3">
                                                <input type="text" class="form-control" id="total_ht" value="<?= number_format($delivery['amount_ht'], 2, ',', ' ') ?> €" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">Total TVA</th>
                                            <td colspan="3">
                                                <input type="text" class="form-control" id="total_vat" value="<?= number_format($delivery['vat_amount'], 2, ',', ' ') ?> €" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">Total TTC</th>
                                            <td colspan="3">
                                                <input type="text" class="form-control" id="total_ttc" value="<?= number_format($delivery['amount_ttc'], 2, ',', ' ') ?> €" readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="<?= base_url('admin/deliveryitem') ?>" class="btn btn-default">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let itemIndex = <?= count($delivery['items']) ?>;

    // Fonction pour calculer les totaux d'une ligne
    function calculateRowTotals(row) {
        const quantity = parseFloat(row.find('.quantity').val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const vatRate = parseFloat(row.find('.vat-rate').val()) || 0;

        const totalHT = quantity * unitPrice;
        const totalTTC = totalHT * (1 + vatRate / 100);

        row.find('.total-ht').val(totalHT.toFixed(2).replace('.', ',') + ' €');
        row.find('.total-ttc').val(totalTTC.toFixed(2).replace('.', ',') + ' €');

        calculateTotals();
    }

    // Fonction pour calculer les totaux généraux
    function calculateTotals() {
        let totalHT = 0;
        let totalVAT = 0;
        let totalTTC = 0;

        $('#itemsTable tbody tr').each(function() {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
            const vatRate = parseFloat($(this).find('.vat-rate').val()) || 0;

            const rowHT = quantity * unitPrice;
            const rowVAT = rowHT * (vatRate / 100);
            const rowTTC = rowHT + rowVAT;

            totalHT += rowHT;
            totalVAT += rowVAT;
            totalTTC += rowTTC;
        });

        $('#total_ht').val(totalHT.toFixed(2).replace('.', ',') + ' €');
        $('#total_vat').val(totalVAT.toFixed(2).replace('.', ',') + ' €');
        $('#total_ttc').val(totalTTC.toFixed(2).replace('.', ',') + ' €');
    }

    // Ajouter un nouvel article
    $('#addItem').click(function() {
        const newRow = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="items[${itemIndex}][product_name]" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="items[${itemIndex}][description]">
                </td>
                <td>
                    <input type="number" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" class="form-control unit-price" name="items[${itemIndex}][unit_price]" value="0.00" step="0.01" required>
                </td>
                <td>
                    <input type="number" class="form-control vat-rate" name="items[${itemIndex}][vat_rate]" value="20.00" step="0.01" required>
                </td>
                <td>
                    <input type="text" class="form-control total-ht" value="0,00 €" readonly>
                </td>
                <td>
                    <input type="text" class="form-control total-ttc" value="0,00 €" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsTable tbody').append(newRow);
        itemIndex++;
    });

    // Supprimer un article
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        calculateTotals();
    });

    // Recalculer les totaux lors de la modification des valeurs
    $(document).on('input', '.quantity, .unit-price, .vat-rate', function() {
        calculateRowTotals($(this).closest('tr'));
    });

    // Validation du formulaire
    $('#editDeliveryForm').submit(function(e) {
        e.preventDefault();
        
        if ($('#itemsTable tbody tr').length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez ajouter au moins un article'
            });
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Bon de livraison modifié avec succès',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = '<?= base_url('admin/deliveryitem') ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message || 'Une erreur est survenue'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la modification'
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 