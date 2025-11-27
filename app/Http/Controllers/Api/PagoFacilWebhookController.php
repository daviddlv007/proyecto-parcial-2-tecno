<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Services\PagoFacilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagoFacilWebhookController extends Controller
{
    protected $pagoFacilService;

    public function __construct(PagoFacilService $pagoFacilService)
    {
        $this->pagoFacilService = $pagoFacilService;
    }

    /**
     * Recibe callbacks de PagoFácil cuando cambia el estado de una transacción
     */
    public function handleCallback(Request $request)
    {
        try {
            Log::info('Callback PagoFácil recibido', [
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            // Verificar y procesar el callback
            $result = $this->pagoFacilService->verifyCallback($request->all());

            if (!$result['success']) {
                Log::warning('Callback PagoFácil no válido', [
                    'message' => $result['message'],
                    'data' => $request->all(),
                ]);

                return $this->pagoFacilService->getCallbackResponse();
            }

            // Buscar el pago por transaction_id
            $pago = Pago::where('transaction_id', $result['transactionId'])->first();

            if (!$pago) {
                Log::error('Pago no encontrado para transaction_id', [
                    'transaction_id' => $result['transactionId'],
                ]);

                return $this->pagoFacilService->getCallbackResponse();
            }

            // Actualizar estado del pago según el resultado
            $estadoAnterior = $pago->estado_pago;
            $pago->estado_pago = $result['status'];
            $pago->save();

            Log::info('Estado de pago actualizado', [
                'pago_id' => $pago->id,
                'transaction_id' => $result['transactionId'],
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $result['status'],
                'mensaje' => $result['message'],
            ]);

            // Responder a PagoFácil con el formato esperado
            return $this->pagoFacilService->getCallbackResponse();

        } catch (\Exception $e) {
            Log::error('Error procesando callback PagoFácil', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            // Siempre responder con formato correcto a PagoFácil
            return $this->pagoFacilService->getCallbackResponse();
        }
    }
}
