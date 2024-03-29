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
                            $user = User::withTrashed()->find($objectId);
                            break;
                        case 'post':
                            $user = Post::withTrashed()->find($objectId)->user;
                            break;
                        case 'comment':
                            $user = Comment::withTrashed()->find($objectId)->user;
                            break;
                    }

                    if ($penalty == PenaltyType::DELETE_USER) {
                        $user->delete();
                    } else {
                        $user->banned_until = $date;
                        $user->save();
                    }
                }

                if ($penalty == PenaltyType::DELETE_POST && in_array($objectType, ['post', 'comment'])) {
                    $post = Post::find(
                        $objectType == 'post' ? $objectId : (Comment::withTrashed()->find($objectId)->id ?? 0),
                    );

                    if ($post) {
                        $post->delete();
                    }
                }

                if ($penalty == PenaltyType::DELETE_COMMENT) {
                    Comment::withTrashed()->find($objectId)->delete();
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

    public function ban()
    {
        $type = rtrim(request('type', ''), 's');
        $id = request('id');
        $description = request('description', '');
        $date = request('date');

        DB::beginTransaction();
        try {
            $userId = null;

            switch ($id) {
                case 'user':
                    $userId = User::find($id)->id;
                    break;
                case 'post':
                    $userId = Post::find($id)->user_id;
                    break;
                case 'comment':
                    $userId = Comment::find($id)->user_id;
                    break;
            }

            $user = User::withTrashed()->find($userId);
            if ($user) {
                $user->banned_until = $date;
                $user->save();
            }

            $obj = new Report([
                'object_type' => $type,
                'object_id' => $id,
                'user_id' => Auth::id() ?? 0,
                'admin_id' => Auth::id() ?? 0,
                'description' => $description,
                'status' => ReportStatus::ENFORCED,
                'penalty' => PenaltyType::BAN,
            ]);

            $obj->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error('Something went wrong');
            return false;
        }

        toastr('User banned successfully');
        return true;
    }

    public function pardon($id)
    {
        $report = Report::where('status', ReportStatus::ENFORCED)->findOrFail($id);

        $userId = 0;

        if ($report->object_type == 'user') {
            $userId = $report->object_id;
        } elseif ($report->object_type == 'post') {
            $userId = Post::find($report->object_id)->user_id ?? 0;
        } elseif ($report->object_type == 'comment') {
            $userId = Comment::find($report->object_id)->user_id ?? 0;
        }

        $user = User::withTrashed()->find($userId);

        DB::beginTransaction();
        try {
            switch ($report->penalty) {
                case PenaltyType::BAN:
                    if ($user) {
                        $user->banned_until = null;
                        $user->save();
                    }
                    break;
                case PenaltyType::DELETE_USER:
                    if ($user) {
                        $user->restore();
                    }
                    break;
                case PenaltyType::DELETE_POST:
                    $post = Post::onlyTrashed()->find($report->object_id);
                    if ($post) {
                        $post->restore();
                    }
                    break;
                case PenaltyType::DELETE_COMMENT:
                    $comment = Comment::onlyTrashed()->find($report->object_id);
                    if ($comment) {
                        $comment->restore();
                    }
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
        if ($obj->object_type === 'comment') {
            $obj->setRelation('post', $obj->object->post);
            $obj->setRelation('user', $obj->object->user);
        }
        return response()->json($obj);
    }
}
