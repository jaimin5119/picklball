<?php

namespace App\Exports;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUser implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        //return $collection;
        return User::select('id','first_name','last_name','email','mobile','image')->get();
    }

     public function headings(): array
    {
        return [
            'Id',
            'First name',
            'Last name',
            'Email',
            'Mobile',
            'Image',
        ];
    }
}
