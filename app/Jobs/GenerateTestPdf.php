<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateTestPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function handle(): void
    {
        try {
            $filename = 'test-queued-pdf-' . time() . '.pdf';
            $path = storage_path('app/public/' . $filename);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($this->html);
            $pdf->save($path);

            Log::channel('payments')->info('GenerateTestPdf job saved PDF', ['path' => $path]);
        } catch (\Throwable $e) {
            Log::channel('payments')->error('GenerateTestPdf failed', ['error' => $e->getMessage()]);
            report($e);
        }
    }
}
