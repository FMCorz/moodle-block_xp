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
use moodle_url;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * API client.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class api_client {
    /** @var string The API root. */
    protected $apiroot;
    /** @var \core\files\curl_security_helper */
    protected $curlsecurity;

    /**
     * Constructor.
     *
     * @param string $apiroot The API root.
     */
    public function __construct(string $apiroot) {
        $this->apiroot = rtrim($apiroot, '/');

        $url = new moodle_url($this->apiroot);
        $host = $url->get_host();
        $port = $url->get_port();
        if (!$port && $url->get_scheme() === 'http') {
            $port = 80;
        }
        $this->curlsecurity = new host_only($host . ($port ? ':' . $port : ''));
    }

    /**
     * Delete.
     *
     * @param string $uri The URI.
     * @param array|object|null $params The params.
     */
    public function delete($uri, $params = null) {
        return $this->request('DELETE', $uri, $params);
    }

    /**
     * Head.
     *
     * @param string $uri The URI.
     * @param array|object|null $params The params.
     */
    public function head($uri, $params = null) {
        return $this->request('HEAD', $uri, $params);
    }

    /**
     * Get.
     *
     * @param string $uri The URI.
     * @param array|object|null $params The params.
     */
    public function get($uri, $params = null) {
        return $this->request('GET', $uri, $params);
    }

    /**
     * Patch.
     *
     * @param string $uri The URI.
     * @param array|object|null $data The data.
     */
    public function patch($uri, $data = null) {
        return $this->request('PATCH', $uri, $data);
    }

    /**
     * Post.
     *
     * @param string $uri The URI.
     * @param array|object|null $data The data.
     */
    public function post($uri, $data = null) {
        return $this->request('POST', $uri, $data);
    }

    /**
     * Put.
     *
     * @param string $uri The URI.
     * @param array|object|null $data The data.
     */
    public function put($uri, $data = null) {
        return $this->request('PUT', $uri, $data);
    }

    /**
     * Request.
     *
     * @param string $method The method.
     * @param string $uri The URI.
     * @param array|object|null $data The data.
     */
    protected function request($method, $uri, $data = null) {
        $method = strtoupper($method);

        $curl = new curl();
        $curl->set_security($this->curlsecurity);
        $curl->setHeader('Content-Type: application/json');

        if ($method === 'POST') {
            $url = new moodle_url($this->apiroot . $uri);
            $response = $curl->post($url, $data ? json_encode($data) : '');
        } else if ($method === 'PATCH') {
            $url = new moodle_url($this->apiroot . $uri);
            $response = $curl->patch($url, $data ? json_encode($data) : '');
        } else if ($method === 'PUT') {
            $url = new moodle_url($this->apiroot . $uri);
            $response = $curl->put($url, $data ? json_encode($data) : '');
        } else if ($method === 'GET') {
            $url = new moodle_url($this->apiroot . $uri, (array) $data);
            $response = $curl->get($url->out(false));
        } else if ($method === 'HEAD') {
            $url = new moodle_url($this->apiroot . $uri, (array) $data);
            $response = $curl->head($url->out(false));
        } else if ($method === 'DELETE') {
            $url = new moodle_url($this->apiroot . $uri, (array) $data);
            $response = $curl->delete($url->out(false));
        } else {
            throw new client_exception('invalid_http_verb');
        }

        if ($curl->error) {
            throw new client_exception('request_error', $curl, $response);
        } else if ($curl->info['http_code'] >= 300) {
            throw new api_exception($curl, $response);
        }

        $headers = $curl->getResponse();
        $data = null;
        if ($curl->info['http_code'] !== 204) {
            $contenttype = $headers['Content-Type'] ?? $headers['content-type'] ?? '';
            if (!$contenttype || $contenttype === 'application/json') {
                $data = json_decode($response);
                if ($data === null) {
                    throw new client_exception('cannot_decode_response', $curl, $response);
                }
            }
        }

        return (object) [
            'curl' => $curl,
            'data' => $data,
            'response' => $response,
            'http_code' => $curl->info['http_code'],
            'headers' => $headers,
        ];
    }
}
