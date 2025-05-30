"use strict";
// Class definition

// Set all the required variables for the following methods
var dtID = 'deliveryDatatable',
    remoteAJAXFunctions = {
        loadData: 'admin/deliveryitem/data',
        reject: 'admin/deliveryitem/reject',
        validate: 'admin/deliveryitem/validate',
        view: 'admin/deliveryitem/view',
        edit: 'admin/deliveryitem/edit',
        print: 'admin/deliveryitem/print',
        sendEmail: 'admin/deliveryitem/sendEmail',
        partialDelivery: 'admin/deliveryitem/partialDelivery',
        completeDelivery: 'admin/deliveryitem/completeDelivery',
        cancelDelivery: 'admin/deliveryitem/cancelDelivery'
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
    var deliveryTable = $('.'+ dtID).DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": baseurl + remoteAJAXFunctions.loadData,
            "type": "POST"
        },
        "columns": [
            { "data": "delivery_number" },
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
                data: "dates.delivery_date",
                render: function(data, type, row) {
                    return `<div>
                        <span>Émis le: ${row.dates.delivery_date}</span><br>
                        <small>Valide jusqu'au: ${row.dates.deadline}</small>
                    </div>`;
                }
            },
            { "data": "payment_terms",
                render: function(data) {
                    return `<div>
                        <strong>${data}</strong><br>
                    </div>`;
                } 
            },
            { "data": "delivery_location",
                render: function(data) {
                    return `<div>
                        <strong>${data}</strong><br>
                    </div>`;
                }
            },
            { "data": "dates.creation",
                render: function(data) {
                    return `<div>
                        <strong>${data}</strong><br>
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
                deliveryable: false,
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
                                <a class="dropdown-item view-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-eye me-2"></i> Voir
                                </a>
                            </li>`;
                    
                    // Option Imprimer
                    actions += `
                            <li>
                                <a class="dropdown-item print-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-print me-2"></i> Imprimer
                                </a>
                            </li>`;
                    
                    // Option Envoyer par email
                    actions += `
                            <li>
                                <a class="dropdown-item send-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-envelope-o me-2"></i> Envoyer par email
                                </a>
                            </li>`;
                    
                    // Options Valider/Rejeter (uniquement pour les devis en attente)
                    if(parseInt(row.status.code) === 1) {
                        actions += `
                            <li>
                                <a class="dropdown-item partial-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-truck me-2"></i> Livraison partielle
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item complete-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-check-circle me-2"></i> Livraison complète
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item cancel-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-ban me-2"></i> Annuler livraison
                                </a>
                            </li>`;
                    }

                     // Options Valider/Rejeter (uniquement pour les devis en attente)
                     if(parseInt(row.status.code) === 6) {
                        actions += `
                            <li>
                                <a class="dropdown-item partial-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-truck me-2"></i> Livraison partielle
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item complete-delivery" href="#" data-id="${row.id}">
                                    <i class="fa fa-check-circle me-2"></i> Livraison complète
                                </a>
                            </li>`;
                    }

                    actions += `</ul></div>`;
                    return actions;
                }
            }
        ],
        "delivery": [[3, "desc"]],
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
        deliveryTable.ajax.reload(null, false);
    }


    // Voir un devis
    $('.' + dtID).on('click', '.view-delivery', function() {
        var deliveryId = $(this).data('id');
        window.location.href = base_url + '/'+ remoteAJAXFunctions.view  + '/'+ deliveryId;
    });

    // Modifier un devis
    $('.' + dtID).on('click', '.partial-delivery', function() {
        var deliveryId = $(this).data('id');
        window.location.href = base_url + '/'+ remoteAJAXFunctions.partialDelivery + '/'+ deliveryId;
    });

    
    // Imprimer un devis
    $('.' + dtID).on('click', '.print-delivery', function() {
        var deliveryId = $(this).data('id');
        // Ouvrir dans une nouvelle fenêtre
        var printWindow = window.open(base_url + 'admin/deliveryformitem/print/' + deliveryId, '_blank');
        
        // Attendre que la page soit chargée
        printWindow.onload = function() {
            printWindow.print();
        };
    });

    
    // Confirmation avant envoi par email
    $('.' + dtID).on('click', '.send-delivery', function(e) {
        e.preventDefault();
        var deliveryId = $(this).data('id');
        
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
                        id: deliveryId
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

    // Livraison complète
    $('.' + dtID).on('click', '.complete-delivery', function() {
        var deliveryId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmer la livraison complète',
            text: "Voulez-vous marquer cette commande comme totalement livrée ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Traitement en cours...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: base_url + '/' + remoteAJAXFunctions.completeDelivery,
                    type: 'POST',
                    data: {
                        id: deliveryId
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if(response.status == "success") {
                            Toast.fire({
                                icon: 'success',
                                title: 'Livraison complète enregistrée'
                            });
                            refreshTable();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Erreur lors de l\'opération'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Toast.fire({
                            icon: 'error',
                            title: 'Erreur: ' + (xhr.responseJSON?.message || 'Erreur serveur')
                        });
                    }
                });
            }
        });
    });

    // Annulation de livraison
    $('.' + dtID).on('click', '.cancel-delivery', function() {
        var deliveryId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmer l\'annulation',
            text: "Voulez-vous vraiment annuler cette livraison ?",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Motif de l\'annulation',
            inputAttributes: {
                'aria-label': 'Motif de l\'annulation'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                if (!result.value.trim()) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Veuillez saisir un motif d\'annulation'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Annulation en cours...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: base_url + '/' + remoteAJAXFunctions.cancelDelivery,
                    type: 'POST',
                    data: {
                        reason: result.value,
                        id: deliveryId
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if(response.status == "success") {
                            Toast.fire({
                                icon: 'success',
                                title: 'Livraison annulée avec succès'
                            });
                            refreshTable();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Erreur lors de l\'annulation'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Toast.fire({
                            icon: 'error',
                            title: 'Erreur: ' + (xhr.responseJSON?.message || 'Erreur serveur')
                        });
                    }
                });
            }
        });
    });


});