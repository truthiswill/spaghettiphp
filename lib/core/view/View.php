<?php

class View extends Object {
    public $layout;
    public $extension = 'htm';
    public $contentForLayout;
    public $scriptsForLayout;
    public $stylesForLayout;
    public $helpers = array('html', 'form', 'pagination');
    protected $loadedHelpers = array();

    public function __construct() {
        $this->loadHelper($this->helpers);
    }
    public function __get($name) {
        if(!array_key_exists($name, $this->loadedHelpers)):
            $this->loadHelper($name);
        endif;
        
        return $this->loadedHelpers[$name];
    }
    public function loadHelper($helper) {
        if(is_array($helper)):
            return array_walk($helper, array($this, 'loadHelper'));
        endif;
        
        $helper_class = Inflector::camelize($helper) . 'Helper';
        require_once 'lib/helpers/' . $helper_class . '.php';
        $this->loadedHelpers[$helper] = new $helper_class($this);
    }
    public function render($action, $data = array()) {
        $filename = explode('.', $action);
        $action = $filename[0];
        if(array_key_exists(1, $filename)):
            $extension = $filename[1];
        else:
            $extension = $this->extension;
        endif;
        $view_file = Loader::path('View', $action . '.' . $extension);
        
        if(file_exists($view_file)):
            $output = $this->renderView($view_file, $data);
            if($this->layout):
                $output = $this->renderLayout($this->layout, $output, $data);
            endif;
            return $output;
        else:
            $this->error('missingView', array(
                'view' => $action,
                'extension' => $extension
            ));
            return false;
        endif;
    }
    public function renderLayout($layout, $content, $data) {
        $layout_file = Loader::path('Layout', $layout . '.' . $this->extension);
        if(file_exists($layout_file)):
            $this->contentForLayout = $content;
            return $this->renderView($layout_file, $data);
        else:
            $this->error('missingLayout', array(
                'layout' => $layout
            ));
            return false;
        endif;        
    }
    public function element($element, $data = array()) {
        $element = dirname($element) . '/_' . basename($element);
        $element_path = Loader::path('View', $element . '.' . $this->extension);
        return $this->renderView($element_path, $data);
    }
    protected function renderView($filename, $data = array()) {
        extract($data, EXTR_OVERWRITE);
        ob_start();
        require $filename;
        $output = ob_get_clean();
        return $output;
    }
}