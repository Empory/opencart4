<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						$tax_price = (float)$product_info['special'];
					} else {
						$special = false;
						$tax_price = (float)$product_info['price'];
					}
		
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format($tax_price, $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
					
					
					$original_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
					$special_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
					$discound = $this->currency->format(($original_price - $special_price), $this->session->data['currency']);
					$total_sales = $this->model_catalog_product->getTotalProductSales($product_id);
					$percent = round((($product_info['price'] -  $product_info['special'])/$product_info['price']) * 100 ,0). '%';
					$categories = $this->model_catalog_product->getCategories($product_info['product_id']);
					if($categories){
					    $categories_info = $this->model_catalog_category->getCategory($categories[0]['category_id']);
					    $category_title = $categories_info['name'];
					}else{
					    $category_title = '';
					}
					
					if(mb_strlen($product_info['name'], 'UTF-8') > 45){
						$produc_name = utf8_substr($product_info['name'], 0, 45) . '...';
					} else {
						$produc_name = $product_info['name'];
					}

					if($total_sales > 1 ){
					$top_selling = '<span class="new">Top Selling</span>';
					} else {
					$top_selling = "";
					}
	
					
					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $produc_name,
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'percent' 	  => $percent,
						'top_selling' => $top_selling,
						'category_title' => $category_title,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}