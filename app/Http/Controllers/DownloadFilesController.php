<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadFilesController extends Controller
{
    public function downloadInfo(){
        return Storage::download('Academic_Integrity_Self_Evaluation_Tools_Report_2019.pdf');
    }
}