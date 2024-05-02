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

namespace block_xp\local\compat;

/**
 * Clock.
 *
 * This is a system_clock like interface to use until we can rely fully on the core clock
 * introduced in Moodle 4.4. Until then, this will be available via our DI container. The
 * purpose of this clock is to facilitate testing, and should not be used for anything else
 * but reading the current time.
 *
 * We do not implement any interface here because we do not want to introduce potential
 * incompatibilities with core classes, or make it more complicated for us by having to
 * use both the core clock and ours.
 *
 * Both methods here should only be used with Moodle 4.1, 4.2 and 4.3 as our dependncy
 * injection layer will never instantiate this class so long as the core clock exists.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class clock {

    /**
     * Return the current time.
     *
     * @return \DateTimeImmutable
     */
    public function now(): \DateTimeImmutable {
        return new \DateTimeImmutable('now', \core_date::get_server_timezone_object());
    }

    /**
     * Return the current time as a timestamp.
     *
     * @return int
     */
    public function time(): int {
        return $this->now()->getTimestamp();
    }

}
