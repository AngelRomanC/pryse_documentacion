<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarioEquipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true
        ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
            'nombre_persona'         => 'required|string|max:255',
            'departamento_id'        => 'required|integer|exists:departamentos,id',
            'tipo_pc'                => 'required|string|max:100',
            'marca_equipo'           => 'required|string|max:100',
            'sistema_operativo'      => 'required|string|max:100',
            'procesador'             => 'required|string|max:255',
            'tarjeta_madre'          => 'required|string|max:255',
            'tarjeta_grafica'        => 'required|string|max:255',
            'datos_tarjeta_grafica'  => 'required|string|max:255',
            'tipo_ram'               => 'required|string|max:50',
            'capacidad_ram'          => 'required|string|max:50',
            'marca_ram'              => 'required|string|max:100',
            'tipo_disco'             => 'required|string|max:50',
            'capacidad_disco'        => 'required|string|max:50',
            'teclado_mouse'          => 'required|string|max:255',
            'camara_web'             => 'required|string|max:255',
            'otro_periferico'        => 'required|string|max:255',
            'software_remoto'        => 'nullable|string|max:255',
            'id_remoto'              => 'nullable|string|max:255',
            'password_remoto'        => 'nullable|string|max:255',
            'name_id'                => 'required|integer|exists:users,id',
            'observaciones'          => 'nullable|string|max:500',
        ];
    }
    public function messages()
{
    return [
        'departamento_id.required' => 'El campo departamento es obligatorio.',
        'departamento_id.integer' => 'El departamento debe ser un valor numérico.',
        'departamento_id.exists' => 'El departamento seleccionado no es válido.',

        'name_id.required' => 'El campo nombre es obligatorio.',

    ];
}

}
