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

/**
 * API exception.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class api_exception extends client_exception {
    /** @var mixed The response decoded. */
    protected $responsedecoded;

    /**
     * Constructor.
     *
     * @param curl $curl The curl object post request.
     * @param string $response The response from the server.
     */
    public function __construct($curl, $response) {
        $this->responsedecoded = json_decode($response);
        parent::__construct('api_error', $curl, $response);
    }

    /**
     * Get the API error code.
     *
     * @return string
     */
    public function get_error_code() {
        return is_object($this->responsedecoded) ? $this->responsedecoded->code ?? 'UNKNOWN_ERROR' : 'UNKNOWN_ERROR';
    }

    /**
     * Get the API error message.
     *
     * @return string
     */
    public function get_error_message() {
        return is_object($this->responsedecoded) ? $this->responsedecoded->message ?? 'Unknown error' : 'Unknown error';
    }
}
