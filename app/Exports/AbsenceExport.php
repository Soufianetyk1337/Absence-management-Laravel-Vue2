<?php

namespace App\Exports;

use App\absence;
use App\module;
use App\student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class AbsenceExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       #return absence::select('id','justification','hours','date')->get();
    	return DB::table('absences')
        ->join('students' ,'students.id','=','absences.student_id')
        ->join('modules' ,'modules.id','=','absences.student_id')
        ->select('students.first_name','students.last_name','modules.name','justification','hours','date')
        ->get();	
    }

    public function headings(): array {
    	return [
    		'Nom','Prenom','Module','Justifie','Heures','Date',
    	];
    }
}
