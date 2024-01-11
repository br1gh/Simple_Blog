<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PenaltyType;
use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Vendor\Table;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index($status = 'pending')
    {
        $table = new Table(
            'reports',
            'report',
            [
                'object_type',
                'object_id',
                'status',
                'penalty',
            ],
            [
                'object_type' => 'Object type',
            ],
            [
                'enforce',
                'pardon',
                'forceDelete',
            ],
            [],
            $status
        );

        if (request()->ajax()) {
            return response()->json($table->render());
        }

        return view('layouts.admin.index.index', [
            'table' => $table
        ]);
    }

    public function enforce()
    {
        $reportId = request('reportId');
        $status = request('status', ReportStatus::PENDING);
        $objectId = request('objectId');
        $objectType = request('objectType');
        $penalty = request('penalty', PenaltyType::NONE);
        $date = request('date');

        $report = Report::find($reportId);

        DB::beginTransaction();
        try {
            if ($status == ReportStatus::ENFORCED) {
                if (in_array($penalty, [PenaltyType::BAN, PenaltyType::DELETE_USER])) {
                    $user = null;

                    switch ($objectType) {
                        case 'user':
                            $user = User::find($objectId);
                            break;
                        case 'post':
                            $user = Post::find($objectId)->user;
                            break;
                        case 'comment':
                            $user = Comment::find($objectId)->user;
                            break;
                    }

                    if ($penalty == PenaltyType::DELETE_USER) {
                        $user->comments()->delete();
                        $postIds = $user->posts->pluck('id')->toArray();
                        Comment::whereIn('post_id', $postIds)->delete();
                        $user->posts()->delete();
                        $user->delete();
                    } else {
                        $user->banned_until = $date;
                        $user->save();
                    }
                }

                if ($penalty == PenaltyType::DELETE_POST && in_array($objectType, ['post', 'comment'])) {
                    $post = $objectType == 'post' ? Post::find($objectId) : Comment::find($objectId)->post;
                    $post->comments()->delete();
                    $post->delete();
                }

                if ($penalty == PenaltyType::DELETE_COMMENT) {
                    Comment::find($objectId)->delete();
                }
            }

            $report->fill([
                'status' => $status,
                'penalty' => $penalty,
                'admin_id' => Auth::id(),
            ]);

            $report->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            toastr()->error('Something went wrong');
            return false;
        }
        toastr('Report ' . ($status == ReportStatus::ENFORCED ? 'enforced' : 'rejected') . ' successfully');
        return true;
    }

    public function pardon($id)
    {
        $report = Report::where('status', ReportStatus::ENFORCED)->findOrFail($id);

        DB::beginTransaction();
        try {
            switch ($report->penalty) {
                case PenaltyType::BAN:
                    $user = User::find($report->object_id);
                    $user->banned_until = null;
                    $user->save();
                    break;
                case PenaltyType::DELETE_USER:
                    $user = User::find($report->object_id);
                    $user->restore();
                    break;
                case PenaltyType::DELETE_POST:
                    $post = Post::find($report->object_id);
                    $post->restore();
                    break;
                case PenaltyType::DELETE_COMMENT:
                    $comment = Comment::find($report->object_id);
                    $comment->restore();
                    break;
            }

            $report->fill([
                'status' => ReportStatus::REJECTED,
                'admin_id' => Auth::id(),
            ]);

            $report->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            toastr()->error('Something went wrong');
            return redirect()->route('admin.reports.index', ['type' => 'enforced']);
        }

        toastr('Pardoned successfully');
        return redirect()->route('admin.reports.index', ['type' => 'enforced']);
    }

    public function forceDelete($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.users.index');
        }

        DB::beginTransaction();
        try {
            $obj = Report::findOrFail($id);
            $obj->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.reports.index');
        }

        toastr('Report deleted permanently');
        return redirect()->route('admin.reports.index');
    }

    public function fetch($id)
    {
        $obj = Report::findOrFail($id);
        $obj->setRelation('object', $obj->object);
        if ($obj->object_type === 'comment'){
            $obj->setRelation('post', $obj->object->post);
            $obj->setRelation('user', $obj->object->user);
        }
        return response()->json($obj);
    }
}
