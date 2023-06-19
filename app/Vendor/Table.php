<?php

namespace App\Vendor;

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

    public function __construct(
        string $tableName,
        string $showRouteName,
        array  $sqlSelect,
        array  $columns,
        array  $actions = [],
        array  $orderableColumns = []
    )
    {
        $this->tableName = $tableName;
        $this->showRouteName = $showRouteName;
        $this->sqlSelect = $sqlSelect;
        $this->columns = $columns;
        $this->headers = array_values($this->columns);
        $this->orderableColumns = $orderableColumns ?: $this->columns;
        $this->actions = $actions;
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
            $select[] = 'id';
        }

        $db->select($select);

        if ($this->tableName === 'reports') {
            $db->leftJoin('users', 'users.id', '=', 'reports.user_id');
        }

        if ($this->tableName === 'users') {
            $db->where('id', '<>', 1);
        }

//        if ($filter) {
//            foreach ($filter['multiselect'] ?? [] as $field => $value) {
//                if ($value) {
//                    $db->whereIn($field, $value);
//                }
//            }
//
//            foreach ($filter['select'] ?? [] as $field => $value) {
//                if ($value) {
//                    $db->where($field, $value);
//                }
//            }
//        }

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
            ])->render(),
            'lastPage' => $items->lastPage(),
            'currentPage' => $items->currentPage(),
        ];
    }
}
