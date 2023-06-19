<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report($type, $id)
    {
        if (!in_array($type, ['user', 'post', 'comment'])) {
            return back();
        }

        $description = request()->get('description');
        $model = 'App\Models\\' . ucfirst($type);
        $obj = $model::find($id);

        if (!$obj) {
            return back();
        }

        DB::beginTransaction();
        try {
            $obj = new Report();
            $obj->fill([
                'object_type' => $type,
                'object_id' => $id,
                'user_id' => Auth::id(),
                'description' => $description,
            ]);
            $obj->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            toastr()->error('Something went wrong');
            return back();
        }

        toastr(ucfirst($type) . ' has been reported!');
        return back();
    }
}
