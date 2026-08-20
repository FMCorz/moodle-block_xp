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
// See <https://levelup.plus>.

namespace block_xp\local\http;

use core\files\curl_security_helper;

/**
 * Host only.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class host_only extends curl_security_helper {
    /** @var string */
    protected $host;
    /** @var int */
    protected $port = 443;

    /**
     * Constructor.
     *
     * @param string $host The host, optionally including the port.
     */
    public function __construct($host) {
        $port = null;
        if (strpos($host, ':') !== false) {
            [$host, $port] = explode(':', $host, 2);
        }
        $this->host = $host;
        $this->port = $port ?? $this->port;
    }

    /**
     * Whether security is enabled.
     *
     * @return bool
     */
    public function is_enabled() {
        return true;
    }

    /**
     * Whether the host is blocked.
     *
     * @param string $host The host.
     * @return bool
     */
    protected function host_is_blocked($host) {
        return $host !== $this->host;
    }

    /**
     * Whether the port is blocked.
     *
     * @param int $port The port.
     * @return bool
     */
    protected function port_is_blocked($port) {
        return $port != $this->port;
    }
}
