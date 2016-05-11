<?php

namespace App\Models;

use App\Models\TransactionsLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class JqGridBase extends Model
{

    protected $dates = ['deleted_at'];

    public function scopeJqGridFullCount($query, $where = '', $join = null)
    {

        if (!empty($join)) {
            if (isset($join['left'])) {
                $query->leftJoin($join['left'], $join['on'], $join['op'], $join['to']);
            } else {
                $query->join($join['table'], $join['on'], $join['op'], $join['to']);
            }
        }
        if (!empty($where)) {
            $query->whereRaw($where);
        }

        if (\Request::input('_search', null) === 'true') {
            $where_ = $this->generateSearchString(\Request::input('filters', null));
        }

        if (!empty($where_)) {
            $query->whereRaw($where_);
        }

        return $query->count();
    }

    public function scopeJqGridFullDesignation($query, $options, $where = '', $join = null, $select = null)
    {
        // $query->onlyTrashed();
        //

        if (is_null($join)) {
            $query->select('*');
        } else {

            $query->select(\DB::raw($select)); // потому как id  и дескрипшин совпадают
        }

        if (!is_null($join)) {
            //private $join = ['left'=>'cc_carriers', 'on'=>'cc_carriers.id_carriers', 'op'=>'=', 'to'=>'cc_supertrunk.carriers'];
            if (isset($join['left'])) {
                $query->leftJoin($join['left'], $join['on'], $join['op'], $join['to']);
                // $query->select($this->table.'.*', $this->table.'.id as first_id', $this->table.'.description as first_description',$join['left'].'.*');
            } else {
                $query->join($join['table'], $join['on'], $join['op'], $join['to']);
            }
        }
        if (!empty($where)) {
            $query->whereRaw($where);
        }

        $where_=NULL;
        if (\Request::input('_search', null) === 'true') {
            $where_ = $this->generateSearchString(\Request::input('filters', null));
        }
        if (!empty($where_)) {
            $query->whereRaw($where_);
        }

        return $query->orderBy($this->table . "." . $options['table_sort'], $options['type_sort'])->skip($options['start'])->take($options['limit'])->get();
    }

    public function scopeJqGridAdd($query, $data, $table = null)
    {
        // $query->onlyTrashed();
        if (empty($table)) {
            $table = $this->table;
        }
        $data['created_at'] = date("Y-m-d H:i:s");
        $id                 = $query->withTrashed()->insertGetId($data);

        $log = array(
            'user_id'           => User::getUser()->id,
            'transactions_type' => $table,
            'transactions_id'   => $id,
            'type_action'       => 'add',
            'description'       => "Добавление новой записи",
            'created_at'        => date("Y-m-d H:i:s"),
        );
        TransactionsLog::insert($log);
        return $id;

    }

    public function scopeJqGridEdit($query, $id, $data, $table = null)
    {
        // $query->onlyTrashed();
        if (empty($table)) {
            $table = $this->table;
        }

        if (!is_numeric($id)) {
            return \Response::make('Операция не выполнена Id найден', 500)->header('Content-Type', 'application/json');
        }
        $data['updated_at'] = date("Y-m-d H:i:s");

        $old_data = $query->findOrFail($id);
        if (!$old_data) {
            return \Response::make("Операция не выполнена запист с id: $id не найдена", 500)->header('Content-Type', 'application/json');
        }
        $query->withTrashed()->where((string) $this->primaryKey, $id)->update($data);

        $log = array(
            'user_id'           => \Sentry::getUser()->id,
            'transactions_type' => $table,
            'transactions_id'   => $id,
            'type_action'       => 'edit',
            'old_data'          => serialize($old_data),
            'description'       => "Редактирование записи",
            'created_at'        => date("Y-m-d H:i:s"),
        );

        TransactionsLog::insert($log);
        return true;

    }

    public function scopeReturnBaseVariable($query)
    {

        return array(
            'primarykey' => (string) $this->primaryKey,
            'table'      => (string) $this->table,
            'connection' => (string) $this->connection,
        );
    }

    public function scopeJqGridDel($query, $id, $table = null)
    {
        // $query->onlyTrashed();
        if (empty($table)) {
            $table = $this->table;
        }

        if (!is_numeric($id)) {
            return \Response::make('Операция не выполнена Id найден', 500)->header('Content-Type', 'application/json');
        }
        $data['updated_at'] = date("Y-m-d H:i:s");

        $log = array(
            'user_id'           => \Sentry::getUser()->id,
            'transactions_type' => $table,
            'transactions_id'   => $id,
            'type_action'       => 'del',
            'description'       => "Удаление записи",
            'created_at'        => date("Y-m-d H:i:s"),
        );

        $query->withTrashed()->where((string) $this->primaryKey, $id)->delete();
        return true;

    }

    public function generateSearchString($filters)
    {

        // $searchField  = \Request::input('searchField', null);
        // $searchOper   = \Request::input('searchOper', null);
        // $searchString = \Request::input('searchString', null);

        $where = '';
        if ($filters) {
            $filters = json_decode($filters);
            $where .= self::generateSearchStringFromObj($filters);
        }

        return $where;

    }
    public function generateSearchStringFromObj($filters)
    {
        $where = '';

        // Генерация условий группы фильтров
        if (count($filters)) {
            foreach ($filters->rules as $index => $rule) {
                $rule->data = addslashes($rule->data);

                $where .= "`" . preg_replace('/-|\'|\"/', '', $rule->field) . "`";
                switch ($rule->op) {
                    // В будущем будет больше вариантов для всех вохможных условий jqGrid
                    case 'eq':$where .= " = '" . $rule->data . "'";
                        break;
                    case 'ne':$where .= " != '" . $rule->data . "'";
                        break;
                    case 'lt':
                        if (in_array($rule->field, ['created_at', 'updated_at'])) {
                            $where .= " < STR_TO_DATE('" . $rule->data . "', '%d.%m.%Y') ";
                        } else {
                            $where .= " < '" . $rule->data . "'";
                        }
                        break;
                    case 'gt':

                        if (in_array($rule->field, ['created_at', 'updated_at'])) {
                            // list($d, $m, $y) = explode('.', $rule->data);
                            // $where .= " <  '" . sprintf('%4d-%02d-%02d', $y, $m, $d) . "'";
                            $where .= " > STR_TO_DATE('" . $rule->data . "', '%d.%m.%Y') ";
                        } else {
                            $where .= " > '" . $rule->data . "'";
                        }

                        break;
                    case 'bw':$where .= " LIKE '" . $rule->data . "%'";
                        break;
                    case 'bn':$where .= " NOT LIKE '" . $rule->data . "%'";
                        break;
                    case 'ew':$where .= " LIKE '%" . $rule->data . "'";
                        break;
                    case 'en':$where .= " NOT LIKE '%" . $rule->data . "'";
                        break;
                    case 'cn':$where .= " LIKE '%" . $rule->data . "%'";
                        break;
                    case 'nc':$where .= " NOT LIKE '%" . $rule->data . "%'";
                        break;
                    case 'nu':$where .= " IS NULL";
                        break;
                    case 'nn':$where .= " IS NOT NULL";
                        break;
                    case 'in':$where .= " IN ('" . str_replace(",", "','", $rule->data) . "')";
                        break;
                    case 'ni':$where .= " NOT IN ('" . str_replace(",", "','", $rule->data) . "')";
                        break;
                }

                // 'eq' => '=',
                // 'ne' => '<>',
                // 'lt' => '<',
                // 'le' => '<=',
                // 'gt' => '>',
                // 'ge' => '>=',

                // Добавить логику соединения, если это не последние условие
                if (count($filters->rules) != ($index + 1)) {
                    $where .= " " . addslashes($filters->groupOp) . " ";
                }

            }
        }

        // Генерация условий подгруппы фильтров
        $isSubGroup = false;
        if (isset($filters->groups)) {
            foreach ($filters->groups as $groupFilters) {
                $groupWhere = self::generateSearchStringFromObj($groupFilters);
                // Если подгруппа фильтров содержит условия, то добавить их
                if ($groupWhere) {
                    // Добавить логику соединения, если условия подгруппы фильтров добавляются после условий фильтров этой группы
                    // или после условий других подгрупп фильтров
                    if (count($filters->rules) or $isSubGroup) {
                        $where .= " " . addslashes($filters->groupOp) . " ";
                    }

                    $where .= $groupWhere;
                    $isSubGroup = true; // Флаг, определяющий, что было хоть одно условие подгрупп фильтров
                }
            }
        }

        if ($where) {
            return '(' . $where . ')';
        }

        return ''; // Условий нет
    }

    public static function CrViewForJqgrid($massiv)
    {
        $string  = '';
        $total   = count($massiv);
        $counter = 0;
        foreach ($massiv as $key => $value) {
            $counter++;
            // последний элемент
            if ($counter == $total) {
                $string .= self::RemCurvesCharacters($key) . ":" . self::RemCurvesCharacters($value);
            } else {
                $string .= self::RemCurvesCharacters($key) . ":" . self::RemCurvesCharacters($value) . ";";
            }
        }
        return $string;

    }
// убираем с вывода все лишнее

    public static function RemCurvesCharacters($str)
    {

        $ar = ["'", "\"", "{", "}", "[", "]", "(", ")", "."];
        return trim(str_replace($ar, "", $str));
    }

    public static function addsearchSomeCondition($mname, $where_filters = '', $select = [], $type_time = false)
    {

        if (isset($type_time['type'])) {
            $andwheredate = $this->addDateSomeCondition($mname, $type_time);
            if (isset($andwheredate['noescape'])) {
                $where_filters .= (empty($where_filters)) ? $andwheredate['noescape'] : ' AND ' . $andwheredate['noescape'] . ' ';
            };
        }
        foreach ($select as $value) {

            if (empty($value)) {
                continue;
            }

            $userdata = \Session::get("s_" . $value . "_" . $mname);
            $andwhere = null;

            if (is_array($userdata) && count($userdata)) {
                $userdata = addslashes(implode(',', $userdata));
                $andwhere = '`' . addslashes($value) . "` IN ('" . str_replace(",", "','", $userdata) . "') ";
            } elseif (is_string($userdata) && !empty($userdata)) {
                $andwhere = '`' . addslashes($value) . "` LIKE  '%" . $userdata . "%'";
            }

            if (!empty($andwhere)) {
                $where_filters .= (empty($where_filters)) ? $andwhere : ' AND ' . $andwhere . ' ';
            }
        }
        return $where_filters;
    }

    public static function addDateSomeCondition($model, $type_time, $filters_array = false)
    {

        $time          = \Session::get('time_' . $model);
        $array_filters = array();

        if (!isset($type_time['type']) or !isset($type_time['field'])) {
            dd("Используете  переменную в формате array('type'=>'TIMESTAMP','field'=>'date')");
            return redirect()->back()->with("warning', 'Используете  переменную в формате array('type'=>'TIMESTAMP','field'=>'date')");
        }

        if (!empty($time['from']) && !empty($time['to'])) {

            if ($type_time['type'] === "unixtime") {
                $array_filters['noescape'] = " ( `" . $type_time['field'] . "` > '" . $time['ufrom'] . "' AND `" . $type_time['field'] . "` < '" . $time['uto'] . "') ";
            } elseif ($type_time['type'] === "TIMESTAMP") {
                //$array_filters['noescape'] = " ( " . $type_time['type'] . "(" . $type_time['field'] . ") BETWEEN '" . $time['sfrom'] . "' AND '" . $time['sto'] . "' ) ";
                $array_filters['noescape'] = " ( `" . $type_time['field'] . "` > '" . $time['sfrom'] . "' AND `" . $type_time['field'] . "` < '" . $time['sto'] . "') ";
            } elseif (in_array($type_time['type'], ["DATETIME", "DATE", "TIME"])) {
                $array_filters['noescape'] = " ( " . $type_time['type'] . "(" . $type_time['field'] . ") BETWEEN '" . $time['sfrom'] . "' AND '" . $time['sto'] . "' ) ";
                //$array_filters['noescape'] = " ( `" . $type_time['field'] . "` > '" . $time['sfrom'] . "' AND `" . $type_time['field'] . "` < '" . $time['sto'] . "') ";

            } else {
                return null;
            }
        }
        return (is_array($filters_array)) ? $array_filters + $filters_array : $array_filters;
    }

}
