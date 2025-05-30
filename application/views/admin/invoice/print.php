<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture <?php echo $invoice['invoice_number']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .customer-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h2><?php echo $this->config->item('company_name'); ?></h2>
            <p>
                <?php echo $this->config->item('company_address'); ?><br>
                <?php echo $this->config->item('company_zip'); ?> <?php echo $this->config->item('company_city'); ?><br>
                Tél: <?php echo $this->config->item('company_phone'); ?><br>
                Email: <?php echo $this->config->item('company_email'); ?>
            </p>
        </div>
        <div class="invoice-info">
            <h1>FACTURE N° <?php echo $invoice['invoice_number']; ?></h1>
            <p>
                Date de facture: <?php echo date('d/m/Y', strtotime($invoice['invoice_date'])); ?><br>
                Date d'échéance: <?php echo date('d/m/Y', strtotime($invoice['due_date'])); ?>
            </p>
        </div>
        <div class="customer-info">
            <h3>Client</h3>
            <p>
                <?php echo $invoice['customer_name']; ?><br>
                <?php echo $invoice['customer_address']; ?><br>
                <?php echo $invoice['customer_zip']; ?> <?php echo $invoice['customer_city']; ?><br>
                <?php if (!empty($invoice['customer_phone'])) { ?>
                    Tél: <?php echo $invoice['customer_phone']; ?><br>
                <?php } ?>
                <?php if (!empty($invoice['customer_email'])) { ?>
                    Email: <?php echo $invoice['customer_email']; ?>
                <?php } ?>
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Article</th>
                <th>Quantité</th>
                <th>Unité</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice['items'] as $item) { ?>
                <tr>
                    <td><?php echo $item['category_name']; ?></td>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo number_format($item['quantity'], 2, ',', ' '); ?></td>
                    <td><?php echo $item['unit']; ?></td>
                    <td class="text-right"><?php echo number_format($item['unit_price'], 2, ',', ' '); ?> €</td>
                    <td class="text-right"><?php echo number_format($item['line_total'], 2, ',', ' '); ?> €</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <th>Total HT</th>
            <td class="text-right"><?php echo number_format($invoice['total_ht'], 2, ',', ' '); ?> €</td>
        </tr>
        <?php if ($invoice['apply_tva']) { ?>
            <tr>
                <th>TVA (<?php echo $invoice['tva_rate']; ?>%)</th>
                <td class="text-right"><?php echo number_format($invoice['tva_amount'], 2, ',', ' '); ?> €</td>
            </tr>
        <?php } ?>
        <tr>
            <th>Total TTC</th>
            <td class="text-right"><?php echo number_format($invoice['total_ttc'], 2, ',', ' '); ?> €</td>
        </tr>
        <tr>
            <th>Montant payé</th>
            <td class="text-right"><?php echo number_format($invoice['amount_paid'], 2, ',', ' '); ?> €</td>
        </tr>
        <tr>
            <th>Reste à payer</th>
            <td class="text-right"><?php echo number_format($invoice['remaining_amount'], 2, ',', ' '); ?> €</td>
        </tr>
    </table>

    <?php if (!empty($payments)) { ?>
        <h3>Paiements reçus</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Référence</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) { ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?></td>
                        <td class="text-right"><?php echo number_format($payment['amount'], 2, ',', ' '); ?> €</td>
                        <td>
                            <?php
                            switch($payment['method']) {
                                case 'cash':
                                    echo 'Espèces';
                                    break;
                                case 'check':
                                    echo 'Chèque';
                                    break;
                                case 'bank_transfer':
                                    echo 'Virement';
                                    break;
                                case 'card':
                                    echo 'Carte bancaire';
                                    break;
                            }
                            ?>
                        </td>
                        <td><?php echo $payment['reference']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <?php if (!empty($invoice['notes'])) { ?>
        <div class="footer">
            <h3>Notes</h3>
            <p><?php echo nl2br($invoice['notes']); ?></p>
        </div>
    <?php } ?>

    <div class="footer">
        <p class="text-center">
            <?php echo $this->config->item('company_name'); ?> - SIRET: <?php echo $this->config->item('company_siret'); ?><br>
            <?php echo $this->config->item('company_address'); ?> - <?php echo $this->config->item('company_zip'); ?> <?php echo $this->config->item('company_city'); ?>
        </p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Imprimer</button>
    </div>
</body>
</html> 