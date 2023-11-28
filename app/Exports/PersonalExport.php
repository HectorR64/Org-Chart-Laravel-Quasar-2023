<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PersonalExport implements FromCollection, WithHeadings
{
    protected $personal;

    public function __construct($personal)
    {
        $this->personal = $personal;
    }
    //Recibe la coleccion de la consulta tratada en el controlador
    public function collection()
    {
        return $this->personal->map(function ($item) {
            return [
                'Id' => $item->id,
                'Nombre' => $item->label,
                'Jefe' => $item->parent ? $item->parent->label : 'No Parent',
                'Departmento' => $item->department ? $item->department->name : 'N/A',
            ];
        });
    }
    //Headers de la tabla
    public function headings(): array
    {
        return [ 'Id','Nombre', 'Jefe', 'Departmento'];
    }
}







