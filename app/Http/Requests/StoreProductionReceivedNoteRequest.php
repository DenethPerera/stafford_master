<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionReceivedNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id'    => 'required|exists:clients,id',
            'customer_po_id' => 'required|exists:customer_pos,id',
            'doc_date'     => 'required|date',
            'cust_do_no'   => 'nullable|string|max:255',
            'gate_pass_no' => 'nullable|string|max:255',
            'job_no'       => 'nullable|string|max:255',
            'emb_no'       => 'nullable|string|max:255',
            'style_no'     => 'nullable|string|max:255',
            'reff_no'      => 'nullable|string|max:255',
            'sewing_no'    => 'nullable|string|max:255',
            'po_no'        => 'nullable|string|max:255',
            'buyer_name'   => 'nullable|string|max:255',
            'remarks'      => 'nullable|string',
            'design_position_id' => 'required|exists:design_positions,id',
            'design_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Dynamic Bundle List arrays
            'bundle_bag_nos'        => 'required|array|min:1',
            'bundle_bag_nos.*'      => 'required|string|max:255',
            'bundle_scales'         => 'required|array|min:1',
            'bundle_scales.*'       => 'required|exists:scales,id',
            'bundle_sizes'          => 'required|array|min:1',
            'bundle_sizes.*'        => 'required|exists:opma_sizes,id',
            'bundle_colors'         => 'required|array|min:1',
            'bundle_colors.*'       => 'required|exists:colors,id',
            'bundle_cut_nums'       => 'required|array|min:1',
            'bundle_cut_nums.*'     => 'required|string|max:255',
            'bundle_cut_froms'      => 'required|array|min:1',
            'bundle_cut_froms.*'    => 'required|integer|min:0',
            'bundle_cut_tos'        => 'required|array|min:1',
            'bundle_cut_tos.*'      => 'required|integer|min:0',
            'bundle_barcodes'       => 'required|array|min:1',
            'bundle_barcodes.*'     => 'required|string|max:255',
            'bundle_qtys'           => 'required|array|min:1',
            'bundle_qtys.*'         => 'required|integer|min:1',
            'bundle_mc_leaders'     => 'required|array|min:1',
            'bundle_mc_leaders.*'   => 'required|string|max:255',
            'bundle_deliver_dates'  => 'required|array|min:1',
            'bundle_deliver_dates.*'=> 'required|date',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'client_id'             => 'customer',
            'customer_po_id'        => 'customer purchase order',
            'doc_date'              => 'document date',
            'cust_do_no'            => 'customer DO number',
            'gate_pass_no'          => 'gate pass number',
            'job_no'                => 'job number',
            'emb_no'                => 'embroidery number',
            'style_no'              => 'style number',
            'reff_no'               => 'reference number',
            'sewing_no'             => 'sewing number',
            'po_no'                 => 'purchase order number',
            'buyer_name'            => 'buyer name',
            'design_position_id'    => 'part name',
            'design_image'          => 'design pattern image',
            
            'bundle_bag_nos.*'      => 'bag number',
            'bundle_scales.*'       => 'scale',
            'bundle_sizes.*'        => 'size',
            'bundle_colors.*'       => 'color',
            'bundle_cut_nums.*'     => 'cut number',
            'bundle_cut_froms.*'    => 'cut from range',
            'bundle_cut_tos.*'      => 'cut to range',
            'bundle_barcodes.*'     => 'bundle number barcode',
            'bundle_qtys.*'         => 'quantity',
            'bundle_mc_leaders.*'   => 'M.C. leader',
            'bundle_deliver_dates.*'=> 'delivery date',
        ];
    }
}
