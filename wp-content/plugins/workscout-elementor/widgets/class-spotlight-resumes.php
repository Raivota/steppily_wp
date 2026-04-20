<?php

/**
 * Workscout Elementor Spotlight Jobs Box class.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.benmarshall.me/build-custom-elementor-widgets/,
 *             Build Custom Elementor Widgets)
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorWorkscout\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
    exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class SpotlightResumes extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'workscout-spotlight-resumes';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Spotlight Resumes', 'workscout_elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'fa fa-bullhorn';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return array('workscout');
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */


    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __('Content', 'workscout_elementor'),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => __('Title', 'workscout_elementor'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __('Featured Resumes', 'workscout_elementor'),
            )
        );

        $this->add_control(
            'per_page',
            [
                'label' => __('Listings to display', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 21,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order by', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' =>  __(' Order by date.', 'workscout_elementor'),
                    'rand' =>  __(' Random order.', 'workscout_elementor'),
                    'featured' =>  __('Featured', 'workscout_elementor'),
                    'ID' =>  __('Order by post id. ', 'workscout_elementor'),
                    'author' =>  __('Order by author.', 'workscout_elementor'),
                    'title' =>  __('Order by title.', 'workscout_elementor'),
                ],
            ]
        );
        $this->add_control(
            'order',
            [
                'label' => __('Order', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' =>  __('Descending', 'workscout_elementor'),
                    'ASC' =>  __('Ascending. ', 'workscout_elementor'),


                ],
            ]
        );



        $this->add_control(
            'candidate_photo',
            [
                'label' => __('Candidate Photo', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-plugin'),
                'label_off' => __('Hide', 'your-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Auto Play', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-plugin'),
                'label_off' => __('Hide', 'your-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );


        $this->add_control(
            'delay',
            array(
                'label'   => __('Auto Play Speed', 'workscout_elementor'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => __('Subtitle', 'workscout_elementor'),
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'default' => 5000,
            )
        );

        $this->add_control(
            'limitby',
            [
                'label' => __('Limit text by', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'words',
                'multiple' => true,
                'options' => [
                    'words' =>  __('Words', 'workscout_elementor'),
                    'letters' =>  __('Letters. ', 'workscout_elementor'),

                ],
            ]
        );
        $this->add_control(
            'limit',
            [
                'label' => __('Limit text nunber', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 250,
                'step' => 1,
                'default' => 20,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __('Show only from categories', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'default' => [],
                'options' => $this->get_terms('resume_category'),

            ]
        );
      
        $this->add_control(
            'resume_ids',
            [
                'label' => __('Show only selected resumes', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'default' => [],
                'options' => $this->get_posts(),

            ]
        );

        $this->add_control(
            'featured',
            [
                'label' => __('Featured resumes', 'workscout_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'null',
                'multiple' => true,
                'options' => [
                    'true' =>  __('Show only featured', 'workscout_elementor'),
                    'false' =>  __('Hide featured. ', 'workscout_elementor'),
                    'null' =>  __('Show all. ', 'workscout_elementor'),

                ],
            ]
        );

   



        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();



        // 'per_page'                  => get_option( 'job_manager_per_page' ),
        // 'orderby'                   => 'featured',
        // 'order'                     => 'DESC',
        // 'title'                     => 'Job Spotlight',
        // 'visible'                   => '1,1,1,1',
        // 'meta'                      => 'company,location,rate,salary',
        // 'autoplay'                  => "off",
        // 'delay'                     => 5000,
        // 'limit'                     => 20,
        // 'limitby'                   => 'words', //characters
        // // Limit what jobs are shown based on category and type
        // 'categories'                => '',
        // 'job_types'                 => '',
        // 'job_ids'                   => '',
        // 'featured'                  => null, // True to show only featured, false to hide featured, leave null to show both.
        // 'filled'                    => null, // True to show only filled, false to hide filled, leave null to show both/use the settings.

        $per_page = $settings['per_page'] ? $settings['per_page'] : get_option('job_manager_per_page');
        $orderby = $settings['orderby'] ? $settings['orderby'] : 'featured';
        $order = $settings['order'] ? $settings['order'] : 'DESC';
        //$visible = $settings['visible'] ? $settings['visible'] : '1,1,1,1';
        $title = $settings['title'];
        $autoplay = $settings['autoplay'];
        $candidate_photo = $settings['candidate_photo'];
        $delay = $settings['delay'];
        $limit = $settings['limit'];
        $limitby = $settings['limitby'];
        $categories = $settings['categories'];
        
        $resume_ids = $settings['resume_ids'];
        $featured = $settings['featured'];
        


    

        // Array handling
        $categories         = is_array($categories) ? $categories : array_filter(array_map('trim', explode(',', $categories)));

        if ($featured != "null") {
            $featured = (is_bool($featured) && $featured) || in_array($featured, array('1', 'true', 'yes')) ? true : false;
        } else {
            $featured = null;
        }

        $query_args = array(
            'post_type'              => 'resume',
            'post_status'            => 'publish',
            'ignore_sticky_posts'    => 1,
            'offset'                 => 0,
            'posts_per_page'         => intval($per_page),
            'orderby'                => $orderby,
            'order'                  => $order,
            'fields'                 => 'all'
        );

        if (!empty($resume_ids)) {
            
            $query_args['post__in'] = $resume_ids;
        }

        if (!is_null($featured)) {
            $query_args['meta_query'][] = array(
                'key'     => '_featured',
                'value'   => '1',
                'compare' => $featured ? '=' : '!='
            );
        }

        if (!empty($categories)) {
            $field    = is_numeric($categories[0]) ? 'term_id' : 'slug';

            $operator = 'all' === get_option('resume_manager_category_filter_type', 'all') && sizeof($categories) > 1 ? 'AND' : 'IN';
            $query_args['tax_query'][] = array(
                'taxonomy'         => 'resume_category',
                'field'            => $field,
                'terms'            => array_values($categories),
                'include_children' => $operator !== 'AND',
                'operator'         => $operator
            );
        }

        if ('featured' === $orderby) {
            $orderby = array(
                'menu_order' => 'ASC',
                'date'       => 'DESC'
            );
        }

?><h3 class="margin-bottom-5"><?php echo esc_html($title); ?></h3><?php
                                                                    $wp_query = new \WP_Query($query_args);
                                                          
                                                                    $resume_photo_style = \Kirki::get_option('workscout', 'pp_resume_rounded_photos', 'off');

                                                                    if ($resume_photo_style) {
                                                                        $photo_class = "square";
                                                                    } else {
                                                                        $photo_class = "rounded";
                                                                    }
                                                                    if ($wp_query->have_posts()) :   ?>

            <!-- Showbiz Container -->
            <?php $slick_autplay = ($autoplay == 'on') ? true : false; ?>
            <div id="job-spotlight" data-slick='{"autoplaySpeed": <?php echo $delay; ?>, "autoplay": <?php echo $slick_autplay; ?> }' class="job-spotlight-car showbiz-container">
                <?php while ($wp_query->have_posts()) : $wp_query->the_post();
                                                                            $id = get_the_id(); ?>

                    <div class="resume-spotlight photo-<?php echo $photo_class ?>">
                        <?php if ($candidate_photo == 'yes') {
                                                                                the_candidate_photo();
                                                                            } ?>
                        <a href="<?php the_permalink(); ?>">
                            <h4><?php the_title(); ?> <span><?php the_candidate_title(); ?></span></h4>
                        </a>
                        <span><i class="fa fa-map-marker"></i> <?php ws_candidate_location(); ?></span>

                        <?php
                                                                            $currency_position =  get_option('workscout_currency_position', 'before');

                                                                            $rate_min = get_post_meta($id, '_rate_min', true);
                                                                            if ($rate_min) {
                                                                                $rate_max = get_post_meta($id, '_rate_max', true);  ?>
                            <span>
                                <i class="fa fa-money"></i> <?php
                                                                                if ($currency_position == 'before') {
                                                                                    echo get_workscout_currency_symbol();
                                                                                }
                                                                                echo esc_html($rate_min);
                                                                                if ($currency_position == 'after') {
                                                                                    echo get_workscout_currency_symbol();
                                                                                }
                                                                                if (!empty($rate_max)) {
                                                                                    echo '- ';
                                                                                    if ($currency_position == 'before') {
                                                                                        echo get_workscout_currency_symbol();
                                                                                    }
                                                                                    echo $rate_max;
                                                                                    if ($currency_position == 'after') {
                                                                                        echo get_workscout_currency_symbol();
                                                                                    }
                                                                                } ?> <?php _e('/ hour', 'workscout_core'); ?>
                            </span>
                        <?php } ?>

                        <?php
                                                                            $salary_min = get_post_meta($id, '_salary_min', true);
                                                                            if ($salary_min) {
                                                                                $salary_max = get_post_meta($id, '_salary_max', true);  ?>
                            <span>
                                <i class="fa fa-money"></i>
                                <?php
                                                                                if ($currency_position == 'before') {
                                                                                    echo get_workscout_currency_symbol();
                                                                                }
                                                                                echo esc_html($salary_min);
                                                                                if ($currency_position == 'after') {
                                                                                    echo get_workscout_currency_symbol();
                                                                                } ?> <?php
                                                                                            if (!empty($salary_max)) {
                                                                                                echo ' - ';
                                                                                                if ($currency_position == 'before') {
                                                                                                    echo get_workscout_currency_symbol();
                                                                                                }
                                                                                                echo $salary_max;
                                                                                                if ($currency_position == 'after') {
                                                                                                    echo get_workscout_currency_symbol();
                                                                                                }
                                                                                            } ?>
                            </span>
                        <?php } ?>

                        <p><?php
                                                                            $excerpt = get_the_excerpt();
                                                                            echo workscout_string_limit_words($excerpt, 20); ?>...
                        </p>
                        <?php if (($skills = wp_get_object_terms($id, 'resume_skill', array('fields' => 'names'))) && is_array($skills)) : ?>
                            <div class="skills">
                                <?php echo '<span>' . implode('</span><span>', $skills) . '</span>'; ?>
                            </div>
                            <div class="clearfix"></div>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="button"><?php esc_html_e('Contact Candidate', 'workscout_core') ?></a>
                    </div>


                <?php endwhile; ?>
            </div>
<?php

                                                                    endif;
                                                                    wp_reset_postdata();
                                                                }



                                                                protected function get_terms($taxonomy)
                                                                {
                                                                    $taxonomies = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));

                                                                    $options = ['' => ''];

                                                                    if (!empty($taxonomies)) :
                                                                        foreach ($taxonomies as $taxonomy) {
                                                                            $options[$taxonomy->slug] = $taxonomy->name;
                                                                        }
                                                                    endif;

                                                                    return $options;
                                                                }
                                                                protected function get_posts()
                                                                {
                                                                    $posts = get_posts(
                                                                        array(
                                                                            'numberposts' => -1,
                                                                            'post_type' => 'resume',
                                                                            'suppress_filters' => true
                                                                        )
                                                                    );

                                                                    $options = ['' => ''];

                                                                    if (!empty($posts)) :
                                                                        foreach ($posts as $post) {
                                                                            $options[$post->ID] = get_the_title($post->ID);
                                                                        }
                                                                    endif;

                                                                    return $options;
                                                                }
                                                            }
