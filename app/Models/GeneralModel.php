<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GeneralModel
{
    /**
     * Get records from a table with flexible options.
     *
     * @param string $table
     * @param string $row 'first' | 'all'
     * @param array  $whereConditions [[column, operator, value], ...]
     * @param string $orderBy e.g. 'created_time DESC'
     * @param string $select
     * @param int|null $limit
     * @return mixed
     */
    public function getGeneral($table = '', $row = '', $whereConditions = [], $orderBy = '', $select = '*', $limit = null)
    {
        if (empty($table)) {
            throw new \InvalidArgumentException('Table name cannot be empty.');
        }

        $query = DB::table($table);

        if (!empty($whereConditions)) {
            foreach ($whereConditions as $condition) {
                if (count($condition) == 3) {
                    $query->where($condition[0], $condition[1], $condition[2]);
                }
            }
        }

        if ($select !== '*') {
            $query->selectRaw($select);
        }

        if (!empty($orderBy)) {
            $parts = explode(' ', $orderBy);
            $col = $parts[0];
            $dir = isset($parts[1]) ? $parts[1] : 'asc';
            $query->orderBy($col, $dir);
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        if ($row === 'first' || empty($row)) {
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public static function getByIdGeneral($table = '', $row = '', $column = '', $id = '')
    {
        $instance = new self();
        return $instance->getGeneral($table, $row ?: 'first', [[$column, '=', $id]]);
    }

    public static function getByMultiIdGeneral($table = '', $row = '', $column = '', $id = '', $column2 = '', $id2 = '')
    {
        $instance = new self();
        return $instance->getGeneral($table, $row ?: 'first', [
            [$column, '=', $id],
            [$column2, '=', $id2],
        ]);
    }

    public static function create($table, array $data)
    {
        if (empty($table)) {
            throw new \InvalidArgumentException('Table name cannot be empty.');
        }
        return DB::table($table)->insertGetId($data);
    }

    public static function update($table, array $data, $whereConditions = [])
    {
        if (empty($table)) {
            throw new \InvalidArgumentException('Table name cannot be empty.');
        }
        $query = DB::table($table);
        if (!empty($whereConditions)) {
            foreach ($whereConditions as $condition) {
                if (count($condition) == 3) {
                    $query->where($condition[0], $condition[1], $condition[2]);
                }
            }
        }
        return $query->update($data);
    }

    public static function updateById($table, array $data, $column, $id)
    {
        return self::update($table, $data, [[$column, '=', $id]]);
    }

    public static function delete($table, $whereConditions = [])
    {
        if (empty($table)) {
            throw new \InvalidArgumentException('Table name cannot be empty.');
        }
        $query = DB::table($table);
        if (!empty($whereConditions)) {
            foreach ($whereConditions as $condition) {
                if (count($condition) == 3) {
                    $query->where($condition[0], $condition[1], $condition[2]);
                }
            }
        }
        return $query->delete();
    }

    public static function deleteById($table, $column, $id)
    {
        return self::delete($table, [[$column, '=', $id]]);
    }

    /**
     * Get records with DataTables support.
     */
    public function getDatatable($table, $whereConditions = [], $select = '*', $orderBy = '')
    {
        $query = DB::table($table);

        if ($select !== '*') {
            $query->selectRaw($select);
        }

        if (!empty($whereConditions)) {
            foreach ($whereConditions as $condition) {
                if (count($condition) == 3) {
                    $query->where($condition[0], $condition[1], $condition[2]);
                }
            }
        }

        if (!empty($orderBy)) {
            $parts = explode(' ', $orderBy);
            $query->orderBy($parts[0], isset($parts[1]) ? $parts[1] : 'asc');
        }

        return DataTables::of($query)->make(true);
    }

    /**
     * Paginate records.
     */
    public function paginateGeneral($table, $perPage = 15, $whereConditions = [], $orderBy = '', $select = '*')
    {
        $query = DB::table($table);

        if ($select !== '*') {
            $query->selectRaw($select);
        }

        if (!empty($whereConditions)) {
            foreach ($whereConditions as $condition) {
                if (count($condition) == 3) {
                    $query->where($condition[0], $condition[1], $condition[2]);
                }
            }
        }

        if (!empty($orderBy)) {
            $parts = explode(' ', $orderBy);
            $query->orderBy($parts[0], isset($parts[1]) ? $parts[1] : 'asc');
        }

        return $query->paginate($perPage);
    }
}
