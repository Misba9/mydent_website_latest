<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (auth()->check() && auth()->user()->hasRole('patient') && auth()->user()->patient) {
            $this->merge([
                'patient_id' => auth()->user()->patient->id,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = Appointment::$rules;

        return $rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Service field is required.',
            'patient_id.required' => 'Patient field is required.',
            'from_time.required' => 'Please select appointment time slot.',
        ];
    }
}
