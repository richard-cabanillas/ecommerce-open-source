<?php

namespace Webkul\Admin\Listeners;
use Illuminate\Support\Facades\Log;

use Webkul\Admin\Mail\Order\CanceledNotification;
use Webkul\Admin\Mail\Order\CreatedNotification;
use Webkul\Sales\Contracts\Order as OrderContract;

class Order extends Base
{
    /**
     * After order is created
     *
     * @return void
     */
    public function afterCreated(OrderContract $order)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_order_mail_to_admin')) {
                return;
            }

            $this->prepareMail($order, new CreatedNotification($order));
        } catch (\Exception $e) {
            report($e);
        }
    }

/* 

*/


  public function afterInvoiced($order)
    {
        try {
            Log::info('📧 Enviando correo de factura para la orden ID: ' . $order->id);

            $this->prepareMail($order, new \Webkul\Admin\Mail\Order\InvoicedNotification($order));
        } catch (\Exception $e) {
            Log::error('❌ Error al enviar correo de factura: ' . $e->getMessage());
            report($e);
        }
    }


    /**
     * Send cancel order mail.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function afterCanceled($order)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.cancel_order_mail_to_admin')) {
                return;
            }

            $this->prepareMail($order, new CanceledNotification($order));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
