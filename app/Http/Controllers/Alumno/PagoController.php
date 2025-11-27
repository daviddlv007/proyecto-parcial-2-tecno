<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Inscripcion;
use App\Models\MetodoPago;
use App\Services\PagoFacilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PagoController extends Controller
{
    protected $pagoFacilService;

    public function __construct(PagoFacilService $pagoFacilService)
    {
        $this->pagoFacilService = $pagoFacilService;
    }

    public function planes()
    {
        $alumno = Auth::user();
        
        $inscripciones = Inscripcion::where('alumno_id', $alumno->id)
            ->where('estado_inscripcion', 'activa')
            ->with(['curso', 'planPago', 'pagos'])
            ->get()
            ->filter(fn($i) => $i->getSaldoPendiente() > 0);
        
        $planesPago = $inscripciones->map(function($inscripcion) {
            $plan = $inscripcion->planPago;
            $saldoPendiente = $inscripcion->getSaldoPendiente();
            $montoCuota = $inscripcion->calcularMontoCuota();
            $pagosPendientes = max(0, ceil($saldoPendiente / $montoCuota));
            
            // Calcular próximo vencimiento
            $ultimoPago = $inscripcion->pagos()->orderBy('fecha', 'desc')->first();
            $fechaBase = $ultimoPago ? $ultimoPago->fecha : $inscripcion->fecha_inicio;
            $diasIntervalo = $plan->dias_intervalo ?? 30;
            $proximoVencimiento = date('Y-m-d', strtotime($fechaBase . " +{$diasIntervalo} days"));
            
            $diasParaVencimiento = (strtotime($proximoVencimiento) - time()) / 86400;
            $estadoPago = $diasParaVencimiento < 0 ? 'moroso' : ($diasParaVencimiento <= 7 ? 'proximo' : 'vigente');
            
            return [
                'inscripcion_id' => $inscripcion->id,
                'curso' => $inscripcion->curso->nombre,
                'plan_pago' => $plan->nombre ?? 'N/A',
                'monto_total' => $inscripcion->monto_total,
                'saldo_pendiente' => $saldoPendiente,
                'monto_cuota' => $montoCuota,
                'cuotas_pendientes' => $pagosPendientes,
                'proximo_vencimiento' => $proximoVencimiento,
                'estado' => $estadoPago,
                'dias_para_vencimiento' => round($diasParaVencimiento),
            ];
        })->values();
        
        return Inertia::render('Alumno/PlanesPago/Index', compact('planesPago'));
    }
    
    public function index()
    {
        $alumno = Auth::user();
        
        $pagos = Pago::where('alumno_id', $alumno->id)
            ->with(['metodoPago', 'inscripcion.curso'])
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'fecha' => $p->fecha,
                'monto' => $p->monto,
                'metodo' => optional($p->metodoPago)->nombre ?? 'N/A',
                'curso' => optional($p->inscripcion)->curso->nombre ?? 'N/A',
            ]);
        
        $inscripcionesPendientes = Inscripcion::where('alumno_id', $alumno->id)
            ->where('estado_inscripcion', 'activa')
            ->with(['curso', 'planPago'])
            ->get()
            ->filter(fn($i) => $i->getSaldoPendiente() > 0)
            ->map(fn($i) => [
                'inscripcion_id' => $i->id,
                'curso' => $i->curso->nombre,
                'monto_total' => $i->monto_total,
                'saldo_pendiente' => $i->getSaldoPendiente(),
                'monto_cuota' => $i->calcularMontoCuota(),
            ]);
        
        return Inertia::render('Alumno/Pagos/Index', compact('pagos', 'inscripcionesPendientes'));
    }
    
    public function create(Request $request)
    {
        $inscripcionId = $request->query('inscripcion_id');
        
        $inscripcion = Inscripcion::where('alumno_id', Auth::id())
            ->where('id', $inscripcionId)
            ->with(['curso', 'planPago'])
            ->firstOrFail();
        
        $metodosPago = MetodoPago::all();
        
        $pagoInfo = [
            'inscripcion_id' => $inscripcion->id,
            'curso' => $inscripcion->curso->nombre,
            'saldo_pendiente' => $inscripcion->getSaldoPendiente(),
            'monto_cuota' => $inscripcion->calcularMontoCuota(),
        ];
        
        return Inertia::render('Alumno/Pagos/Create', compact('pagoInfo', 'metodosPago'));
    }
    
    // Generar QR usando PagoFácil API
    public function generarQR(Request $request)
    {
        try {
            $validated = $request->validate([
                'inscripcion_id' => 'required|exists:inscripcion,id',
            ]);

            $inscripcion = Inscripcion::where('alumno_id', Auth::id())
                ->where('id', $validated['inscripcion_id'])
                ->with(['curso'])
                ->firstOrFail();

            $alumno = Auth::user();
            
            // Usar monto de configuración (0.10 Bs por defecto)
            $amount = config('services.pagofacil.payment_amount', 0.10);

            // Generar QR usando el servicio
            $qrData = $this->pagoFacilService->generateQr(
                inscripcionId: $inscripcion->id,
                clientName: $alumno->nombre . ' ' . $alumno->apellido,
                email: $alumno->email,
                phoneNumber: $alumno->telefono ?? '00000000',
                amount: $amount
            );

            // Crear registro de pago pendiente
            $pago = Pago::create([
                'alumno_id' => Auth::id(),
                'inscripcion_id' => $inscripcion->id,
                'monto' => $amount,
                'metodo_pago_id' => MetodoPago::where('nombre', 'LIKE', '%QR%')->first()->id ?? 1,
                'fecha' => now(),
                'transaction_id' => $qrData['transactionId'],
                'payment_number' => $qrData['paymentNumber'],
                'estado_pago' => 'pendiente',
            ]);

            Log::info('QR generado exitosamente', [
                'pago_id' => $pago->id,
                'transaction_id' => $qrData['transactionId'],
            ]);

            return response()->json([
                'success' => true,
                'qr_code' => $qrData['qrImage'],
                'transaction_id' => $qrData['transactionId'],
                'payment_number' => $qrData['paymentNumber'],
                'monto' => $amount,
                'pago_id' => $pago->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error generando QR PagoFácil', [
                'error' => $e->getMessage(),
                'inscripcion_id' => $request->input('inscripcion_id'),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al generar el código QR. Por favor intenta nuevamente.',
            ], 500);
        }
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inscripcion_id' => 'required|exists:inscripcion,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago_id' => 'required|exists:metodo_pago,id',
        ]);
        
        $inscripcion = Inscripcion::where('alumno_id', Auth::id())
            ->where('id', $validated['inscripcion_id'])
            ->firstOrFail();
        
        // Validar que el monto no exceda el saldo pendiente
        if ($validated['monto'] > $inscripcion->getSaldoPendiente()) {
            return back()->withErrors(['monto' => 'El monto excede el saldo pendiente.']);
        }
        
        Pago::create([
            'alumno_id' => Auth::id(),
            'inscripcion_id' => $validated['inscripcion_id'],
            'monto' => $validated['monto'],
            'metodo_pago_id' => $validated['metodo_pago_id'],
            'fecha' => now(),
        ]);
        
        return redirect()->route('alumno.pagos')->with('success', 'Pago registrado correctamente.');
    }
}
