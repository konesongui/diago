"use strict";
// Class definition

// Set all the required variables for the following methods
var dtID = 'invoiceDatatable',
    modalID = 'addPaymentModal',
    modalContentID = 'addPaymentContent',
    formID = 'paymentForm',
    submitID = 'paymentSubmit',
    remoteAJAXFunctions = {
        loadData: 'admin/invoiceitem/data',
        view: 'admin/invoiceitem/view',
        edit: 'admin/invoiceitem/edit',
        print: 'admin/invoiceitem/print',
        sendEmail: 'admin/invoiceitem/sendEmail',
        addPayment: 'admin/invoiceitem/addPaymentForm',
        setPayment: 'admin/invoiceitem/setPayment',
        cancel: 'admin/invoiceitem/cancel'
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
    var invoiceTable = $('.'+ dtID).DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": baseurl + remoteAJAXFunctions.loadData,
            "type": "POST"
        },
        "columns": [
            { "data": "invoice_number" },
            { 
                data: "customer",
                render: function(data) {
                    // console.log(data);
                    return `<div>
                        <strong>${data.name}</strong><br>
                        <small>${data.email}<br>${data.phone}</small>
                    </div>`;
                }
            },
            { "data": "dates.invoice" },
            { "data": "dates.due" },
            { 
                "data": "amount.ht",
                "render": function(data) {
                    return data;
                }
            },
            { 
                "data": "amount.ttc",
                "render": function(data) {
                    return data;
                }
            },
            { 
                "data": "amount.remaining",
                "render": function(data) {
                    return data;
                }
            },
            { 
                "data": "amount.paid",
                "render": function(data) {
                    return data;
                }
            },
            { 
                "data": "amount.remaining",
                "render": function(data) {
                    return data;
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
                    // console.log(data);
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
                                <a class="dropdown-item" href="${baseurl}admin/invoiceitem/view/${row.id}">
                                    <i class="fa fa-eye me-2"></i> Voir
                                </a>
                            </li>`;
                    
                    
                    
                    // Option Payer (si non payée et montant restant > 0)
                    if (parseInt(row.status.code) == 1 || parseInt(row.status.code) == 3 && parseFloat(row.amount.remaining) > 0) {
                        actions += `
                            <li>
                                <a data-toggle="modal" data-target="#${modalID}" data-row-id = "${row.id}" data-toggle="tooltip" data-placement="left" title="Ajouter un paiement" type="button" data-remaining="${row.amount.remaining}" class="btn btn-sm text-dark btn-icon add-payment"><i class="fa fa-money me-2"></i> Ajouter un paiement</a>  
                                    
                                </a>
                            </li>`;
                    }
                    
                    // Option Annuler (si non payée)
                    if (parseInt(row.status.code) == 1) {
                        actions += `
                            <li>
                                <a class="dropdown-item" href="${baseurl}admin/invoiceitem/edit/${row.id}">
                                    <i class="fa fa-edit me-2"></i> Modifier
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item cancel-invoice" href="#" data-id="${row.id}">
                                    <i class="fa fa-ban me-2"></i> Annuler
                                </a>
                            </li>`;
                    }
                    // Option Imprimer
                    actions += `
                            <li>
                                <a class="dropdown-item" href="${baseurl}admin/invoiceitem/print/${row.id}" target="_blank">
                                    <i class="fa fa-print me-2"></i> Imprimer
                                </a>
                            </li>`;
                    
                    
                    actions += `
                        </ul>
                    </div>`;
                    
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
        invoiceTable.ajax.reload(null, false);
    }


    // Voir une facture
    $('.' + dtID).on('click', '.view-invoice', function() {
        var invoiceId = $(this).data('id');
        window.location.href = base_url + '/'+ remoteAJAXFunctions.view  + '/'+ invoiceId;
    });

    // Modifier une facture
    $('.' + dtID).on('click', '.edit-invoice', function() {
        var invoiceId = $(this).data('id');
        window.location.href = base_url + '/'+ remoteAJAXFunctions.edit + '/'+ invoiceId;
    });

    
    // Imprimer une facture
    $('.' + dtID).on('click', '.print-invoice', function() {
        var invoiceId = $(this).data('id');
        // Ouvrir dans une nouvelle fenêtre
        var printWindow = window.open(base_url + 'admin/invoiceitem/print/' + invoiceId, '_blank');
        
        // Attendre que la page soit chargée
        printWindow.onload = function() {
            printWindow.print();
        };
    });

    
    // Confirmation avant envoi par email
    $('.' + dtID).on('click', '.send-invoice', function(e) {
        e.preventDefault();
        var invoiceId = $(this).data('id');
        
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

    // Annulation de livraison
    $('.' + dtID).on('click', '.cancel-invoice', function() {
        var invoiceId = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmer l\'annulation',
            text: "Voulez-vous vraiment annuler cette facture ?",
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
                    url: base_url + '/' + remoteAJAXFunctions.cancel,
                    type: 'POST',
                    data: {
                        reason: result.value,
                        id: invoiceId
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if(response.status == "success") {
                            Toast.fire({
                                icon: 'success',
                                title: 'Facture annulée avec succès'
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

    
    // Function to load on (edit or add) button click
    $('.' + dtID).on('click', `.add-payment`, function(e) {

        // Desable default event
        e.preventDefault();

        // Get the selected row id
        var rowID = $(this).attr('data-row-id');
        var remaining = $(this).attr('data-remaining');

        // console.log(base_url);

        // AJAX function to load the form data to display
        $.ajax({
            // AJAX Call options
            url: base_url + '/' + remoteAJAXFunctions.addPayment,
            type: "POST",
            data: {
                'rowID': rowID,
                'remaining': remaining
            },
            // On 'Success' Event
            success: function(data) {

                // Process only if any data has been loaded
                if(data) {
                    // Display the loaded data
                    $(`#${modalID} #${modalContentID}`).html(data);

                    // Initialisation du datepicker
                    $('.date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                } // End if

            }, // End success event

        });

    });

    // Soumission du formulaire avec confirmation
    $(document).on('click', `#${submitID}`, function(e) {
        e.preventDefault();
        
        // Validation du formulaire
        const form = $(`#${formID}`)[0];
        if (!form.checkValidity()) {
            $(form).addClass('was-validated');
            return;
        }

        // Récupération des données
        const formData = {
            invoice_id: $('#payment_invoice_id').val(),
            amount: $('#amount').val(),
            payment_date: $('#payment_date').val(),
            method: $('#method').val(),
            reference: $('#reference').val(),
            notes: $('#notes').val()
        };

        // Configuration SweetAlert
        Swal.fire({
            title: 'Confirmer le paiement',
            html: `
                <p>Voulez-vous enregistrer ce paiement ?</p>
                <div class="text-left">
                    <strong>Montant:</strong> ${formData.amount} €<br>
                    <strong>Date:</strong> ${formData.payment_date}<br>
                    <strong>Méthode:</strong> ${formData.method}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, envoyer',
            cancelButtonText: 'Annuler',
            customClass: {
                container: 'sweetalert-container'
            },
            didOpen: () => {
                $('.sweetalert-container').css('z-index', '999999');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                
                $.ajax({
                    url: `${base_url}/${remoteAJAXFunctions.setPayment}`,
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                .done(function(response) {
                    if(response.status === "success") {
                        Swal.fire({
                            title: 'Succès',
                            text: 'Paiement enregistré avec succès',
                            icon: 'success',
                            customClass: {
                                container: 'sweetalert-container'
                            }
                        }).then(() => {
                            // Fermeture propre du modal
                            // $(`#addPaymentModal`).modal("hide");
                            // const modal = $(`#${modalID}`);
                            location.reload();
                            // modal.modal('hide');
                            
                            // Rafraîchissement des données
                            // if (typeof refreshTable === 'function') {
                            //     refreshTable();
                            // }
                            
                            // Réinitialisation du formulaire
                            // form.reset();
                            // $(form).removeClass('was-validated');
                        });
                    } else {
                        Swal.fire({
                            title: 'Erreur',
                            text: response.message || 'Erreur lors du traitement',
                            icon: 'error'
                        });
                    }
                })
                .fail(function(xhr) {
                    Swal.fire({
                        title: 'Erreur',
                        text: xhr.responseJSON?.message || 'Erreur serveur',
                        icon: 'error'
                    });
                })
                .always(() => Swal.hideLoading());
            }
        });
    });

    // Gestion de la validation Bootstrap
    $(`#${formID} input, #${formID} select`).on('change keyup', function() {
        $(`#${formID}`).removeClass('was-validated');
    });

    // CSS à ajouter pour garantir l'affichage correct
    const style = document.createElement('style');
    style.textContent = `
        .sweetalert-container {
            z-index: 999999 !important;
        }
        .sweetalert-popup {
            z-index: 999999 !important;
        }
    `;
    document.head.appendChild(style);


});