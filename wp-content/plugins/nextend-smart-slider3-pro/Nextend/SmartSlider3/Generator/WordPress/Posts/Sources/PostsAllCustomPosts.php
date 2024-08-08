<?php

namespace Nextend\SmartSlider3\Generator\WordPress\Posts\Sources;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MixedField\GeneratorOrder;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Filter;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsAllTaxonomies;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsMetaKeys;
use Nextend\SmartSlider3\Generator\WordPress\Posts\Elements\PostsPostTypes;
use Nextend\SmartSlider3\Generator\WordPress\Posts\GeneratorGroupPosts;


class PostsAllCustomPosts extends AbstractGenerator {

    protected $layout = 'article';

    public function getDescription() {
        return n2_('Creates slides from the posts of all your post types.');
    }

    public function renderFields($container) {
        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));
        $filter      = $filterGroup->createRow('filter');

        new PostsPostTypes($filter, 'posttypes', n2_('Post types'), 0, array(
            'isMultiple' => true
        ));

        new PostsAllTaxonomies($filter, 'taxonomies', n2_('Taxonomies'), 0, array(
            'postSeparator' => '|*|'
        ));

        new Textarea($filter, 'ids', n2_('Post IDs to display'), '', array(
            'width'          => 280,
            'height'         => 160,
            'tipLabel'       => n2_('Post IDs to display'),
            'tipDescription' => sprintf(n2_('You can make your generator display only the posts with the set ID. No other post will be fetched, even if they match the set filters. %1$s Write one ID per line.'), '<br>')
        ));

        $posts = $filterGroup->createRow('posts');
        new Filter($posts, 'poststicky', n2_('Sticky'), 0);
        $statuses = get_post_stati();
        $statuses += array(
            'any'   => 'any',
            'unset' => 'unset',
        );
        new Select($posts, 'poststatus', n2_('Post status'), 'publish', array(
            'options' => $statuses
        ));


        $postMetaGroup = $filterGroup->createRowGroup('postmetaGroup', n2_('Post meta comparison'));
        $postMeta      = $postMetaGroup->createRow('postmeta');
        new PostsMetaKeys($postMeta, 'postmetakey', n2_('Field name'), 0, array(
            'tipLabel'       => n2_('Field name'),
            'tipDescription' => n2_('Only show posts, where the given meta key is equal to the given meta value.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1900-wordpress-custom-posts-generator#post-meta-comparison'
        ));

        new Select($postMeta, 'postmetacompare', n2_('Compare method'), '=', array(
            'options' => array(
                '='           => '=',
                '!='          => '!=',
                '>'           => '>',
                '>='          => '>=',
                '<'           => '<',
                '<='          => '<=',
                'LIKE'        => 'LIKE',
                'NOT LIKE'    => 'NOT LIKE',
                'IN'          => 'IN',
                'NOT IN'      => 'NOT IN',
                'BETWEEN'     => 'BETWEEN',
                'NOT BETWEEN' => 'NOT BETWEEN',
                'REGEXP'      => 'REGEXP',
                'NOT REGEXP'  => 'NOT REGEXP',
                'RLIKE'       => 'RLIKE',
                'EXISTS'      => 'EXISTS',
                'NOT EXISTS'  => 'NOT EXISTS'
            )
        ));

        new Text($postMeta, 'postmetavalue', n2_('Field value'));

        new Select($postMeta, 'postmetatype', n2_('Field type'), 'CHAR', array(
            'options' => array(
                'CHAR'     => 'CHAR',
                'NUMERIC'  => 'NUMERIC',
                'DATE'     => 'DATE',
                'DATETIME' => 'DATETIME',
                'TIME'     => 'TIME',
                'BINARY'   => 'BINARY',
                'DECIMAL'  => 'DECIMAL',
                'SIGNED'   => 'SIGNED',
                'UNSIGNED' => 'UNSIGNED'
            )
        ));

        $postMetaMore = $postMetaGroup->createRow('postmeta-more');
        new Textarea($postMetaMore, 'postmetakeymore', n2_('Meta comparison'), '', array(
            'tipLabel'       => n2_('Meta comparison'),
            'tipDescription' => sprintf(n2_('You can create other comparisons based on the previous "Post Meta Comparison" options. Use the following format: name||compare||value||type%1$s%1$s Example:%1$spublished||=||yes||CHAR%1$s%1$sWrite one comparison per line.'), '<br>'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1900-wordpress-custom-posts-generator#post-meta-comparison',
            'width'          => 300,
            'height'         => 100
        ));

        $orderGroup = new ContainerTable($container, 'order-group', n2_('Order'));
        $order      = $orderGroup->createRow('order');
        new GeneratorOrder($order, 'postsorder', 'post_date|*|desc', array(
            'options' => array(
                'none'          => n2_('None'),
                'post_date'     => n2_('Post date'),
                'ID'            => 'ID',
                'title'         => n2_('Title'),
                'post_modified' => n2_('Modification date'),
                'rand'          => n2_('Random'),
                'post__in'      => n2_('Given IDs'),
                'menu_order'    => n2_('Menu order')
            )
        ));
    }

    protected function _getData($count, $startIndex) {
        global $post, $wp_query;
        $tmpPost = $post;

        if (has_filter('the_content', 'siteorigin_panels_filter_content')) {
            $siteorigin_panels_filter_content = true;
            remove_filter('the_content', 'siteorigin_panels_filter_content');
        } else {
            $siteorigin_panels_filter_content = false;
        }

        $taxonomies = array_diff(explode('||', $this->data->get('taxonomies', '')), array(
            '',
            0
        ));

        if (count($taxonomies)) {
            $tax_array = array();
            foreach ($taxonomies as $tax) {
                $parts = explode('|*|', $tax);
                if (!is_array(@$tax_array[$parts[0]]) || !in_array($parts[1], $tax_array[$parts[0]])) {
                    $tax_array[$parts[0]][] = $parts[1];
                }
            }

            $tax_query = array();
            foreach ($tax_array as $taxonomy => $terms) {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'terms'    => $terms,
                    'field'    => 'id',
                    'relation' => 'OR'
                );
            }
        } else {
            $tax_query = '';
        }

        $compare       = array();
        $compare_value = $this->data->get('postmetacompare', '');
        if (!empty($compare_value)) {
            $compare = array('compare' => $compare_value);
        }

        $postMetaKey = $this->data->get('postmetakey', '0');
        if (!empty($postMetaKey)) {
            $postMetaValue = $this->data->get('postmetavalue', '');
            $getPostMeta   = array(
                'meta_query' => array(
                    array(
                        'key'  => $postMetaKey,
                        'type' => $this->data->get('postmetatype', 'CHAR')
                    ) + $compare
                )
            );

            if ($compare_value != 'EXISTS' && $compare_value != 'NOT EXISTS') {
                $getPostMeta['meta_query'][0]['value'] = $postMetaValue;
            }
        } else {
            $getPostMeta = array();
        }

        $metaMore = $this->data->get('postmetakeymore', '');
        if (!empty($metaMore) && $metaMore != 'field_name||compare_method||field_value') {
            $metaMoreValues = explode(PHP_EOL, $metaMore);
            foreach ($metaMoreValues as $metaMoreValue) {
                $metaMoreValue = trim($metaMoreValue);
                if ($metaMoreValue != 'field_name||compare_method||field_value') {
                    $metaMoreArray = explode('||', $metaMoreValue);
                    if (count($metaMoreArray) >= 2) {
                        $compare = array('compare' => $metaMoreArray[1]);

                        $key_query = array(
                            'key' => $metaMoreArray[0]
                        );

                        if (!empty($metaMoreArray[2])) {
                            $key_query += array(
                                'value' => $metaMoreArray[2]
                            );
                        }

                        if (!empty($metaMoreArray[3])) {
                            $key_query += array(
                                'type' => $metaMoreArray[3]
                            );
                        }

                        $getPostMeta['meta_query'][] = $key_query + $compare;
                    }
                }
            }
        }

        list($orderBy, $order) = Common::parse($this->data->get('postsorder', 'post_date|*|desc'));

        $getPosts = array(
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => $this->data->get('poststatus', 'publish'),
            'suppress_filters' => false,
            'offset'           => $startIndex,
            'posts_per_page'   => $count,
            'tax_query'        => $tax_query
        );

        $postTypes = $this->data->get('posttypes', '');
        if (!empty($postTypes)) {
            $getPosts += array('post_type' => explode('||', $postTypes));
        } else {
            $getPosts += array('post_type' => 'any');
        }

        if ($orderBy != 'none') {
            $getPosts += array(
                'orderby'            => $orderBy,
                'order'              => $order,
                'ignore_custom_sort' => true
            );
        }

        $poststicky = $this->data->get('poststicky');
        switch ($poststicky) {
            case 1:
                $getPosts += array(
                    'post__in' => get_option('sticky_posts')
                );
                break;
            case -1:
                $getPosts += array(
                    'post__not_in' => get_option('sticky_posts')
                );
                break;
        }

        $ids = $this->getIDs();

        if (count($ids) <> 1 || $ids[0] <> 0) {
            $getPosts += array(
                'post__in' => $ids
            );
        }

        $getPosts = array_merge($getPosts, $getPostMeta);

        $posts = get_posts($getPosts);

        $data = array();

        for ($i = 0; $i < count($posts); $i++) {
            $record = array();

            $post = $posts[$i];
            setup_postdata($post);
            $wp_query->post = $post;

            $record['id'] = $post->ID;

            $record['url']                = get_permalink();
            $record['title']              = apply_filters('the_title', get_the_title(), $post->ID);
            $record['content']            = get_the_content();
            $record['description']        = GeneratorGroupPosts::removeShortcodes($record['content']);
            $record['author_name']        = $record['author'] = get_the_author();
            $userID                       = get_the_author_meta('ID');
            $record['author_url']         = get_author_posts_url($userID);
            $record['author_avatar']      = get_avatar_url($userID);
            $record['date']               = get_the_date();
            $record['modified']           = get_the_modified_date();
            $type_object                  = get_post_type_object(get_post_type());
            $record['type_singular_name'] = $type_object->labels->singular_name;

            $record = array_merge($record, GeneratorGroupPosts::getCategoryData($post->ID));

            $thumbnail_id             = get_post_thumbnail_id($post->ID);
            $record['featured_image'] = wp_get_attachment_image_url($thumbnail_id, 'full');
            if (!$record['featured_image']) {
                $record['featured_image'] = '';
            } else {
                $thumbnail_meta = get_post_meta($thumbnail_id, '_wp_attachment_metadata', true);
                if (isset($thumbnail_meta['sizes'])) {
                    $sizes  = GeneratorGroupPosts::getImageSizes($thumbnail_id, $thumbnail_meta['sizes']);
                    $record = array_merge($record, $sizes);
                }
                $record['alt'] = '';
                $alt           = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                if (isset($alt)) {
                    $record['alt'] = $alt;
                }
            }

            $record['thumbnail'] = $record['image'] = $record['featured_image'];
            $record['url_label'] = 'View';

            $record = GeneratorGroupPosts::arrayMerge($record, GeneratorGroupPosts::extractPostMeta(get_post_meta($post->ID)));

            $taxonomies = get_post_taxonomies($post->ID);
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_post_terms($post->ID, $taxonomy);
                $taxonomy   = str_replace('-', '', $taxonomy);

                for ($j = 0; $j < count($post_terms); $j++) {
                    $record[$taxonomy . '_' . ($j + 1)]                  = $post_terms[$j]->name;
                    $record[$taxonomy . '_' . ($j + 1) . '_ID']          = $post_terms[$j]->term_id;
                    $record[$taxonomy . '_' . ($j + 1) . '_description'] = $post_terms[$j]->description;
                }
            }

            $record = GeneratorGroupPosts::arrayMerge($record, GeneratorGroupPosts::getACFData($post->ID), 'acf_');

            if (isset($record['primarytermcategory'])) {
                $primary                         = get_category($record['primarytermcategory']);
                $record['primary_category_name'] = $primary->name;
                $record['primary_category_link'] = get_category_link($primary->cat_ID);
            }
            $record['excerpt'] = get_the_excerpt();

            $data[$i] = &$record;
            unset($record);
        }
        if ($siteorigin_panels_filter_content) {
            add_filter('the_content', 'siteorigin_panels_filter_content');
        }

        $wp_query->post = $tmpPost;
        wp_reset_postdata();

        return $data;
    }
}

