<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
// use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{ 
    // FromCollection, Responsable
    // use Exportable;

    // private $fileName = 'users.xlsx';
    private $data;
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request)
    {
        # code...
        $this->data = $request;
    }

    public function view(): View
    {
        # code...
        return view('exports.casa', [
            'users' => $this->data
        ]);
    }
    // public function collection()
    // {
    //     return User::all();
    // }
}
