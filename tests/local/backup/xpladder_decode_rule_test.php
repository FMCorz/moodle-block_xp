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

namespace block_xp\local\backup;

use backup_controller_dbops;
use block_xp\local\shortcode\handler;
use block_xp\tests\base_testcase;
use Generator;
use restore_dbops;

/**
 * Tests.
 *
 * @package    block_xp
 * @category   test
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \block_xp\local\backup\xpladder_decode_rule
 */
final class xpladder_decode_rule_test extends base_testcase {

    /**
     * Encoding provider.
     *
     * @return Generator
     */
    public static function encode_content_provider(): Generator {
        yield ['Hello World!', 'Hello World!'];
        yield ['Hello [xpladder]', 'Hello $@BLOCKXPSHORTCODEXPLADDER*0*0*0*0@$'];
        yield ['Hello [xpladder hidelink]', 'Hello $@BLOCKXPSHORTCODEXPLADDER*0*0*1*0@$'];
        yield ['[xpladder ctx=123 secret=abc]', '$@BLOCKXPSHORTCODEXPLADDER*123*0*0*0@$'];
        yield ['[xpladder ctx=123 secret=abc top withprogress]', '$@BLOCKXPSHORTCODEXPLADDER*123*10*0*1@$'];
        yield ['[xpladder ctx=123 secret=abc top=20]', '$@BLOCKXPSHORTCODEXPLADDER*123*20*0*0@$'];
        yield ['[xpladder top=25 hidelink]', '$@BLOCKXPSHORTCODEXPLADDER*0*25*1*0@$'];
        yield [
            "[xpladder ctx=1]\n[xpladder ctx=2 top=5]",
            "$@BLOCKXPSHORTCODEXPLADDER*1*0*0*0@$\n$@BLOCKXPSHORTCODEXPLADDER*2*5*0*0@$",
        ];
    }

    /**
     * Test.
     *
     * @param string $input
     * @param string $expected
     * @dataProvider encode_content_provider
     */
    public function test_encode_content($input, $expected): void {
        global $CFG;
        if (!class_exists('filter_shortcodes\shortcodes')) {
            $this->markTestSkipped('The plugin filter_shortcodes is required.');
            return;
        }

        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
        $this->assertEquals($expected, xpladder_decode_rule::encode_content($input));
    }

    /**
     * Decoding provider.
     *
     * @return Generator
     */
    public static function decode_provider(): Generator {
        yield ['Hello World!', 'Hello World!'];
        yield ['$@BLOCKXPSHORTCODEXPLADDER*0*0*0*0@$', '[xpladder]'];
        yield ['$@BLOCKXPSHORTCODEXPLADDER*0*0*1*0@$', '[xpladder hidelink]'];
        yield ['$@BLOCKXPSHORTCODEXPLADDER*0*10*0*1@$', '[xpladder top=10 withprogress]'];
        yield ['$@BLOCKXPSHORTCODEXPLADDER*100*0*0*0@$', '[xpladder ctx=CTX200 secret=SEC200]'];
        yield ['$@BLOCKXPSHORTCODEXPLADDER*101*10*1*1@$', '[xpladder ctx=CTX201 secret=SEC201 top=10 hidelink withprogress]'];
        yield [
            "$@BLOCKXPSHORTCODEXPLADDER*100*0*0*0@$\n$@BLOCKXPSHORTCODEXPLADDER*101*5*0*0@$",
            "[xpladder ctx=CTX200 secret=SEC200]\n[xpladder ctx=CTX201 secret=SEC201 top=5]",
        ];
    }

    /**
     * Test.
     *
     * @param string $input
     * @param string $expected
     * @dataProvider decode_provider
     */
    public function test_decode($input, $expected): void {
        global $CFG;
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();
        $c1ctx = \context_course::instance($c1->id);
        $c2ctx = \context_course::instance($c2->id);

        backup_controller_dbops::create_backup_ids_temp_table('abc');
        restore_dbops::set_backup_ids_record('abc', 'context', 100, $c1ctx->id);
        restore_dbops::set_backup_ids_record('abc', 'context', 101, $c2ctx->id);

        $rule = new xpladder_decode_rule();
        $rule->set_restoreid('abc');
        $replacements = [
            'CTX200' => $c1ctx->id,
            'CTX201' => $c2ctx->id,
            'SEC200' => handler::get_xpladder_secret($c1ctx),
            'SEC201' => handler::get_xpladder_secret($c2ctx),
        ];
        $expected = str_replace(array_keys($replacements), array_values($replacements), $expected);
        $result = $rule->decode($input);

        backup_controller_dbops::drop_backup_ids_temp_table('abc');
        $this->assertEquals($expected, $result);
    }

}
