<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pdf;

class PdfController extends Controller
{
    public function index()
    {
        $pdfs = Pdf::all();
        return response()->json(['pdfs'=> $pdfs]);
    }

    public function store(StorePdfRequest $request)
    {
        $path = $request->file('pdf')->store('pdfs');
        $filename = $request->input('filename');
        $pdf = new Pdf;
        $pdf->filepath = $path;
        $pdf->filename = !empty($filename) ? $filename : "Document";
        $pdf->save();
        return response()->json(['result'=> 'success']);
    }
}
