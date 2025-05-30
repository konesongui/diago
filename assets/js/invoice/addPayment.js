"use strict";
// Class definition

// Set all the required variables for the following methods
var modalID = 'addPaymentModal',
    modalContentID = 'addPaymentModalContent',
    formID = 'paymentForm',
    submitID = 'paymentSubmit',
    remoteAJAXFunctions = {
        addPayment: 'admin/invoiceitem/addPaymentForm',
        setPayment: 'admin/invoiceitem/setPayment'
    };

$(document).ready(function() {
  // Initialisation du datepicker
  $('.datepicker').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayHighlight: true,
      language: 'fr'
  });

  // Function to load on (edit or add) button click
  $(document).on('click', `.add-payment`, function(e) {

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
            } // End if

        }, // End success event

    });

  });



  // Soumission du formulaire avec confirmation
  $(document).on('click', `#${submitID}`, function(e) {
    e.preventDefault();

    console.log('test');
    
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
                <strong>Montant:</strong> ${formData.amount}<br>
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
                        location.reload();
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