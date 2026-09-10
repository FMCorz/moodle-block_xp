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

use action_link;
use moodle_url;
use pix_icon;

/**
 * Navigator.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class nav_item {
    /** @var string The ID. */
    protected $id;
    /** @var string The text. */
    protected $text;
    /** @var moodle_url The URL. */
    protected $url;
    /** @var ?pix_icon The icon. */
    protected $icon;
    /** @var nav_item[] The children. */
    protected $children = [];
    /** @var bool Whether the addon is required. */
    protected bool $addonrequired = false;
    /** @var bool Whether the page needs attention. */
    protected bool $needsattention = false;

    /**
     * Constructor.
     *
     * @param string $text
     * @param moodle_url $url
     * @param string|null $id
     */
    public function __construct(string $text, moodle_url $url, ?string $id = null) {
        $this->text = $text;
        $this->url = $url;
        $this->id = $id;
    }

    /**
     * Add a child.
     *
     * @param nav_item $item
     * @return self
     */
    public function add_child(nav_item $item): self {
        $this->children[] = $item;
        return $this;
    }

    /**
     * Get the children.
     *
     * @return nav_item[]
     */
    public function get_children(): array {
        return $this->children;
    }

    /**
     * Get the icon.
     *
     * @return pix_icon|null
     */
    public function get_icon(): ?pix_icon {
        return $this->icon;
    }

    /**
     * Get the ID.
     *
     * @return string|null
     */
    public function get_id(): ?string {
        return $this->id;
    }

    /**
     * Get the text.
     *
     * @return string
     */
    public function get_text(): string {
        return $this->text;
    }

    /**
     * Get the URL.
     *
     * @return moodle_url
     */
    public function get_url(): moodle_url {
        return $this->url;
    }

    /**
     * Whether the addon is required.
     *
     * @return bool
     */
    public function is_addon_required(): bool {
        return $this->addonrequired;
    }

    /**
     * Whether the page needs attention.
     *
     * @return bool
     */
    public function needs_attention(): bool {
        return $this->needsattention;
    }

    /**
     * Set addon required.
     *
     * @param bool $addonrequired
     * @return self
     */
    public function set_addon_required(bool $addonrequired): self {
        $this->addonrequired = $addonrequired;
        return $this;
    }

    /**
     * Set needs attention.
     *
     * @param bool $needsattention
     * @return self
     */
    public function set_needs_attention(bool $needsattention): self {
        $this->needsattention = $needsattention;
        return $this;
    }

    /**
     * Set children.
     *
     * @param nav_item[] $items
     * @return self
     */
    public function set_children(array $items): self {
        $this->children = array_values($items);
        return $this;
    }

    /**
     * Set the icon.
     *
     * @param pix_icon|null $icon
     * @return self
     */
    public function set_icon(?pix_icon $icon): self {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Return as array.
     *
     * @return array
     */
    public function as_array() {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'text' => $this->text,
            'icon' => $this->icon,
            'addonrequired' => $this->addonrequired,
            'needsattention' => $this->needsattention,
            'children' => array_values(array_map(
                static function (nav_item $child): array {
                    return $child->as_array();
                },
                $this->children
            )),
        ];
    }

    /**
     * Return as action_link.
     *
     * @return action_link
     */
    public function as_action_link() {
        return new action_link($this->url, $this->text, null, null, $this->icon);
    }
}
