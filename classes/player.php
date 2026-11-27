<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace smarturlplayer_youtube;

use mod_smarturl\local\url\player as player_contract;
use mod_smarturl\local\url\resource_target;

/**
 * YouTube player controls for Smart URL.
 *
 * @package    smarturlplayer_youtube
 * @copyright  2026 Didactika.org
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class player implements player_contract {
    /**
     * Adds playback preferences to the privacy-enhanced embed.
     *
     * @param string $url Resource URL.
     * @return array|null
     */
    public function describe(string $url): ?array {
        global $CFG;
        $target = resource_target::youtube($url);
        if (!$target) {
            return null;
        }
        $embed = new \moodle_url($target['embedurl'], [
            'playsinline' => 1,
            'controls' => get_config('smarturlplayer_youtube', 'hidecontrols') ? 0 : 1,
            'cc_load_policy' => get_config('smarturlplayer_youtube', 'captions') ? 1 : 0,
            'origin' => (new \moodle_url($CFG->wwwroot))->get_scheme() . '://' . parse_url($CFG->wwwroot, PHP_URL_HOST),
        ]);
        $target['embedurl'] = $embed->out(false);
        return $target;
    }
}
