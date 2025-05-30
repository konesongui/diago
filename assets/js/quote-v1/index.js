"use strict";
// Class definition

// Set all the required variables for the following methods
var dtID = 'quoteDatatable',
    remoteAJAXFunctions = {
        loadData: 'admin/quoteitem/data',
        reject: 'admin/quoteitem/reject',
        validate: 'admin/quoteitem/validate',
        view: 'admin/quoteitem/view',
        edit: 'admin/quoteitem/edit',
        print: 'admin/quoteitem/print',
        sendEmail: 'admin/quoteitem/sendEmail',
    };

$(document).ready(function() {
    // Configuration de base pour SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // Initialisation de DataTables
    var quoteTable = $('.'+ dtID).DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": baseurl + remoteAJAXFunctions.loadData,
            "type": "POST"
        },
        "columns": [
            { "data": "quote_number" },
            { "data": "designation" },
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
                        <strong>TTC: ${data.ttc}</strong><br>
                        <small>HT: ${data.ht}</small>
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
                    let actions = `
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" 
                                id="dropdownMenu${row.id}" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu${row.id}">`;
                    
                    // Option Voir
                    actions += `
                            <li>
                                <a class="dropdown-item view-quote" href="#" data-id="${row.id}">
                                    <i class="fa fa-eye me-2"></i> Voir
                                </a>
                            </li>`;
                    
                    // Option Modifier (seulement si en attente)
                    if(parseInt(row.status.code) === 1) {
                        actions += `
                            <li>
                                <a class="dropdown-item edit-quote" href="#" data-id="${row.id}">
                                    <i class="fa fa-pencil me-2"></i> Modifier
                                </a>
                            </li>`;
                    }
                    
                    // Option Imprimer
                    actions += `
                            <li>
                                <a class="dropdown-item print-quote" href="#" data-id="${row.id}">
                                    <i class="fa fa-print me-2"></i> Imprimer
                                </a>
                            </li>`;
                    
                    // Option Envoyer par email
                    actions += `
                            <li>
                                <a class="dropdown-item send-quote" href="#" data-id="${row.id}">
                                    <i class="fa fa-envelope-o me-2"></i> Envoyer par email
                                </a>
                            </li>`;
                    
                    // Options Valider/Rejeter (uniquement pour les devis en attente)
                    if(parseInt(row.status.code) === 1) {
                        actions += `
                            <li>
                                <a class="dropdown-item validate-quote" href="#" data-id="${row.id}">
                                    <i class="fa fa-check me-2"></i> Valider
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item reject-quote" href="#" data-id="${row.id}">
                                    <i class="fa fa-times me-2"></i> Rejeter
                                </a>
                            </li>`;
                    }
                    
                    actions += `</ul></div>`;
                    return actions;
                }
            }
        ],
        "order": [[3, "desc"]],
        "language": {
            "url": baseurl + "assets/js/french.json"
        },
        "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "pageLength": 25
    });

    // Fonction pour rafraîchir la table
    function refreshTable() {
        quoteTable.ajax.reload(null, false);
    }


    // Voir un devis
    $('.' + dtID).on('click', '.view-quote', function() {
        var quoteId = $(this).data('id');
        window.location.href = base_url + '/'+ remoteAJAXFunctions.view  + '/'+ quoteId;
    });

    // Modifier un devis
    $('.' + dtID).on('click', '.edit-quote', function() {
        var quoteId = $(this).data('id');
        window.location.href = base_url  + '/'+ remoteAJAXFunctions.edit + '/'+ quoteId;
    });



    // Valider un devis
    $('.' + dtID).on('click', '.validate-quote', function() {
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
                    url: base_url + '/'+ remoteAJAXFunctions.validate + '/'+ quoteId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        if(response.status == "success") {
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
    $('.' + dtID).on('click', '.reject-quote', function() {
        var quoteId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmer le rejet',
            text: "Voulez-vous vraiment rejeter ce devis ?",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Motif du rejet',
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
                    url: base_url + '/'+ remoteAJAXFunctions.reject,
                    type: 'POST',
                    data: {
                        reason: result.value,
                        id: quoteId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status == "success") {
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

    
    // Imprimer un devis
    $('.' + dtID).on('click', '.print-quote', function() {
        var quoteId = $(this).data('id');
        // Ouvrir dans une nouvelle fenêtre
        var printWindow = window.open(base_url + 'admin/quoteitem/print/' + quoteId, '_blank');
        
        // Attendre que la page soit chargée
        printWindow.onload = function() {
            printWindow.print();
        };
    });

    
    // Confirmation avant envoi par email
    $('.' + dtID).on('click', '.send-quote', function(e) {
        e.preventDefault();
        var quoteId = $(this).data('id');
        
        Swal.fire({
            title: 'Envoi du devis par email',
            text: "Voulez-vous envoyer ce devis par email ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, envoyer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url + '/'+ remoteAJAXFunctions.sendEmail,
                    type: 'POST',
                    data: {
                        id: quoteId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status == "success") {
                            Toast.fire({
                                icon: 'success',
                                title: 'Devis envoyé avec succès'
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Erreur lors de l\'envoi'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Erreur lors de l\'envoi'
                        });
                    }
                });
            }
        });
    });



});