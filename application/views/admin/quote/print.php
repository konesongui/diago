<?php
    // var_dump($quote);
    // exit;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; }
        .logo { width: 160px; }
        .company-info, .client-info { font-size: 11px; }
        .vat-invoice { background: #666; color: #fff; padding: 10px 20px; text-align: right; }
        .invoice-details { margin-top: 10px; }
        .qr { width: 80px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #bbb; padding: 6px 4px; }
        .table th { background: #f4f4f4; }
        .table .total-row th, .table .total-row td { background: #eee; font-weight: bold; }
        .table .grand-total th, .table .grand-total td { background: #e74c3c; color: #fff; font-size: 1.1em; }
        .payment-section { margin-top: 30px; }
        .payment-section table { width: 100%; border: 1px solid #bbb; }
        .payment-section th, .payment-section td { border: 1px solid #bbb; padding: 6px; }
        .payment-section th { background: #e74c3c; color: #fff; }
        .terms { margin-top: 20px; font-size: 10px; }
        .fxrate { text-align: right; font-size: 11px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="LOGO_URL" class="logo" alt="Logo">
        <div class="company-info">
            <strong>MC3 Cloud Ltd</strong><br>
            Office 1, Lot Level 2,<br>
            Ebene Commercial Centre, Ebene, Mauritius 722 3000<br>
            Tel: +230 2691030<br>
            Email: contact@mc3.cloud<br>
            BRN: C20177766<br>
            VAT No: Vat276635
        </div>
        <div>
            <div class="vat-invoice">
                <strong>VAT Invoice</strong><br>
                Invoice Code: 068066<br>
                Invoice #: EMA23-068066<br>
                Date: 07/04/2025<br>
                Due date: 21/04/2025
            </div>
            <div class="invoice-details">
                <div class="client-info">
                    <strong>COULIBALY MOUSSA & ENFANTS EXPERTISES</strong><br>
                    ADJINAN COCODY-ANGRE RIVIERA NOIRE,<br>
                    NON LOIN DE L'AVENUE DES ALLIES, LOT 208 ABIDJAN<br>
                    Tel: 07 57 19 27 17<br>
                    NIF: 261 481 634 813 03516<br>
                    VAT No.: 240134F
                </div>
                <img src="QR_CODE_URL" class="qr" alt="QR Code">
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Désignation</th>
                <th>Unité</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Remise</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- Boucle sur les articles -->
            <?php foreach ($quote['items'] as $item): ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['category'] ?></td>
                <td><?= $item['unit'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 2, ',', ' ') ?> €</td>
                <td><?= $item['discount'] ?>%</td>
                <td><?= number_format($item['total'], 2, ',', ' ') ?> €</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="7" style="text-align:right;">Total avant taxe</th>
                <td><?= number_format($quote['totals']['ht'], 2, ',', ' ') ?> €</td>
            </tr>
            <tr>
                <th colspan="7" style="text-align:right;">TVA</th>
                <td><?= number_format($quote['totals']['tva'], 2, ',', ' ') ?> €</td>
            </tr>
            <tr class="grand-total">
                <th colspan="7" style="text-align:right;">TOTAL</th>
                <td><?= number_format($quote['totals']['ttc'], 2, ',', ' ') ?> €</td>
            </tr>
        </tfoot>
    </table>

    <div class="fxrate">
        FX Rate MUR/EUR: <?= $quote['fxrate'] ?? '48.97' ?>
    </div>

    <div class="payment-section">
        <table>
            <tr>
                <th>Payments</th>
                <th>Banking details</th>
            </tr>
            <tr>
                <td>Net: 45 MCC EUR</td>
                <td>
                    Beneficiary Account Name: MC3 CLOUD LTD<br>
                    Account No: 0002011071<br>
                    Beneficiary Bank: CREDIT INDUSTRIEL ET COMMERCIAL<br>
                    IBAN: FR76 3006 0860 9600 0201 1070 127<br>
                    SWIFT: CMCIFRPP
                </td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <img src="QR_CODE_URL" class="qr" style="float:left; margin-right:10px;" alt="QR Code">
        All cheques should be made payable to 'MC3 CLOUD LTD' and crossed 'A/C Payee only'. Please write your company name and invoice reference at the back of your cheque. For credit sales, interest of 1.5% above bank rate per annum will be charged after due date. Furthermore, in the case of recovery of the above sum through a solicitor, the commission due to the solicitor will be payable by the client.<br>
        This sale is governed by the general terms and conditions in force on the day of the order. The customer is deemed to have read them and to accept them expressly.<br>
        Latest Terms and Conditions: <a href=\"https://aka.mc3.fr/terms/mc3cloud/\">https://aka.mc3.fr/terms/mc3cloud/</a> or by scanning the QR Code.
    </div>
</body>
</html>