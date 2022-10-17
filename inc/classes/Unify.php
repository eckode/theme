<?php

declare(strict_types=1);

namespace Eckode;

use stdClass;

class Unify
{

    protected const CONTENT_MODEL_BASE = [
        'id'            => 0,
        'path'          => '',
        'content'       => '',
        'context'       => '',
        'context_value' => '',
        'props'         => [],
    ];

    protected const SINGLE_MAPPING = [
        'id'            => ['ID', 'id'],
        'path'          => 'get_permalink',
        'title'         => ['post_title', ['title', 'rendered']],
        'content'       => ['post_content', ['content', 'rendered']],
        'context'       => 'single',
        'context_value' => 'post_type',
    ];

    protected const TAXONOMY_ARCHIVE_MAPPING = [
        'id'            => 'term_id',
        'path'          => 'get_term_link',
        'title'         => 'name',
        'content'       => 'description',
        'context'       => 'taxonomy_archive',
        'context_value' => 'taxonomy',
    ];

    protected const POST_TYPE_ARCHIVE_MAPPING = [
        'id'            => 0,
        'path'          => 'get_post_type_archive_link',
        'title'         => 'label',
        'content'       => 'description',
        'context'       => 'post_type_archive',
        'context_value' => 'name',
    ];

    /**
     * Home pages require two variants depending on what the settings are 
     * under Settings > Reading > Your homepage displays
     */
    protected const HOME_MAPPING = [
        'id'            => [0, 'ID'],
        'path'          => '/',
        'title'         => ['', 'post_title'],
        'content'       => 'description',
        'context'       => ['home', 'single'],
        'context_value' => ['latest_posts', 'page'],
    ];

    protected const NOT_FOUND_MAPPING = [
        'id'            => 0,
        'path'          => '',
        'title'         => '',
        'content'       => '',
        'context'       => 'not_found',
        'context_value' => '',
    ];

    private array $item;

    private array $mapping;

    private bool $rest;

    private $mapping_variant;

    protected static string $date_format;

    protected string $permalink;

    public function __construct($item, bool $rest)
    {
        $this->item = (array) $item;
        $this->rest = $rest;
        static::$date_format ??= get_option('date_format');
    }

    public final static function configure($item, $rest = false)
    {
        return new Unify($item, $rest);
    }

    public function add_context(string $context = null, string $context_value = null, int $mapping_variant = null): Unify
    {
        if (null !== $context && null !== $context_value) {
            $this->mapping = constant('static::' . strtoupper($context) . '_MAPPING');
            $this->mapping['context_value'] = $context_value;
            $this->mapping_variant = $mapping_variant ?? $this->rest ? 1 : null;
            return $this;
        }
        if (is_front_page()) {
            $this->mapping         = static::HOME_MAPPING;
            $this->mapping_variant = 0;
            if (!is_home()) {
                // If static home page is selected, use the 2nd variant.
                $this->mapping_variant = 1;
            }
        } else if (is_archive()) {
            if (is_tag() || is_tax() || is_category()) {
                $this->mapping = static::TAXONOMY_ARCHIVE_MAPPING;
            } else if (is_post_type_archive()) {
                $this->mapping = static::POST_TYPE_ARCHIVE_MAPPING;
            }
        } else if (is_single() || is_singular()) {
            $this->mapping = static::SINGLE_MAPPING;
        } else if (is_404()) {
            $this->mapping = static::NOT_FOUND_MAPPING;
        }

        if (!isset($this->mapping_variant) && $this->rest) {
            $this->mapping_variant = 1;
        }

        return $this;
    }

