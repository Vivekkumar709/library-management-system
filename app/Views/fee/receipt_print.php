<!DOCTYPE html>
<html><head><title>Fee Receipt</title><style>@page {size: A4; margin:15mm}</style></head>
<body style="font-family:Arial">
    <div style="border:3px solid black; padding:30px; text-align:center">
        <h2>OFFICIAL FEE RECEIPT</h2>
        <p>Receipt No: <?= $payment['receipt_no'] ?> | Date: <?= date('d-m-Y') ?></p>
        <hr>
        <h3>Student: <?= esc($payment['first_name'].' '.$payment['last_name']) ?></h3>
        <p>Amount Paid: ₹<?= number_format($payment['amount'],2) ?> (<?= $payment['payment_mode'] ?>)</p>
        <button onclick="window.print()">🖨️ Print Receipt</button>
    </div>
</body></html>