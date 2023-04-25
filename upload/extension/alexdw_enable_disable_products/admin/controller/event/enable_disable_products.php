<?php
#############################################################
#	Enable Disable Products 1.08 for Opencart 4x by AlexDW	#
#############################################################
namespace Opencart\Admin\Controller\Extension\AlexDWEnableDisableProducts\Event;
class EnableDisableProducts extends \Opencart\System\Engine\Controller {

	private $path = 'extension/alexdw_enable_disable_products/module/enable_disable_products';
	private $module = 'module_enable_disable_products';

	public function init(&$route, &$args, &$output): void {

		if ($this->config->get($this->module.'_status')) {

            $this->load->language($this->path);
			$this->load->language('catalog/product');

	$data['enable_pro'] = $this->url->link('extension/alexdw_enable_disable_products/event/enable_disable_products|enable', 'user_token=' . $this->session->data['user_token']);
	$data['disable_pro'] = $this->url->link('extension/alexdw_enable_disable_products/event/enable_disable_products|disable', 'user_token=' . $this->session->data['user_token']);
	$data['enable_all'] = $this->url->link('extension/alexdw_enable_disable_products/event/enable_disable_products|enable_all', 'user_token=' . $this->session->data['user_token']);
	$data['disable_all'] = $this->url->link('extension/alexdw_enable_disable_products/event/enable_disable_products|disable_all', 'user_token=' . $this->session->data['user_token']);

			$hook = '<a href="' . $args['add'] . '" data-bs-toggle="tooltip" title="' . $args['button_add'] . '" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>';
			$insert = $this->load->view($this->path.'_tpl', $data) . $hook;
			$output = str_replace($hook, $insert, $output);

			$hook = '<button type="button" id="button-filter"';
			$insert = '<button type="button" id="button-clear" class="btn btn-info" style="margin-right:5px"><i class="fas fa-eraser"></i> Clear</button>' . $hook;
			$output = str_replace($hook, $insert, $output);

			$hook = "$('#button-filter').on";
			$insert = "
$('#button-clear').on('click', function() {
	$('select[class=\'form-select\']').val('');
	$('input[class=\'form-control\']').val('');
	$('#button-filter').click();
});
$('input[class=\'form-control\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-filter').click();
	}
});" . $hook;
			$output = str_replace($hook, $insert, $output);
		}
	}

	public function enable(): void {
		$this->load->language($this->path);
		$this->load->language('catalog/product');
		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/alexdw_enable_disable_products/endispro');

			foreach ($selected as $product_id) {
				$this->model_extension_alexdw_enable_disable_products_endispro->enable_pro($product_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable(): void {
		$this->load->language($this->path);
		$this->load->language('catalog/product');
		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/alexdw_enable_disable_products/endispro');

			foreach ($selected as $product_id) {
				$this->model_extension_alexdw_enable_disable_products_endispro->disable_pro($product_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function enable_all(): void {
		$this->load->language($this->path);
		$this->load->language('catalog/product');
		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/alexdw_enable_disable_products/endispro');
			$this->model_extension_alexdw_enable_disable_products_endispro->enable_all();
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable_all(): void {
		$this->load->language($this->path);
		$this->load->language('catalog/product');
		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/alexdw_enable_disable_products/endispro');
			$this->model_extension_alexdw_enable_disable_products_endispro->disable_all();
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
