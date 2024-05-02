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

namespace block_xp\local\ruletype;

/**
 * Repeat option.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repeat_option {

    /** @var int The scope value. */
    protected $value;
    /** @var lang_string|string|null The once label. */
    protected $oncelabel;
    /** @var bool $worksincm Whether this works in a module. */
    protected $worksincm = true;

    /**
     * Constructor.
     *
     * Intentionally left almost empty to support future changes.
     *
     * @param int $value The scope value.
     */
    public function __construct(int $value) {
        $this->value = $value;
    }

    /**
     * Get the label.
     *
     * @return string
     */
    public function get_once_label(): string {
        return (string) ($this->oncelabel ?? get_string('unknown', 'block_xp'));
    }

    /**
     * Get the scope value.
     *
     * @return int
     */
    public function get_value(): int {
        return $this->value;
    }

    /**
     * Whether this is compatible inside a module.
     *
     * @return bool
     */
    public function is_compatible_inside_cm(): bool {
        return $this->worksincm;
    }

    /**
     * Set as incompatible inside a module.
     *
     * @return self
     */
    public function set_incompatible_inside_cm(): self {
        $this->worksincm = false;
        return $this;
    }

    /**
     * Set the once label.
     *
     * @param lang_string|string $oncelabel The once label.
     * @return self
     */
    public function set_once_label($oncelabel): self {
        $this->oncelabel = $oncelabel;
        return $this;
    }

}
