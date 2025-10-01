<?php
// This file is part of Level Up XP.
//
// Level Up XP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP.  If not, see <https://www.gnu.org/licenses/>.
//
// https://levelup.plus

namespace block_xp\local\config;

use moodle_database;

/**
 * Table setter config.
 *
 * This sets value broadly across a table but does not support reads.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class table_setter_config implements config {

    /** @var array The columns. */
    protected $columns;
    /** @var moodle_database The DB. */
    protected $db;
    /** @var string The table name. */
    protected $tablename;
    /** @var array Reserved keys. */
    protected $reservedkeys = ['id', 'courseid', 'contextid'];
    /** @var string SQL WHERE clause. */
    protected $wheresql;
    /** @var array SQL WHERE parameters. */
    protected $whereparams;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param string $tablename The table name.
     * @param string $wheresql Optional WHERE SQL clause to limit the rows affected.
     * @param array $whereparams Optional WHERE parameters.
     */
    public function __construct(moodle_database $db, $tablename, $wheresql = '1=1', $whereparams = []) {
        $this->db = $db;
        $this->tablename = $tablename;
        $this->wheresql = $wheresql;
        $this->whereparams = $whereparams;
    }

    public function get($name) {
        return null;
    }

    public function get_all() {
        return [];
    }

    protected function get_columns() {
        if (!isset($this->columns)) {
            $this->columns = array_flip(array_map(function ($col) {
                return $col->name;
            }, $this->db->get_columns($this->tablename)));
        }
        return $this->columns;
    }

    public function has($name) {
        if (in_array($name, $this->reservedkeys)) {
            return false;
        }
        return array_key_exists($name, $this->get_columns());
    }

    public function set($name, $value) {
        if (!$this->has($name)) {
            throw new \coding_exception('Invalid config name: ' . $name);
        }
        $this->db->set_field_select($this->tablename, $name, $value, $this->wheresql, $this->whereparams);
    }

    public function set_many(array $values) {
        foreach ($values as $name => $value) {
            $this->set($name, $value);
        }
    }

}
