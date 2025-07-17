<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSistemaRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $isCreate = $this->isMethod('post'); // Detectar si es creación

        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'departamento_id' => 'required|exists:departamentos,id',
            'url' => 'required|string|max:255',
            'fecha_creacion' => 'required|date',
            'fecha_produccion' => 'required|date',
            'estatus' => 'required|string|max:255',
            'numero_usuarios' => 'required|string|max:255',
            'nombre_servidor' => 'required|string|max:255',
            'ip_servidor' => 'required|string|max:255',
            'sistema_operativo' => 'required|string|max:255',
            'nombre_servidor_bd' => 'required|string|max:255',
            'ip_servidor_bd' => 'required|string|max:255',
            'lenguaje_desarrollo' => 'required|string|max:255',
            'version_lenguaje' => 'required|string|max:255',
            'ruta_documento' => 'required|array',
            'ruta_documento.*' => 'file|mimes:pdf|max:102400',
        ];
    }

    public function messages(): array
    {
        return [
            //'departamento_id.required' => 'Debe seleccionar un departamento mois.',
        ];
    }

    public function attributes(): array
    {
        return [
             'departamento_id' => 'departamento', // 
        ];
    }
}