<?php

namespace App\Http\Requests\V1;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->can(
            'create',
            Transaction::class
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'borrower_id' => ['required', 'exists:users,id'],
            'due_date' => ['required', 'date'],
            'items' => ['required', 'array'],
            'items.*.name' => ['required', 'string'],
            'items.*.price' => ['required', 'numeric'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.name.required' => 'Required to have a name.',
            'items.*.price.required' => 'Required to have a price.',
            'items.*.price.numeric' => 'The price must be a number.',
            'items.*.quantity.required' => 'Required to have a quantity.',
            'items.*.quantity.numeric' => 'The quantity must be a number.',
            'items.*.quantity.min' => 'The quantity must be at least 1.',
        ];
    }
}
