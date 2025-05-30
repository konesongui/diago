  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
      <h1><i class="fa fa-building-o"></i>Inventaire</h1>
  </section>
    
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
                  <form id="invoiceForm" method="post" action="<?php echo base_url(); ?>admin/invoice/add">
                      <div class="box-body">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="customer">Client <span class="text-danger">*</span></label>
                                      <select class="form-control select2" id="customer" name="customer" required>
                                          <option value="">Sélectionner un client...</option>
                                          <?php foreach ($clients as $client) { ?>
                                              <option value="<?php echo $client['id']; ?>"><?php echo $client['name']; ?></option>
                                          <?php } ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="invoice_date">Date de facture <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control datepicker" id="invoice_date" name="invoice_date" value="<?php echo date('d/m/Y'); ?>" required>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="due_date">Date d'échéance <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control datepicker" id="due_date" name="due_date" value="<?php echo date('d/m/Y', strtotime('+30 days')); ?>" required>
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
                                              <tr>
                                                  <td>
                                                      <select class="form-control item-category" name="item_category_id[]" required>
                                                          <option value="">Sélectionner...</option>
                                                          <?php foreach ($itemcatlist as $category) { ?>
                                                              <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                                          <?php } ?>
                                                      </select>
                                                  </td>
                                                  <td>
                                                      <select class="form-control item-select" name="item_id[]" required>
                                                          <option value="">Sélectionner...</option>
                                                      </select>
                                                  </td>
                                                  <td>
                                                      <input type="number" class="form-control item-quantity" name="quantity[]" min="0.01" step="0.01" required>
                                                  </td>
                                                  <td>
                                                      <input type="text" class="form-control item-unit" name="unit[]">
                                                  </td>
                                                  <td>
                                                      <input type="number" class="form-control item-price" name="price[]" min="0.01" step="0.01" required>
                                                  </td>
                                                  <td>
                                                      <input type="text" class="form-control item-total" readonly>
                                                  </td>
                                                  <td>
                                                      <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash"></i></button>
                                                  </td>
                                              </tr>
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
                                          <option value="cash">Espèces</option>
                                          <option value="check">Chèque</option>
                                          <option value="bank_transfer">Virement</option>
                                          <option value="card">Carte bancaire</option>
                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="notes">Notes</label>
                                      <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <div class="checkbox">
                                          <label>
                                              <input type="checkbox" id="apply_tva" name="apply_tva" value="1" checked> Appliquer la TVA
                                          </label>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="tva_rate">Taux de TVA (%)</label>
                                      <input type="number" class="form-control" id="tva_rate" name="tva_rate" value="20" min="0" max="100" step="0.1">
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label>Total HT</label>
                                              <input type="text" class="form-control" id="total_ht" readonly>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label>TVA</label>
                                              <input type="text" class="form-control" id="tva_amount" readonly>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label>Total TTC</label>
                                      <input type="text" class="form-control" id="total_ttc" readonly>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="box-footer">
                          <button type="submit" class="btn btn-primary">Enregistrer</button>
                          <a href="<?php echo base_url(); ?>admin/invoice" class="btn btn-default">Annuler</a>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </section>
</div>

<script src="<?= base_url('assets/js/invoice/actions.js') ?>"></script>