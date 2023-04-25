<?php

namespace Opencart\Admin\Controller\Extension\Lets\Theme;

class Lets extends \Opencart\System\Engine\Controller
{
    public function index(): void
    {

        $this->load->language('extension/lets/theme/lets');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/lets/theme/lets', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id']),
        ];

        $data['save'] = $this->url->link('extension/lets/theme/lets|save', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme');

        if (isset($this->request->get['store_id'])) {
            $this->load->model('setting/setting');

            $setting_info = $this->model_setting_setting->getSetting('theme_lets', $this->request->get['store_id']);
        }

        if (isset($setting_info['theme_lets_status'])) {
            $data['theme_lets_status'] = $setting_info['theme_lets_status'];
        } else {
            $data['theme_lets_status'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/lets/theme/lets', $data));
    }

    public function save(): void
    {
        $this->load->language('extension/lets/theme/lets');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/lets/theme/lets')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('theme_lets', $this->request->post, $this->request->get['store_id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install(): void
    {
        $this->load->model('setting/startup');
        $startup_data = [
            'code' => 'theme_lets',
            'action' => 'catalog/extension/lets/startup/lets',
            'status' => 1,
            'sort_order' => 3,
        ];

        $this->model_setting_startup->addStartup($startup_data);

        $startup_data_admin = [
            'code' => 'theme_lets',
            'action' => 'admin/extension/lets/startup/lets',
            'status' => 1,
            'sort_order' => 4,
        ];
        $this->model_setting_startup->addStartup($startup_data_admin);
    }

    public function uninstall(): void
    {
        if ($this->user->hasPermission('modify', 'extension/lets/theme/lets')) {
            $this->load->model('setting/startup');

            $this->model_setting_startup->deleteStartupByCode('theme_lets');
        }
    }
}