    public function add_props(array $props): Unify
    {
        if (!isset($this->mapping)) {
            $this->add_context();
        }
        if (empty($props)) {
            return $this;
        }

        $this->mapping['props'] = [];
        foreach ($props as $prop) {
            $value = '';
            switch ($prop) {
                case 'excerpt':
                    $value = strip_tags(
                        $this->item['excerpt']['rendered'] ?? // Rest requests
                            $this->item['post_excerpt'] ?? // Rest requests
                            $this->item['description'] ?? // Nav menu items
                            ''
                    );
                    break;
                case 'date':
                    $value = date(static::$date_format, strtotime($this->item['date']));
                    break;
                case 'comments':
                    $value = $this->item['comment_status'];
                    break;
                case 'image':
                    $value = new stdClass();
                    if (($attachment_id = $this->item['featured_media'] ?? 0) > 0) {
                        $featured_image     = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                        $featured_image_med = wp_get_attachment_image_url($attachment_id, 'medium');
                        $featured_image_lrg = wp_get_attachment_image_url($attachment_id, 'large');
                        $value = [
                            'tn'  => ltrim(wp_make_link_relative($featured_image), '/wp-content/uploads'),
                            'med' => ltrim(wp_make_link_relative($featured_image_med), '/wp-content/uploads'),
                            'lrg' => ltrim(wp_make_link_relative($featured_image_lrg), '/wp-content/uploads'),
                        ];
                    }
                case 'post_image':
                    $value = new stdClass();
                    if (($attachment_id = $this->item['ID'] ?? 0) > 0) {
                        $featured_image     = get_the_post_thumbnail_url($this->item['ID'], 'thumbnail');
                        $featured_image_med = get_the_post_thumbnail_url($this->item['ID'], 'medium');
                        $featured_image_lrg = get_the_post_thumbnail_url($this->item['ID'], 'large');
                        $value = [
                            'tn'  => ltrim(wp_make_link_relative($featured_image), '/wp-content/uploads'),
                            'med' => ltrim(wp_make_link_relative($featured_image_med), '/wp-content/uploads'),
                            'lrg' => ltrim(wp_make_link_relative($featured_image_lrg), '/wp-content/uploads'),
                        ];
                    }
                    break;
            }
            $this->mapping['props'][$prop] = $value;
        }

        return $this;
    }

    public function get_path(string $callback, int $id): string
    {
        $link = '';
        if (is_callable($callback)) {
            $link = call_user_func_array($callback, [$id]);
        }
        return trim(wp_make_link_relative($link), '/');
    }

    public function additional_props(callable $callback): Unify
    {
        if (is_callable($callback)) {
            $this->mapping['props'] = wp_parse_args(call_user_func_array($callback, [$this->item]), $this->mapping['props'] ?? []);
        }
        return $this;
    }

    /**
     * Map values from items
     * 
     * @return array 
     */
    public function map(): array
    {
        if (!isset($this->mapping)) {
            $this->add_context();
        }
        $content = [];
        $items   = $this->mapping;
        foreach ($items as $key => $value) {
            if ('props' === $key || 'path' === $key) {
                $content[$key] = $value;
                continue;
            }
            if (in_array($key, ['context', 'context_value'], true)) {
                $content[$key] = $value;
                if (is_array($value)) {
                    $content[$key] = $value[$this->mapping_variant];
                }
                // Archives require different treatment when assigning context_value, they still need to be mapped...
                if (in_array($this->mapping['context'], ['taxonomy_archive', 'post_type_archive'], true) && 'context_value' === $key) {
                    $content[$key] = $this->item[$value];
                }
                continue;
            }
            if (is_array($value)) {
                // Is home or rest is true
                if (isset($this->mapping_variant)) {
                    // Multiple options
                    if (is_array($value[$this->mapping_variant])) {
                        $content[$key] = array_reduce($value[$this->mapping_variant], function ($curry, $key) {
                            return $curry[$key];
                        }, $this->item);
                        continue;
                    }
                    $content[$key] = $this->item[$value[$this->mapping_variant]] ?? $value[$this->mapping_variant];
                } else {
                    // Nested
                    if (!is_array($value[0])) {
                        $content[$key] = $this->item[$value[0]];
                        continue;
                    }
                    $content[$key] = array_reduce($value[0], function ($curry, $key) {
                        return $curry[$key];
                    }, $this->item);
                }
                continue;
            }
            $content[$key] = $this->item[$value] ?? $value;
        }

        $content['path'] = $this->get_path($content['path'], $content['id']);

        return $content;
    }

    public function override_mapping(array $overrides): Unify
    {
        foreach ($overrides as $key => $target) {
            $this->mapping[$key] = $target;
        }
        return $this;
    }
}
