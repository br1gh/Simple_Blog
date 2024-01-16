<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReportController extends Controller
{
    /**
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function report()
    {
        $type = request()->get('type', null);
        $id = request()->get('id', null);
        $description = request()->get('description', null);
        dd($type, $id, $description);

        if (in_array(null, [$type, $id])) {
            throw new Exception('Invalid request');
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
            throw $exception;
        }

        return response()->json();
    }
}
