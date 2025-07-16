<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        // Log configuration (without sensitive data)
        Log::info('Midtrans configuration loaded', [
            'is_production' => Config::$isProduction,
            'is_sanitized' => Config::$isSanitized,
            'is_3ds' => Config::$is3ds,
            'server_key_set' => !empty(Config::$serverKey)
        ]);
    }

    /**
     * Membuat transaksi pembayaran
     */
    public function createTransaction($order)
    {
        try {
            // Validasi data order
            if (!$order->user || !$order->service) {
                throw new \Exception('Order data incomplete: missing user or service');
            }

            if (!$order->midtrans_order_id) {
                throw new \Exception('Midtrans order ID is required');
            }

            if ($order->total_price <= 0) {
                throw new \Exception('Invalid total price: ' . $order->total_price);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->midtrans_order_id,
                    'gross_amount' => (int) $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->no_hp ?? '08123456789',
                ],
                'enabled_payments' => ['bca_va', 'bni_va', 'permata_va', 'danamon_va', 'mandiri_va', 'bri_va', 'cimb_va'],
                'item_details' => [
                    [
                        'id' => $order->service->id,
                        'price' => (int) $order->total_price,
                        'quantity' => 1,
                        'name' => optional($order->user)->name ?? '-',
                    ]
                ],
                'callbacks' => [
                    'finish' => route('member.payment.finish'),
                    'unfinish' => route('member.payment.unfinish'),
                    'error' => route('member.payment.error'),
                ]
            ];

            Log::info('Creating Midtrans transaction', [
                'order_id' => $order->midtrans_order_id,
                'gross_amount' => $params['transaction_details']['gross_amount'],
                'customer_email' => $params['customer_details']['email']
            ]);

            $transaction = Snap::createTransaction($params);

            Log::info('Midtrans transaction created successfully', [
                'order_id' => $order->midtrans_order_id,
                'token_length' => strlen($transaction->token ?? '')
            ]);

            return $transaction;
        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans transaction', [
                'order_id' => $order->midtrans_order_id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Mengecek status transaksi
     */
    public function getTransactionStatus($orderId)
    {
        try {
            Log::info('Getting transaction status from Midtrans', ['order_id' => $orderId]);

            $status = Transaction::status($orderId);

            Log::info('Transaction status retrieved', [
                'order_id' => $orderId,
                'status' => $status->transaction_status ?? 'unknown'
            ]);

            return $status;
        } catch (\Exception $e) {
            Log::error('Failed to get transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
