<?php

class HL_Listener
{

    private $hooks = array();
    private $actions = array();

    public function __construct(array $hooks)
    {

        $this->hooks = $hooks;

        add_action('init', array($this, 'listener'));
        add_action('admin_bar_menu', array($this, 'init_admin_bar_menu'), 100);

    }

    public function listener()
    {

        foreach ($this->hooks as $hook) {
            add_action($hook, function () {
                $this->actions[] = current_filter();
            }, 10);
        }
        add_action('shutdown', array($this, 'shutdown_action_callback'), 11);

    }

    public function shutdown_action_callback()
    {
        $modal_template = '
        <div id="hl_modal" class="modal">
          <div class="modal-content hl-modal-content">
            <div class="modal-header">
                <div id="hl-close-modal-container"><span class="hl_close_modal">&times;</span></div>
                <h4 id="hl_title_modal">do_action()</h2>
            </div>
            %s
          </div>
        </div>';

        $hook_lines = '';
        foreach($this->actions as $key => $action) {
            $class = $key % 2 === 0 ? 'alternate-background' : '';
            $hook_lines .= '<div class="'. $class .' hl-hook-line">';
            $hook_lines .= $action;
            $hook_lines .= '</div>';
        }
        $modal = sprintf($modal_template, $hook_lines);
        echo $modal;
        
    }

    public function init_admin_bar_menu(WP_Admin_Bar $admin_bar)
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        $admin_bar->add_menu(array(
            'id'    => 'hl-menu',
            'parent' => null,
            'group'  => null,
            'title' => 'Show Hooks Fired', //you can use img tag with image link. it will show the image icon Instead of the title.
            'meta' => [
                'title' => __('Menu Title', 'textdomain'), //This title will show on hover
            ]
        ));
    }
}


new HL_Listener($hooksArrayList);
