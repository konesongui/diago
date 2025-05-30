<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $title; ?>
        <small><?php echo $title_list; ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li><a href="<?php echo base_url(); ?>admin/invoice">Factures</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title; ?></h3>
                </div>
                <form id="invoiceForm" method="post" action="<?php echo base_url(); ?>admin/invoice/update">
                    <input type="hidden" name="id" value="<?php echo $invoice['id']; ?>">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer">Client <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="customer" name="customer" required>
                                        <option value="">Sélectionner un client...</option>
                                        <?php foreach ($clients as $client) { ?>
                                            <option value="<?php echo $client['id']; ?>" <?php echo ($client['id'] == $invoice['customer_id']) ? 'selected' : ''; ?>><?php echo $client['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="invoice_date">Date de facture <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker" id="invoice_date" name="invoice_date" value="<?php echo date('d/m/Y', strtotime($invoice['invoice_date'])); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="due_date">Date d'échéance <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker" id="due_date" name="due_date" value="<?php echo date('d/m/Y', strtotime($invoice['due_date'])); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 30%">Catégorie</th>
                                                <th style="width: 30%">Article</th>
                                                <th style="width: 10%">Quantité</th>
                                                <th style="width: 10%">Unité</th>
                                                <th style="width: 15%">Prix unitaire</th>
                                                <th style="width: 15%">Total</th>
                                                <th style="width: 5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoice['items'] as $item) { ?>
                                                <tr>
                                                    <td>
                                                        <select class="form-control item-category" name="item_category_id[]" required>
                                                            <option value="">Sélectionner...</option>
                                                            <?php foreach ($itemcatlist as $category) { ?>
                                                                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $item['category_id']) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control item-select" name="item_id[]" required>
                                                            <option value="">Sélectionner...</option>
                                                            <?php foreach ($itemList as $listItem) { ?>
                                                                <?php if ($listItem['category_id'] == $item['category_id']) { ?>
                                                                    <option value="<?php echo $listItem['id']; ?>" 
                                                                            data-price="<?php echo $listItem['price']; ?>" 
                                                                            data-unit="<?php echo $listItem['unit']; ?>"
                                                                            <?php echo ($listItem['id'] == $item['item_id']) ? 'selected' : ''; ?>>
                                                                        <?php echo $listItem['name']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control item-quantity" name="quantity[]" min="0.01" step="0.01" value="<?php echo $item['quantity']; ?>" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control item-unit" name="unit[]" value="<?php echo $item['unit']; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control item-price" name="price[]" min="0.01" step="0.01" value="<?php echo $item['unit_price']; ?>" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control item-total" value="<?php echo number_format($item['line_total'], 2, ',', ' '); ?> €" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <button type="button" class="btn btn-success btn-sm" id="addItem">
                                                        <i class="fa fa-plus"></i> Ajouter une ligne
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">Méthode de paiement</label>
                                    <select class="form-control" id="payment_method" name="payment_method">
                                        <option value="">Sélectionner...</option>
                                        <option value="cash" <?php echo ($invoice['payment_method'] == 'cash') ? 'selected' : ''; ?>>Espèces</option>
                                        <option value="check" <?php echo ($invoice['payment_method'] == 'check') ? 'selected' : ''; ?>>Chèque</option>
                                        <option value="bank_transfer" <?php echo ($invoice['payment_method'] == 'bank_transfer') ? 'selected' : ''; ?>>Virement</option>
                                        <option value="card" <?php echo ($invoice['payment_method'] == 'card') ? 'selected' : ''; ?>>Carte bancaire</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $invoice['notes']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="apply_tva" name="apply_tva" value="1" <?php echo $invoice['apply_tva'] ? 'checked' : ''; ?>> Appliquer la TVA
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tva_rate">Taux de TVA (%)</label>
                                    <input type="number" class="form-control" id="tva_rate" name="tva_rate" value="<?php echo $invoice['tva_rate']; ?>" min="0" max="100" step="0.1">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total HT</label>
                                            <input type="text" class="form-control" id="total_ht" value="<?php echo number_format($invoice['total_ht'], 2, ',', ' '); ?> €" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>TVA</label>
                                            <input type="text" class="form-control" id="tva_amount" value="<?php echo number_format($invoice['tva_amount'], 2, ',', ' '); ?> €" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Total TTC</label>
                                    <input type="text" class="form-control" id="total_ttc" value="<?php echo number_format($invoice['total_ttc'], 2, ',', ' '); ?> €" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="<?php echo base_url(); ?>admin/invoice/view/<?php echo $invoice['id']; ?>" class="btn btn-default">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    // Initialisation des composants
    $('.select2').select2();
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'fr',
        autoclose: true,
        todayHighlight: true
    });

    // Gestion des articles
    var itemList = <?php echo json_encode($itemList); ?>;
    
    // Fonction pour mettre à jour les totaux
    function updateTotals() {
        var totalHT = 0;
        $('.item-total').each(function() {
            var value = parseFloat($(this).val().replace('€', '').replace(',', '.').trim()) || 0;
            totalHT += value;
        });
        
        $('#total_ht').val(totalHT.toFixed(2) + ' €');
        
        var tvaRate = parseFloat($('#tva_rate').val()) || 0;
        var tvaAmount = totalHT * (tvaRate / 100);
        var totalTTC = totalHT + tvaAmount;
        
        $('#tva_amount').val(tvaAmount.toFixed(2) + ' €');
        $('#total_ttc').val(totalTTC.toFixed(2) + ' €');
    }

    // Gestion du changement de catégorie
    $(document).on('change', '.item-category', function() {
        var categoryId = $(this).val();
        var itemSelect = $(this).closest('tr').find('.item-select');
        
        // Filtrer les articles par catégorie
        var filteredItems = itemList.filter(function(item) {
            return item.category_id == categoryId;
        });
        
        // Mettre à jour la liste des articles
        itemSelect.empty().append('<option value="">Sélectionner...</option>');
        filteredItems.forEach(function(item) {
            itemSelect.append('<option value="' + item.id + '" data-price="' + item.price + '" data-unit="' + item.unit + '">' + item.name + '</option>');
        });
    });

    // Gestion du changement d'article
    $(document).on('change', '.item-select', function() {
        var option = $(this).find('option:selected');
        var row = $(this).closest('tr');
        
        if (option.val()) {
            row.find('.item-price').val(option.data('price'));
            row.find('.item-unit').val(option.data('unit'));
            calculateRowTotal(row);
        }
    });

    // Gestion du changement de quantité ou de prix
    $(document).on('input', '.item-quantity, .item-price', function() {
        calculateRowTotal($(this).closest('tr'));
    });

    // Calcul du total par ligne
    function calculateRowTotal(row) {
        var quantity = parseFloat(row.find('.item-quantity').val()) || 0;
        var price = parseFloat(row.find('.item-price').val()) || 0;
        var total = quantity * price;
        
        row.find('.item-total').val(total.toFixed(2) + ' €');
        updateTotals();
    }

    // Gestion de la TVA
    $('#apply_tva, #tva_rate').on('change', function() {
        updateTotals();
    });

    // Ajout d'une nouvelle ligne
    $('#addItem').click(function() {
        var newRow = $('#itemsTable tbody tr:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('').trigger('change');
        $('#itemsTable tbody').append(newRow);
    });

    // Suppression d'une ligne
    $(document).on('click', '.remove-item', function() {
        if ($('#itemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateTotals();
        } else {
            toastr.warning('Vous devez avoir au moins une ligne d\'article');
        }
    });

    // Validation du formulaire
    $('#invoiceForm').submit(function(e) {
        e.preventDefault();
        
        // Vérification du nombre de lignes
        if ($('#itemsTable tbody tr').length === 0) {
            toastr.error('Vous devez ajouter au moins un article');
            return;
        }
        
        // Vérification des totaux
        var totalHT = parseFloat($('#total_ht').val().replace('€', '').replace(',', '.').trim()) || 0;
        if (totalHT <= 0) {
            toastr.error('Le montant total doit être supérieur à 0');
            return;
        }
        
        // Envoi du formulaire
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    window.location.href = '<?php echo base_url(); ?>admin/invoice/view/<?php echo $invoice['id']; ?>';
                } else {
                    if (response.error) {
                        $.each(response.error, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                }
            },
            error: function() {
                toastr.error('Une erreur est survenue');
            }
        });
    });
});
</script> 