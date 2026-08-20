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

use curl;
use moodle_exception;

/**
 * Client exception.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class client_exception extends moodle_exception {
    /** @var curl|null The curl. */
    protected $curl;
    /** @var string|null The response, if any. */
    protected $response;

    /**
     * Constructor.
     *
     * @param string $error The error code.
     * @param curl|null $curl The curl object post request.
     * @param string|null $response The response from the server.
     */
    public function __construct($error, $curl = null, $response = null) {
        $this->curl = $curl;
        $this->response = $response;
        $debuginfo = json_encode([
            'info' => $curl ? $curl->info : '',
            'errno' => $curl ? $curl->errno : '',
            'error' => $curl ? $curl->error : '',
            'response' => $curl ? $response : '',
        ]);
        parent::__construct($error, 'block_xp', '', null, $debuginfo);
    }

    /**
     * Get the HTTP code.
     *
     * @return int Zero when unknown.
     */
    public function get_http_code() {
        return ($this->curl && !empty($this->curl->info['http_code'])) ? $this->curl->info['http_code'] : 0;
    }
}
