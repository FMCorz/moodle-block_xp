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

namespace block_xp\local\navigation;

use block_xp\di;
use block_xp\local\controller\promo_controller;
use block_xp\local\course_world;
use block_xp\local\permission\access_logs_permissions;
use block_xp\local\permission\access_report_permissions;
use block_xp\local\routing\url;
use block_xp\local\routing\url_resolver;
use block_xp\local\utils\world_utils;
use pix_icon;

/**
 * Course world navigator.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_world_navigator extends navigator {
    /** @var course_world The world. */
    protected course_world $world;
    /** @var url_resolver The URL resolver. */
    protected url_resolver $urlresolver;

    /**
     * Constructor.
     *
     * @param course_world $world The world.
     * @param url_resolver $resolver The URL resolver.
     */
    public function __construct(course_world $world, url_resolver $resolver) {
        $this->world = $world;
        $this->urlresolver = $resolver;
    }

    /**
     * Get the block navigation.
     *
     * @return nav_item[]
     */
    public function get_block_navigation(): array {
        $accessperms = $this->world->get_access_permissions();
        $canedit = $accessperms->can_manage();
        $canaccessreport = $accessperms instanceof access_report_permissions && $accessperms->can_access_report();
        $config = $this->world->get_config();
        $links = [];

        if ($config->get('enableinfos')) {
            $links[] = (new nav_item(get_string('navinfos', 'block_xp'), $this->get_url('infos'), 'infos'))
                ->set_icon(new pix_icon('i/info', '', 'block_xp'));
        }
        if ($config->get('enableladder')) {
            $links[] = (new nav_item(get_string('navladder', 'block_xp'), $this->get_url('ladder'), 'ladder'))
                ->set_icon(new pix_icon('i/ladder', '', 'block_xp'));
        }
        if ($canaccessreport) {
            $links[] = (new nav_item(get_string('navreport', 'block_xp'), $this->get_url('report'), 'report'))
                ->set_icon(new pix_icon('i/report', '', 'block_xp'));
        }
        if ($canedit) {
            $links[] = (new nav_item(get_string('navsettings', 'block_xp'), $this->get_url('config'), 'config'))
                ->set_icon(new pix_icon('i/settings', '', 'block_xp'));
        }

        return $links;
    }

    /**
     * Get the navigation.
     *
     * @return nav_item[]
     */
    public function get_navigation(): array {
        $links = [];
        $accessperms = $this->world->get_access_permissions();
        $hasaddon = di::get('addon')->is_activated();
        $showpromo = di::get('addon')->is_promo_allowed();
        $config = $this->world->get_config();
        $canmanage = $accessperms->can_manage();
        $supportspointchanges = world_utils::supports_local_points_management($this->world);
        $supportsteamladder = world_utils::supports_team_leaderboard($this->world);

        if ($config->get('enableinfos') || $canmanage) {
            $links[] = new nav_item(get_string('navinfos', 'block_xp'), $this->get_url('infos'), 'infos');
        }

        $canviewladder = $config->get('enableladder') || $canmanage;
        $isteamladderenabled = $config->has('enablegroupladder') && (bool) $config->get('enablegroupladder');
        $canviewteamladder = $supportsteamladder && ($isteamladderenabled || ($canmanage && ($showpromo || $hasaddon)));
        if ($canviewladder || $canviewteamladder) {
            $mainurl = $this->get_url($canviewladder ? 'ladder' : 'group_ladder');
            $laddernav = new nav_item(get_string('navladder', 'block_xp'), $mainurl, 'ladder');
            if ($canviewladder) {
                $laddernav->add_child(new nav_item(
                    get_string('participants', 'block_xp'),
                    $this->get_url('ladder'),
                    'ladder'
                ));
            }
            if ($canviewteamladder) {
                $laddernav->add_child((new nav_item(
                    get_string('teams', 'block_xp'),
                    $this->get_url('group_ladder'),
                    'group_ladder'
                ))->set_addon_required(!$hasaddon));
            }
            $links[] = $laddernav;
        }

        $canviewlogs = $accessperms instanceof access_logs_permissions && $accessperms->can_access_logs();
        $canviewreport = $accessperms instanceof access_report_permissions && $accessperms->can_access_report();
        if ($canviewreport || $canviewlogs) {
            // The link is always called report, but leads to the logs if we can't view the report.
            $mainurl = $this->get_url($canviewreport ? 'report' : 'log');
            $reportnav = new nav_item(get_string('navreport', 'block_xp'), $mainurl, 'report');
            if ($canviewreport) {
                $reportnav->add_child(new nav_item(
                    get_string('navreport', 'block_xp'),
                    $this->get_url('report'),
                    'report'
                ));
            }
            if ($canviewlogs) {
                $reportnav->add_child(new nav_item(get_string('navlog', 'block_xp'), $this->get_url('log'), 'log'));
            }
            $links[] = $reportnav;
        }

        if ($canmanage) {
            $links[] = (new nav_item(
                get_string('navlevels', 'block_xp'),
                $this->get_url('levels'),
                'levels'
            ))->set_children([
                new nav_item(get_string('navlevelssetup', 'block_xp'), $this->get_url('levels'), 'levels'),
                new nav_item(get_string('navvisuals', 'block_xp'), $this->get_url('visuals'), 'visuals'),
            ]);

            if ($supportspointchanges) {
                $links[] = (new nav_item(
                    get_string('navpoints', 'block_xp'),
                    $this->get_url('actionrules'),
                    'rules'
                ))->set_children(array_filter([
                    new nav_item(get_string('navactionrules', 'block_xp'), $this->get_url('actionrules'), 'actionrules'),
                    $showpromo || $hasaddon ? (new nav_item(
                        get_string('navcompletionrules', 'block_xp'),
                        $this->get_url('completionrules'),
                        'completionrules'
                    ))->set_addon_required(!$hasaddon) : null,
                    new nav_item(get_string('naveventrules', 'block_xp'), $this->get_url('rules'), 'rules'),
                    $showpromo || $hasaddon ? (new nav_item(
                        get_string('navgraderules', 'block_xp'),
                        $this->get_url('graderules'),
                        'graderules'
                    ))->set_addon_required(!$hasaddon) : null,
                    $showpromo || $hasaddon ? (new nav_item(
                        get_string('navdrops', 'block_xp'),
                        $this->get_url('drops'),
                        'drops'
                    ))->set_addon_required(!$hasaddon) : null,
                    $showpromo || $hasaddon ? (new nav_item(
                        get_string('navimport', 'block_xp'),
                        $this->get_url('import'),
                        'import'
                    ))->set_addon_required(!$hasaddon) : null,
                ]));
            }

            if ($showpromo || $hasaddon) {
                $links[] = new nav_item(get_string('navai', 'block_xp'), $this->get_url('ai'), 'ai');
            }

            $links[] = new nav_item(get_string('navsettings', 'block_xp'), $this->get_url('config'), 'config');

            if (promo_controller::is_visible()) {
                $links[] = (new nav_item(get_string('navpromo', 'block_xp'), $this->get_url('promo'), 'promo'))
                    ->set_icon($hasaddon ? null : new pix_icon('star', '', 'block_xp', ['class' => 'icon']))
                    ->set_needs_attention(promo_controller::has_new_content());
            }
        }

        return $links;
    }

    /**
     * Get a URL.
     *
     * @param string $routename The route name.
     * @param array $params The route params, if any.
     * @return url
     */
    public function get_url(string $routename, array $params = []): url {
        $params['courseid'] = $this->world->get_courseid();
        return $this->urlresolver->reverse($routename, $params);
    }
}
