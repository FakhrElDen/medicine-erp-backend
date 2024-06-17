<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;
use Modules\Order\Http\Requests\GenerateQrCodeRequest;
use Modules\Order\Repositories\InvoiceRepository;

class InvoiceController extends BaseController
{
    public function __construct(protected InvoiceRepository $invoiceRepository)
    {
    }

    public function generateQrCode(GenerateQrCodeRequest $request)
    {
        $qrCode = $this->invoiceRepository->generateQrCode($request->invoice_id);

        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
        ]);
    }
}
