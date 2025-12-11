<?php

class Controller {
    public function view($file, $data = []) {
        return View::render($file, $data);
    }
}
