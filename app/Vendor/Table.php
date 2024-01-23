<?php

namespace App\Vendor;

use App\Enums\ReportStatus;
use Illuminate\Support\Facades\DB;

class Table
{
    public $tableName;
    public $showRouteName;
    public $sqlSelect;
    private $columns;
    private $headers;
    public $orderableColumns;
    private $actions;
    public $reportStatus;

    public function __construct(
        string $tableName,
        string $showRouteName,
        array  $sqlSelect,
        array  $columns,
        array  $actions = [],
        array  $orderableColumns = [],
        ?string $reportStatus = null
    )
    {
        $this->tableName = $tableName;
        $this->showRouteName = $showRouteName;
        $this->sqlSelect = $sqlSelect;
        $this->columns = $columns;
        $this->headers = array_values($this->columns);
        $this->orderableColumns = $orderableColumns ?: $this->columns;
        $this->actions = $actions;
        $this->reportStatus = $reportStatus;
    }

    public function render(): array
    {
        $column = request()->get('column', 'id');
        $order = request()->get('order', 'asc');
        $search = request()->get('search');
        $page = request()->get('page', 1);
        $limit = request()->get('limit', 10);
        $filter = request()->get('filter', []);

        $db = DB::table($this->tableName);
        $page = min($page, intval(ceil($db->count() / $limit)));

        $select = $this->sqlSelect;

        if ($this->tableName === 'reports') {
            $select = array_merge($select, ['reports.id', 'username']);
        } else {
            $select[] = $this->tableName . '.id';
        }

        $db->select($select);

        if (in_array($this->tableName, ['posts', 'reports'])) {
            $db->leftJoin('users', 'users.id', '=', $this->tableName . '.user_id');
        }

        if (in_array($this->tableName, ['users', 'posts'])) {
            $db->where($this->tableName . '.id', '<>', 1);
        }

        if ($this->tableName === 'reports') {
            $db->where('status', ReportStatus::getStatusFromString($this->reportStatus));
        }

        if ($search) {
            $db->where(function ($q) use ($search) {
                foreach ($this->sqlSelect as $col) {
                    $q->orWhere($col, 'like', '%' . $search . '%');
                }
            });
        }

        $db->orderBy($column, $order);
        $items = $db->paginate($limit, [], 'page', $page);

        return [
            'html' => view('layouts.admin.index.table', [
                'items' => $items,
                'headers' => $this->headers,
                'fields' => array_keys($this->columns),
                'tableName' => $this->tableName,
                'showRouteName' => $this->showRouteName,
                'actions' => $this->actions,
                'reportStatus' => $this->reportStatus,
            ])->render(),
            'lastPage' => $items->lastPage(),
            'currentPage' => $items->currentPage(),
        ];
    }
}
