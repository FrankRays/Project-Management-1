<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Yield :: HOOKS
 *
 * Adds layout support :: Similar to RoR <%= yield =>
 * '{yield}' will be replaced with all output generated by the controller/view.
 */
class Yield {

    function doYield() {

        global $OUT;
        $CI = & get_instance();
        $output = $CI->output->get_output();

        if (!isset($CI->layout)) {
            $CI->layout = 'ajax';
        }

        $CI->layout .= '.php';

        // load the html helper library
        $CI->load->library('HtmlBuilder');

        if (!isset($CI->js)) {
            $CI->js = array();
        }

        if (!isset($CI->css)) {
            $CI->css = array();
        }

        $requested = APPPATH . 'views/layouts/' . $CI->layout;
        $default = APPPATH . 'views/layouts/ajax.php';

        if (file_exists($requested)) {
            $layout = $CI->load->file($requested, true);
        } else {
            $layout = $CI->load->file($default, true);
        }

        $view = str_replace("{yield}", $output, $layout);


        $OUT->_display($view);
    }

}

?>
