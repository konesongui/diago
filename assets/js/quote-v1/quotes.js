"use strict";
// Class definition

// Set all the required variables for the following methods
var dtID = 'quoteDatatable',
    remoteAJAXFunctions = {
        loadData: 'admin/quoteitem/data',
    };

$(document).ready(function() {
    // Configuration de base pour SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // Initialisation de la DataTable
    var quotesTable = $('#'+dtID).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + remoteAJAXFunctions.loadData,
            type: "POST"
        },
        columns: [
            { data: "quote_number" },
            { data: "designation" },
            { 
                data: "customer",
                render: function(data) {
                    return `<div>
                        <strong>${data.name}</strong><br>
                        <small>${data.email}<br>${data.phone}</small>
                    </div>`;
                }
            },
            { 
                data: "dates.emission",
                render: function(data, type, row) {
                    return `<div>
                        <span>Émis le: ${row.dates.emission}</span><br>
                        <small>Valide jusqu'au: ${row.dates.validite}</small>
                    </div>`;
                }
            },
            {
                data: "amount",
                render: function(data) {
                    return `<div>
                        <strong>TTC: ${data.ttc} €</strong><br>
                        <small>HT: ${data.ht} €</small>
                    </div>`;
                }
            },
            {
                data: "status",
                render: function(data) {
                    return `<span class="label ${data.class}">${data.label}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    let actions = '<div class="btn-group">';
                    
                    // Bouton Voir
                    actions += `<button type="button" class="btn btn-default btn-xs view-quote" 
                        data-id="${row.id}" title="Voir">
                        <i class="fa fa-eye"></i>
                    </button>`;
                    
                    // Bouton Modifier (seulement si en attente)
                    if(row.status.code === 1) {
                        actions += `<button type="button" class="btn btn-primary btn-xs edit-quote" 
                            data-id="${row.id}" title="Modifier">
                            <i class="fa fa-pencil"></i>
                        </button>`;
                    }
                    
                    // Bouton Imprimer
                    actions += `<button type="button" class="btn btn-info btn-xs print-quote" 
                        data-id="${row.id}" title="Imprimer">
                        <i class="fa fa-print"></i>
                    </button>`;
                    
                    // Boutons Valider/Rejeter (uniquement pour les devis en attente)
                    if(row.status.code === 1) {
                        actions += `<button type="button" class="btn btn-success btn-xs validate-quote" 
                            data-id="${row.id}" title="Valider">
                            <i class="fa fa-check"></i>
                        </button>`;
                        
                        actions += `<button type="button" class="btn btn-danger btn-xs reject-quote" 
                            data-id="${row.id}" title="Rejeter">
                            <i class="fa fa-times"></i>
                        </button>`;
                    }
                    
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[3, 'desc']], // Tri par date d'émission décroissante
        language: {
            url: base_url + "assets/js/french.json"
        }
    });

    // Fonction pour recharger la table
    function refreshTable() {
        quotesTable.ajax.reload(null, false);
    }

    // Voir un devis
    $('#quotes-table').on('click', '.view-quote', function() {
        var quoteId = $(this).data('id');
        window.location.href = base_url + 'admin/quotes/view/' + quoteId;
    });

    // Modifier un devis
    $('#quotes-table').on('click', '.edit-quote', function() {
        var quoteId = $(this).data('id');
        window.location.href = base_url + 'admin/quotes/edit/' + quoteId;
    });

    // Imprimer un devis
    $('#quotes-table').on('click', '.print-quote', function() {
        var quoteId = $(this).data('id');
        
        // Ouvrir dans une nouvelle fenêtre
        var printWindow = window.open(base_url + 'admin/quotes/print/' + quoteId, '_blank');
        
        // Attendre que la page soit chargée
        printWindow.onload = function() {
            printWindow.print();
        };
    });

    // Valider un devis
    $('#quotes-table').on('click', '.validate-quote', function() {
        var quoteId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmer la validation',
            text: "Voulez-vous vraiment valider ce devis ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, valider',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url + 'admin/quotes/validate/' + quoteId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Devis validé avec succès'
                            });
                            refreshTable();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Erreur lors de la validation'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Erreur lors de la validation'
                        });
                    }
                });
            }
        });
    });

    // Rejeter un devis
    $('#quotes-table').on('click', '.reject-quote', function() {
        var quoteId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmer le rejet',
            text: "Voulez-vous vraiment rejeter ce devis ?",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Motif du rejet (optionnel)',
            inputAttributes: {
                'aria-label': 'Motif du rejet'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, rejeter',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url + 'admin/quotes/reject/' + quoteId,
                    type: 'POST',
                    data: {
                        reason: result.value
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Devis rejeté avec succès'
                            });
                            refreshTable();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Erreur lors du rejet'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Erreur lors du rejet'
                        });
                    }
                });
            }
        });
    });
}); 