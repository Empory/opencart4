<?php
#############################################################
#	Enable Disable Products 1.08 for Opencart 4x by AlexDW	#
#############################################################
namespace Opencart\Admin\Controller\Extension\AlexDWEnableDisableProducts\Module;
class EnableDisableProducts extends \Opencart\System\Engine\Controller {

	private $path = 'extension/alexdw_enable_disable_products/module/enable_disable_products';
	private $event = 'extension/alexdw_enable_disable_products/event/enable_disable_products';
	private $module = 'module_enable_disable_products';
	private $description = 'Buttons for Enable / Disable Products in the Product List';

	public function index(): void {
		$this->load->language($this->path);
		$this->load->model('setting/extension');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->path, 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link($this->path.'|save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data[$this->module.'_status'] = $this->config->get($this->module.'_status');
    
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->path, $data));
	}

	public function save(): void {
		$this->load->language($this->path);
		$json = [];

		if (!$this->user->hasPermission('modify', $this->path)) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->init();
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($this->module, $this->request->post);
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function init(): void {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode($this->module);
		$this->model_setting_event->addEvent([
			'code'			=> $this->module, 
			'description'	=> $this->description,
			'trigger'		=> 'admin/view/catalog/product/after',
			'action'		=> $this->event.'|init',
			'status'		=> true,
			'sort_order'	=> 0
		]);

        $this->load->model('user/user_group');
		$groups = $this->model_user_user_group->getUserGroups();

		foreach($groups as $group) {
			$this->model_user_user_group->addPermission($group['user_group_id'], 'access', $this->event);
		}
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', $this->path)) {
			$this->init();
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', $this->path)) {
			$this->load->model('setting/event');
			$this->model_setting_event->deleteEventByCode($this->module);
		}
	}
}