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

namespace block_xp\local\ruletype\profile;

/**
 * Rule type profile.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile {

    /** Subject course module. */
    const SUBJECT_CM = 'cm';
    /** Subject section. */
    const SUBJECT_SECTION = 'section';
    /** Subject course. */
    const SUBJECT_COURSE = 'course';

    /** @var string|null The subject. */
    protected $subject;
    /** @var string|null The course module type. */
    protected $cmtype;
    /** @var bool Whether completion must be enabled. */
    protected $requirescompletionenabled = false;

    /**
     * Get the subject.
     *
     * The subject describes what the action is primarily about.
     *
     * @return string|null One of the SUBJECT_* constants, or null.
     */
    public function get_subject(): ?string {
        return $this->subject;
    }

    /**
     * Get the course module type.
     *
     * @return string|null The module frankenstyle name, e.g. 'forum', 'quiz'.
     */
    public function get_cm_type(): ?string {
        return $this->cmtype;
    }

    /**
     * Whether completion must be enabled on the target module.
     *
     * Only meaningful when the subject is SUBJECT_MODULE.
     *
     * @return bool
     */
    public function get_requires_completion_enabled(): bool {
        return $this->requirescompletionenabled;
    }

    /**
     * Set the course module type.
     *
     * @param string|null $cmtype The module type.
     * @return self
     */
    public function set_cm_type(?string $cmtype): self {
        $this->cmtype = $cmtype;
        return $this;
    }

    /**
     * Set whether completion must be enabled.
     *
     * @param bool $requirescompletionenabled Whether completion is required.
     * @return self
     */
    public function set_requires_completion_enabled(bool $requirescompletionenabled): self {
        $this->requirescompletionenabled = $requirescompletionenabled;
        return $this;
    }

    /**
     * Set the subject.
     *
     * @param string|null $subject One of the SUBJECT_* constants, or null.
     * @return self
     */
    public function set_subject(?string $subject): self {
        $this->subject = $subject;
        return $this;
    }
}
