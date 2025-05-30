"use strict";
// Class definition

// Set all the required variables for the following methods
var dtID = 'quoteDatatable',
    remoteAJAXFunctions = {
        loadData: 'admin/quoteitem/data',
    };

$(document).ready(function() {
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
            { "data": "customer_name",
            
                "render": function(data, type, row) {
                    
                    return '<span class="label label-info">' + row.customer.name + '</span>';
                }
            },
            { "data": "issue_date" },
            { 
                "data": "total_ttc",
                "className": "text-right"
            },
            { 
                "data": "status",
                "render": function(data, type, row) {
                    var status_class = 'default';
                    var status_text = 'Brouillon';
                    
                    switch(parseInt(data)) {
                        case 1:
                            status_class = 'info';
                            status_text = 'Envoyé';
                            break;
                        case 2:
                            status_class = 'success';
                            status_text = 'Accepté';
                            break;
                        case 3:
                            status_class = 'danger';
                            status_text = 'Refusé';
                            break;
                    }
                    
                    return '<span class="label label-' + status_class + '">' + status_text + '</span>';
                }
            },
            { 
                "data": null,
                "orderable": false,
                "searchable": false,
                "className": "text-right",
                "render": function(data, type, row) {
                    var actions = '<div class="btn-group">';
                    
                    // Bouton voir
                    actions += '<a href="' + baseurl + 'admin/quoteitem/view/' + row.id + '" ' +
                        'class="btn btn-default btn-xs" title="Voir">' +
                        '<i class="fa fa-eye"></i>' +
                        '</a>';
                        
                    // Bouton PDF
                    actions += '<a href="' + baseurl + 'admin/quoteitem/generatePdf/' + row.id + '" ' +
                        'class="btn btn-info btn-xs" title="Télécharger PDF" target="_blank">' +
                        '<i class="fa fa-file-pdf-o"></i>' +
                        '</a>';
                        
                    // Bouton email (seulement si pas encore envoyé)
                    if (parseInt(row.status) === 0) {
                        actions += '<a href="' + baseurl + 'admin/quoteitem/sendEmail/' + row.id + '" ' +
                            'class="btn btn-success btn-xs send-quote" title="Envoyer par email">' +
                            '<i class="fa fa-envelope-o"></i>' +
                            '</a>';
                    }
                    
                    actions += '</div>';
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

    // Confirmation avant envoi par email
    $('.' + dtID).on('click', '.send-quote', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        if (confirm('Voulez-vous vraiment envoyer ce devis par email ?')) {
            window.location.href = url;
        }
    });
});