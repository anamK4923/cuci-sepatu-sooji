<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'delivery_method' => ['required', Rule::in([Order::DELIVERY_ANTAR_JEMPUT, Order::DELIVERY_DROP_OFF])],
            'alamat_pickup' => 'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT . '|nullable|string|max:1000',
            'pickup_schedule' => 'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT . '|nullable|date|after:+2 hours',
            'notes' => 'nullable|string|max:1000',
            'total_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Layanan harus dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'delivery_method.required' => 'Metode pengiriman harus dipilih.',
            'delivery_method.in' => 'Metode pengiriman tidak valid.',
            'alamat_pickup.required_if' => 'Alamat penjemputan wajib diisi untuk metode antar jemput.',
            'alamat_pickup.max' => 'Alamat penjemputan maksimal 1000 karakter.',
            'pickup_schedule.required_if' => 'Jadwal penjemputan wajib diisi untuk metode antar jemput.',
            'pickup_schedule.date' => 'Format jadwal penjemputan tidak valid.',
            'pickup_schedule.after' => 'Jadwal penjemputan minimal 2 jam dari sekarang.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
            'total_price.required' => 'Total harga harus ada.',
            'total_price.numeric' => 'Total harga harus berupa angka.',
            'total_price.min' => 'Total harga tidak boleh negatif.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->service_id) {
                $service = \App\Models\Service::find($this->service_id);
                if ($service && $this->total_price != $service->price) {
                    $validator->errors()->add('total_price', 'Harga tidak sesuai dengan layanan yang dipilih.');
                }
            }
        });
    }
}
