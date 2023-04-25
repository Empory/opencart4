<?php

namespace Opencart\Admin\Controller\Extension\Lets\Startup;

class Lets extends \Opencart\System\Engine\Controller
{
    public function index(): void
    {
        if ($this->config->get('theme_lets_status')) {
            $this->event->register('controller/*/after', new \Opencart\System\Engine\Action('extension/lets/startup/let|event'));
        }
    }

    public function event(string &$route, array &$args): void
    {
        $override = [
            'setting/setting|theme'
        ];
        if (in_array($route, $override)) {
            if (basename($this->request->get['theme']) == "lets") {
                $image = HTTP_CATALOG . 'extension/lets/catalog/view/image/lets.png';
                $this->response->setOutput($image);
            }
        }
    }
}
