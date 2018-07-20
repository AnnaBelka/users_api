<?php
class View {

    public $index_view = '/web/views/index.php';

    public function assign($template=null, $content=null) {

       include dirname(dirname(__FILE__)).$this->index_view;

    }
}