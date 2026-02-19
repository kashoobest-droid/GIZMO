<?php
// Script to generate an invoice PDF for the latest Bankak order and base64-encode it.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

try {
    $order = Order::where('payment_method', 'bankak')->latest()->first();
    if (! $order) {
        echo "NO_BANKAK_ORDER_FOUND\n";
        exit(2);
    }

    $fresh = $order->fresh(['items.product', 'user']);

    if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
        echo "NO_DOMPDF_INSTALLED\n";
        exit(3);
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order-invoice', ['order' => $fresh])->output();
    $b64 = base64_encode($pdf);

    echo "ORDER_ID:" . $order->id . "\n";
    echo "PDF_BYTES:" . strlen($pdf) . "\n";
    echo "BASE64_BYTES:" . strlen($b64) . "\n";

    // write files for inspection
    $outPdf = __DIR__ . '/storage/app/test-invoice-' . $order->id . '.pdf';
    $outB64 = __DIR__ . '/storage/app/test-invoice-' . $order->id . '.b64';
    @mkdir(dirname($outPdf), 0755, true);
    file_put_contents($outPdf, $pdf);
    file_put_contents($outB64, $b64);

    echo "WROTE:" . $outPdf . " and " . $outB64 . "\n";
    exit(0);
} catch (Throwable $e) {
    echo "ERROR:" . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
