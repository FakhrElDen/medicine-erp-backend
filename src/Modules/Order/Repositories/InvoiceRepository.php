<?php

namespace Modules\Order\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Modules\Order\Entities\Invoice;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceRepository extends BaseRepository
{
    public function __construct(protected Invoice $model)
    {
    }

    public function store($input, $packaging)
    {
        $invoice = $this->model->create([
            'order_id' => $input['order_id'],
            'printed_by' => auth()->id(),
            'printed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'printed_num' => 1,
            'bags_num' => isset($packaging['bags_num']) ? intval($packaging['bags_num']) : null,
            'fridges_num' => isset($packaging['fridges_num']) ? intval($packaging['fridges_num']) : null,
            'cartons_num' => isset($packaging['cartons_num']) ? intval($packaging['cartons_num']) : null,
            'invoices_num' => isset($packaging['invoices_num']) ? intval($packaging['invoices_num']) : null,
        ]);

        $qr_code = $this->generateQrCode($invoice->id);
        $invoice->update(['qr_code' => $qr_code]);

        return $invoice;
    }

    public function generateQrCode($invoice_id)
    {
        $path = storage_path("app/public/invoice_qr_code_{$invoice_id}.png");
        QrCode::format('png')->generate($invoice_id, $path);

        return $path;
    }
}
