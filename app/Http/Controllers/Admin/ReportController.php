<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Vendor\Table;

class ReportController extends Controller
{
    public function index()
    {
        $table = new Table(
            'reports',
            'report',
            [
                'description',
                'object_type',
                'status',
            ],
            [
                'description' => 'Description',
                'object_type' => 'Object type',
            ],
            [
//                'delete',
            ]
        );

        if (request()->ajax()) {
            return response()->json($table->render());
        }

        return view('layouts.admin.index.index', [
            'table' => $table
        ]);
    }
}
