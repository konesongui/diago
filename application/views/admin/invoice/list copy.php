<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $title; ?>
        <small><?php echo $title_list; ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title_list; ?></h3>
                    <div class="box-tools pull-right">
                        <?php if ($this->rbac->hasPrivilege('invoice', 'can_add')) { ?>
                            <a href="<?php echo base_url(); ?>admin/invoice/form" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Nouvelle facture
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="invoiceTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N° Facture</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Échéance</th>
                                    <th>Montant HT</th>
                                    <th>TVA</th>
                                    <th>Total TTC</th>
                                    <th>Payé</th>
                                    <th>Reste</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
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

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h4>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette facture ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="paymentModalLabel">Ajouter un paiement</h4>
            </div>
            <form id="paymentForm">
                <div class="modal-body">
                    <input type="hidden" name="invoice_id" id="payment_invoice_id">
                    <div class="form-group">
                        <label for="amount">Montant</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Date de paiement</label>
                        <input type="text" class="form-control datepicker" id="payment_date" name="payment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="method">Méthode de paiement</label>
                        <select class="form-control" id="method" name="method" required>
                            <option value="">Sélectionner...</option>
                            <option value="cash">Espèces</option>
                            <option value="check">Chèque</option>
                            <option value="bank_transfer">Virement</option>
                            <option value="card">Carte bancaire</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reference">Référence</label>
                        <input type="text" class="form-control" id="reference" name="reference">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialisation de la table
    var table = $('#invoiceTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>admin/invoice/data",
            "type": "POST"
        },
        "columns": [
            { "data": "invoice_number" },
            { "data": "customer_name" },
            { "data": "invoice_date" },
            { "data": "due_date" },
            { 
                "data": "total_ht",
                "render": function(data) {
                    return parseFloat(data).toFixed(2) + ' €';
                }
            },
            { 
                "data": "tva_amount",
                "render": function(data) {
                    return parseFloat(data).toFixed(2) + ' €';
                }
            },
            { 
                "data": "total_ttc",
                "render": function(data) {
                    return parseFloat(data).toFixed(2) + ' €';
                }
            },
            { 
                "data": "amount_paid",
                "render": function(data) {
                    return parseFloat(data).toFixed(2) + ' €';
                }
            },
            { 
                "data": "remaining_amount",
                "render": function(data) {
                    return parseFloat(data).toFixed(2) + ' €';
                }
            },
            { 
                "data": "status",
                "render": function(data) {
                    var status = '';
                    switch(parseInt(data)) {
                        case 0:
                            status = '<span class="label label-warning">Non payée</span>';
                            break;
                        case 1:
                            status = '<span class="label label-success">Payée</span>';
                            break;
                        case 2:
                            status = '<span class="label label-danger">Annulée</span>';
                            break;
                    }
                    return status;
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    var actions = '';
                    
                    // Bouton Voir
                    actions += '<a href="<?php echo base_url(); ?>admin/invoice/view/' + row.id + '" class="btn btn-info btn-xs" title="Voir"><i class="fa fa-eye"></i></a> ';
                    
                    // Bouton Imprimer
                    actions += '<a href="<?php echo base_url(); ?>admin/invoice/print/' + row.id + '" class="btn btn-default btn-xs" title="Imprimer" target="_blank"><i class="fa fa-print"></i></a> ';
                    
                    <?php if ($this->rbac->hasPrivilege('invoice', 'can_edit')) { ?>
                        // Bouton Modifier (si non payée)
                        if (row.status == 0) {
                            actions += '<a href="<?php echo base_url(); ?>admin/invoice/edit/' + row.id + '" class="btn btn-primary btn-xs" title="Modifier"><i class="fa fa-edit"></i></a> ';
                        }
                        
                        // Bouton Payer (si non payée)
                        if (row.status == 0 && row.remaining_amount > 0) {
                            actions += '<button type="button" class="btn btn-success btn-xs add-payment" data-id="' + row.id + '" data-remaining="' + row.remaining_amount + '" title="Ajouter un paiement"><i class="fa fa-money"></i></button> ';
                        }
                    <?php } ?>
                    
                    <?php if ($this->rbac->hasPrivilege('invoice', 'can_delete')) { ?>
                        // Bouton Supprimer (si non payée)
                        if (row.status == 0) {
                            actions += '<button type="button" class="btn btn-danger btn-xs delete-invoice" data-id="' + row.id + '" title="Supprimer"><i class="fa fa-trash"></i></button>';
                        }
                    <?php } ?>
                    
                    return actions;
                }
            }
        ],
        "order": [[2, "desc"]],
        "language": {
            "url": "<?php echo base_url(); ?>assets/js/datatables/french.json"
        }
    });

    // Initialisation du datepicker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'fr',
        autoclose: true,
        todayHighlight: true
    });

    // Gestion de la suppression
    var deleteId = null;
    
    $(document).on('click', '.delete-invoice', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });
    
    $('#confirmDelete').click(function() {
        if (deleteId) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/invoice/delete',
                type: 'POST',
                data: { id: deleteId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Une erreur est survenue');
                }
            });
        }
    });

    // Gestion des paiements
    $(document).on('click', '.add-payment', function() {
        var id = $(this).data('id');
        var remaining = $(this).data('remaining');
        
        $('#payment_invoice_id').val(id);
        $('#amount').val(remaining);
        $('#amount').attr('max', remaining);
        $('#payment_date').val('');
        $('#method').val('');
        $('#reference').val('');
        $('#notes').val('');
        
        $('#paymentModal').modal('show');
    });

    $('#paymentForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/invoice/addPayment',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#paymentModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
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