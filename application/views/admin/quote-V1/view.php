<?php
$currency_symbol = ''; // À adapter selon vos besoins
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo $title; ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $page_title; ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('admin/quoteitem/generatePdf/'.$quote['id']); ?>" class="btn btn-primary btn-sm" target="_blank">
                                <i class="fa fa-file-pdf-o"></i> Télécharger PDF
                            </a>
                            <a href="<?php echo base_url('admin/quoteitem/sendEmail/'.$quote['id']); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-envelope"></i> Envoyer par email
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- Informations générales -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Informations du devis</h4>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th>Référence</th>
                                        <td><?php echo $quote['quote_number']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date d'émission</th>
                                        <td><?php echo date('d/m/Y', strtotime($quote['issue_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Désignation</th>
                                        <td><?php echo $quote['designation']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total HT</th>
                                        <td><?php echo number_format($quote['total_ht'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                    </tr>
                                    <?php if ($quote['apply_tva']) : ?>
                                    <tr>
                                        <th>TVA (<?php echo $quote['tva_rate']; ?>%)</th>
                                        <td><?php echo number_format($quote['tva_amount'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total TTC</th>
                                        <td><?php echo number_format($quote['total_ttc'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Informations client</h4>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th>Client</th>
                                        <td><?php echo $quote['customer_name'] . ' ' . $quote['customer_last_name']; ?></td>
                                    </tr>
                                    <?php if (!empty($quote['customer_email'])) : ?>
                                    <tr>
                                        <th>Email</th>
                                        <td><?php echo $quote['customer_email']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>

                        <!-- Articles du devis -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Articles</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Catégorie</th>
                                                <th>Article</th>
                                                <th>Quantité</th>
                                                <th>Unité</th>
                                                <th>Prix unitaire</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($quote['items'] as $item) : ?>
                                            <tr>
                                                <td><?php echo $item['category_name']; ?></td>
                                                <td><?php echo $item['item_name']; ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td><?php echo $item['unit']; ?></td>
                                                <td><?php echo number_format($item['price'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                                <td><?php echo number_format($item['line_total'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right">Total HT</th>
                                                <td><?php echo number_format($quote['total_ht'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                            </tr>
                                            <?php if ($quote['apply_tva']) : ?>
                                            <tr>
                                                <th colspan="5" class="text-right">TVA (<?php echo $quote['tva_rate']; ?>%)</th>
                                                <td><?php echo number_format($quote['tva_amount'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-right">Total TTC</th>
                                                <td><?php echo number_format($quote['total_ttc'], 2, ',', ' ') . ' ' . $currency_symbol; ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="<?php echo base_url('admin/quoteitem'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.mt-4 {
    margin-top: 20px;
}
.table th {
    background-color: #f4f4f4;
}
.box-tools .btn {
    margin-left: 5px;
}
</style> 