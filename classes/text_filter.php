<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace filter_htmx;

use DOMDocument;

/**
 * Filter that strips all hx-* attributes from HTML tags
 *
 * @package    filter_htmx
 * @copyright  2025 Peter Miller <pita.da.bread07@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {
    /**
     * Filter the text and remove all hx-* attributes
     *
     * @param string $text The text to filter
     * @param array $options The filter options
     * @return string The filtered text
     */
    public function filter($text, array $options = []) {
        // Return unmodified text if noclean option is specified.
        if (!empty($options['noclean'])) {
            return $text;
        }

        // Return unmodified text if trusted option is specified.
        if (!empty($options['noclean'])) {
            return $text;
        }

        if (empty($text) || is_numeric($text)) {
            return $text;
        }

        // Use DOMDocument to parse and modify the HTML
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Get all elements
        $xpath = new \DOMXPath($dom);
        $elements = $xpath->query('//*');

        // Remove all hx-* attributes from each element
        foreach ($elements as $element) {
            $attributes = [];
            foreach ($element->attributes as $attribute) {
                if (strpos($attribute->nodeName, 'hx-') === 0) {
                    $attributes[] = $attribute->nodeName;
                }
            }
            foreach ($attributes as $attribute) {
                $element->removeAttribute($attribute);
            }
        }

        $text = $dom->saveHTML($dom->documentElement);

        return $text;
    }
} 
