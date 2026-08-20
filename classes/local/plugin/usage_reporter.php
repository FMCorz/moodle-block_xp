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

/**
 * Usage reporter.
 *
 * @package    block_xp
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\plugin;

use block_xp\di;
use block_xp\local\config\config;
use block_xp\local\http\api_client;
use block_xp\local\http\client_exception;

/**
 * Usage reporter class.
 *
 * @package    block_xp
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class usage_reporter {
    /** @var api_client The API client. */
    protected $client;
    /** @var config The config. */
    protected $config;
    /** @var usage_report_maker The maker. */
    protected $maker;

    /**
     * Constructor.
     *
     * @param config $config The config.
     * @param usage_report_maker $maker The usage report maker.
     * @param api_client|null $client The API client.
     */
    public function __construct(config $config, usage_report_maker $maker, ?api_client $client = null) {
        $this->config = $config;
        $this->maker = $maker;
        $this->client = $client ?? di::get('api_client');
    }

    /**
     * Make usage report.
     *
     * @return object Where keys represent usage.
     * @return bool Whether successful or not.
     */
    public function report() {
        $usage = $this->maker->make();

        $localsiteid = $this->config->get('usagereportid');
        if (!empty($localsiteid)) {
            $usage->local_site_id = $localsiteid;
        }

        try {
            $response = $this->client->post('/v1/xp/usage', $usage);
        } catch (client_exception $e) {
            return false;
        }

        $this->config->set('lastusagereport', di::get('clock')->time());
        $respdata = $response->data;
        if ($respdata && !empty($respdata->local_site_id) && $respdata->local_site_id !== $localsiteid) {
            $this->config->set('usagereportid', $respdata->local_site_id);
        }
        return true;
    }
}
