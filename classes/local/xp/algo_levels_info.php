<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Levels.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\xp;
defined('MOODLE_INTERNAL') || die();

use coding_exception;
use context;

/**
 * Levels with algorithm computation support.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class algo_levels_info implements levels_info {

    /** Default number of levels. */
    const DEFAULT_COUNT = 10;
    /** Default base for XP algo. */
    const DEFAULT_BASE = 120;
    /** Default coef for XP algo. */
    const DEFAULT_COEF = 1.3;

    /** @var array The initial data. */
    protected $data;
    /** @var int Number of levels. */
    protected $count;
    /** @var level[] The levels. */
    protected $levels;
    /** @var int Base XP */
    protected $base;
    /** @var float Coef */
    protected $coef;
    /** @var bool Used algo? */
    protected $usealgo;
    /** @var badge_url_resolver The resolver. */
    protected $resolver;

    /**
     * Constructor.
     *
     * @param array $data Array containing both keys 'xp' and 'desc'. Indexes should start at 1.
     * @param badge_url_resolver $resolver The server resolving badge URLs if any.
     */
    public function __construct(array $data, badge_url_resolver $resolver = null) {
        $this->data = $data;
        $this->base = $data['base'];
        $this->coef = $data['coef'];
        $this->usealgo = (bool) $data['usealgo'];
        $this->resolver = $resolver;
    }

    /**
     * XP Base.
     *
     * @return int
     */
    public function get_base() {
        return $this->base;
    }

    /**
     * XP coef.
     *
     * @return float
     */
    public function get_coef() {
        return $this->coef;
    }

    /**
     * Get the number of levels.
     *
     * @return int
     */
    public function get_count() {
        $this->load();
        return $this->count;
    }

    /**
     * Get the level.
     *
     * @param int $level The level as a number.
     * @return level
     */
    public function get_level($level) {
        $this->load();
        if (!isset($this->levels[$level])) {
            throw new coding_exception('Invalid level: ' . $level);
        }
        return $this->levels[$level];
    }

    /**
     * Get the level for a certain amount of experience points.
     *
     * @param int $xp The experience points.
     * @return level
     */
    public function get_level_from_xp($xp) {
        $this->load();
        for ($i = $this->get_count(); $i > 0; $i--) {
            $level = $this->levels[$i];
            if ($level->get_xp_required() <= $xp) {
                return $level;
            }
        }
        return $level;
    }

    /**
     * Get the levels.
     *
     * @return level[]
     */
    public function get_levels() {
        $this->load();
        return $this->levels;
    }

    /**
     * Whether the algo was used.
     *
     * @return bool
     */
    public function get_use_algo() {
        return $this->usealgo;
    }

    /**
     * Serialize that thing.
     *
     * @return array
     */
    public function jsonSerialize() { // @codingStandardsIgnoreLine.
        return [
            'xp' => array_map(function($level) {
                return $level->get_xp_required();
            }, $this->get_levels()),
            'name' => array_filter(array_map(function($level) {
                return $level instanceof level_with_name ? $level->get_name() : null;
            }, $this->get_levels())),
            'desc' => array_filter(array_map(function($level) {
                return $level instanceof level_with_description ? $level->get_description() : null;
            }, $this->get_levels())),
            'base' => $this->base,
            'coef' => $this->coef,
            'usealgo' => $this->usealgo,
        ];
    }

    protected function load() {
        if ($this->levels !== null) {
            return;
        }

        $data = $this->data;
        $resolver = $this->resolver;

        $this->levels = array_reduce(array_keys($data['xp']), function($carry, $key) use ($data, $resolver) {
            $level = $key;
            $desc = isset($data['desc'][$key]) ? $data['desc'][$key] : null;
            $name = isset($data['name'][$key]) ? $data['name'][$key] : null;
            if (!$resolver) {
                $obj = new described_level($level, $data['xp'][$key], $desc, $name);
            } else {
                $obj = new badged_level($level, $data['xp'][$key], $desc, $resolver, $name);
            }
            $carry[$level] = $obj;
            return $carry;
        }, []);
        $this->count = count($this->levels);
    }

    /**
     * Make levels from the defaults.
     *
     * @param context $badgecontext The context of the badges, if any.
     * @return self
     */
    public static function make_from_defaults(badge_url_resolver $resolver = null) {
        return new self([
            'usealgo' => true,
            'base' => self::DEFAULT_BASE,
            'coef' => self::DEFAULT_COEF,
            'xp' => self::get_xp_with_algo(self::DEFAULT_COUNT, self::DEFAULT_BASE, self::DEFAULT_COEF),
            'name' => [],
            'desc' => [],
        ], $resolver);
    }

    /**
     * Get the levels and their XP based on a simple algorithm.
     *
     * @param int $levelcount The number of levels.
     * @param int $base The base XP required.
     * @param float $coef The coefficient between levels.
     * @return array level => xp required.
     */
    public static function get_xp_with_algo($levelcount, $base, $coef) {
        $list = [];
        for ($i = 1; $i <= $levelcount; $i++) {
            if ($i == 1) {
                $list[$i] = 0;
            } else if ($i == 2) {
                $list[$i] = $base;
            } else {
                $list[$i] = $base + round($list[$i - 1] * $coef);
            }
        }
        return $list;
    }

}
