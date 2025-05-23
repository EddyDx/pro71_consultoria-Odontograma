<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\PaymentFile;
use App\Traits\PaymentModelHelperTrait;


class SaleNotePayment extends ModelTenant
{
    use PaymentModelHelperTrait;

    protected $with = ['payment_method_type', 'card_brand'];
    public $timestamps = false;

    protected $fillable = [
        'sale_note_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'change',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }

    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }

    public function associated_record_payment()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }

    public function payment_file()
    {
        return $this->morphOne(PaymentFile::class, 'payment');
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class);
    }

    
    /**
     * 
     * Filtros para obtener pagos en efectivo y con destino caja
     *
     * @param  Builder $query
     * @return Collection
     */
    public function scopeWhereFilterCashPayment($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::CASH_PAYMENT_ID)
                    ->whereHas('global_payment', function($query){
                        return $query->where('destination_type', Cash::class);
                    });
    }

    
    /**
     * 
     * Obtener informacion del pago y registro origen relacionado
     *
     * @return array
     */
    public function getRowResourceCashPayment()
    {
        return [
            'type' => 'sale_note',
            'type_transaction' => 'income',
            'type_transaction_description' => 'Venta',
            'date_of_issue' => $this->associated_record_payment->date_of_issue->format('Y-m-d'),
            'number_full' => $this->associated_record_payment->number_full,
            'acquirer_name' => $this->associated_record_payment->customer->name,
            'acquirer_number' => $this->associated_record_payment->customer->number,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->getDocumentTypeDescription(),
            'payment_method_type_id' => $this->payment_method_type_id,
            'payment' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->payment,
        ];
    }


    /**
     * 
     * Obtener relaciones necesarias o aplicar filtros para reporte pagos - finanzas
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterRelationsPayments($query)
    {
        // \Log::info("sln");
        return $query->generalPaymentsWithOutRelations()
                    ->with([
                        'payment_method_type' => function($payment_method_type){
                            $payment_method_type->select('id', 'description');
                        }, 
                        'payment_file'
                    ]);
    }

    
    /**
     * 
     * Total de pagos filtrado por id de la nota de venta
     *
     * @param  array $sale_notes_id
     * @return float
     */
    public static function sumPaymentsBySaleNote($sale_notes_id)
    {
        return self::whereIn('sale_note_id', $sale_notes_id)->sum('payment');
    }

    public function cashDocumentPayments()
    {
        return $this->hasMany(CashDocumentPayment::class, 'sale_note_payment_id', 'id');
    }

    /**
     * 
     * Filtros para obtener pagos en efectivo
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterCashPaymentWithoutDestination($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::CASH_PAYMENT_ID);
    }
    
    
    /**
     * 
     * Filtros para obtener pagos con transferencia
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterTransferPayment($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::TRANSFER_PAYMENT_ID);
    }

    
    /**
     * 
     * Filtros para obtener pagos en efectivo de un registro aceptado
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterCashPaymentWithDocument($query)
    {
        return $query->whereHas('associated_record_payment', function ($document) {
                        $document->whereStateTypeAccepted()
                                ->whereNotChanged();
                    })
                    ->filterCashPaymentWithoutDestination();
    }

    
    /**
     * 
     * Filtros para obtener pagos al contado de un documento aceptado
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeDestinationCashPaymentDocument($query)
    {
        return $query->whereHas('associated_record_payment', function ($document) {
                        $document->whereStateTypeAccepted()
                            ->whereNotChanged();
                    })
                    ->whereCashPaymentMethodType();
    }


    /**
     * 
     * Obtener informacion del pago, registro origen y items(opcional) relacionado
     *
     * @return array
     */
    public function getDataCashPaymentReport()
    {
        $data = [
            'total' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->associated_record_payment->total,
            'items_description_html' => ''
        ];

        return array_merge($this->getRowResourceCashPayment(), $data);
    }

    
    /**
     * 
     * Obtener informacion del pago y registro origen relacionado para reporte de ingresos
     *
     * @return array
     */
    public function getRowIncomeSummaryPayment()
    {
        $total = 0;
        $change = 0;
        $payment = 0;
        $payment_for_calculate = 0;

        if(!$this->associated_record_payment->isVoidedOrRejected())
        {
            $total = $this->associated_record_payment->total;
            $change = $this->change ?? 0;
            $payment = $this->payment;
            $payment_for_calculate = $this->payment;

            if(!$this->associated_record_payment->hasNationalCurrency())
            {
                $payment_for_calculate = $this->associated_record_payment->generalConvertValueToPen($this->payment, $this->associated_record_payment->exchange_rate_sale);
            }
        }

        return [
            'type' => 'sale_note',
            'date_time_of_issue' => "{$this->associated_record_payment->date_of_issue->format('Y-m-d')} {$this->associated_record_payment->time_of_issue}",
            'number_full' => $this->associated_record_payment->number_full,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->getDocumentTypeDescription(),
            'payment_method_type_description' => $this->payment_method_type->description,
            'total' => $total,
            'change' => $change,
            'payment' => $payment,
            'payment_for_calculate' => $payment_for_calculate,
        ];
    }


    /**
     *
     * @return string
     */
    public function getPaymentFileUrl()
    {
        return optional($this->payment_file)->getFileUrl('sale_notes');
    }

}
