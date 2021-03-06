<?php 
/* 
 * Hellopaisa online payment
 *
 * @version 1.0
 * @date 21/12/2013
 * @author Yujesh K.C (Khadka) <ujesh.kc@gmail.com>
 * @more info available on w3webstudio.com
 */
class ControllerPaymentHelloPaisa extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/hellopaisa');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');	
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hellopaisa', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_hello'] = $this->language->get('entry_hello');
		$this->data['hellopaisa_id'] = $this->language->get('hellopaisa_id');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		if (isset($this->error['hello_id'])) {
			$this->data['hello_id'] = $this->error['hello_id'];
		} else {
			$this->data['hello_id'] = '';
		}

		

		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->error['hello_' . $language['language_id']])) {
				$this->data['error_hello_' . $language['language_id']] = $this->error['hello_' . $language['language_id']];
			} else {
				$this->data['error_hello_' . $language['language_id']] = '';
			}
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/hellopaisa', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/hellopaisa', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/language');
		
		foreach ($languages as $language) {
			if (isset($this->request->post['hello_paisa_transfer_' . $language['language_id']])) {
				$this->data['hello_paisa_transfer_' . $language['language_id']] = $this->request->post['hello_paisa_transfer_' . $language['language_id']];
			} else {
				$this->data['hello_paisa_transfer_' . $language['language_id']] = $this->config->get('hello_paisa_transfer_' . $language['language_id']);
			}
		}
		
		$this->data['languages'] = $languages;
		
		if (isset($this->request->post['hello_paisa_total'])) {
			$this->data['hello_paisa_total'] = $this->request->post['hello_paisa_total'];
		} else {
			$this->data['hello_paisa_total'] = $this->config->get('hello_paisa_total'); 
		} 
				
		if (isset($this->request->post['hello_paisa_order_status_id'])) {
			$this->data['hello_paisa_order_status_id'] = $this->request->post['hello_paisa_order_status_id'];
		} else {
			$this->data['hello_paisa_order_status_id'] = $this->config->get('hello_paisa_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['hello_paisa_geo_zone_id'])) {
			$this->data['hello_paisa_geo_zone_id'] = $this->request->post['hello_paisa_geo_zone_id'];
		} else {
			$this->data['hello_paisa_geo_zone_id'] = $this->config->get('hello_paisa_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['hellopaisa_status'])) {
			$this->data['hellopaisa_status'] = $this->request->post['hellopaisa_status'];
		} else {
			$this->data['hello_paisastatus'] = $this->config->get('hellopaisa_status');
		}
		
		if (isset($this->request->post['hello_paisa_sort_order'])) {
			$this->data['hello_paisa_sort_order'] = $this->request->post['hello_paisa_sort_order'];
		} else {
			$this->data['hello_paisa_sort_order'] = $this->config->get('hello_paisa_sort_order');
		}
		
		if (isset($this->request->post['hello_paisa_id'])) {
			$this->data['hello_paisa_id'] = $this->request->post['hello_paisa_id'];
		} else {
			$this->data['hello_paisa_id'] = $this->config->get('hello_paisa_id');
		}
		

		$this->template = 'payment/hellopaisa.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/hellopaisa')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		
		if (!$this->request->post['hello_paisa_id']) {
			$this->error['hello_id'] = $this->language->get('hello_id');
		}
		
		foreach ($languages as $language) {
			if (!$this->request->post['hello_paisa_transfer_' . $language['language_id']]) {
				$this->error['hello_' .  $language['language_id']] = $this->language->get('error_hello');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>