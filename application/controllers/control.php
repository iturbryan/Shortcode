<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Control extends CI_Controller {

	// num of records per page

    	private $limit = 15;

	var $gateway = null;
	
	var $settings = null;

	function __construct()

	{
	
		  parent::__construct();
		  
		  $this->load->library('AfricasTalkingGateway', null, 'AT');

		  $this->load->model('User','',TRUE);

		  $this->load->model('Setting','',TRUE);

		  $this->load->model('Subscription','',TRUE);

		  $this->load->model('Outbox','',TRUE);

		  $this->load->model('Inbox','',TRUE);

		  $this->load->model('Contact','',TRUE);

		  $this->load->model('Keyword','',TRUE);

		  $this->settings = $this->Setting->get_list();

		  $this->gateway = new $this->AT($this->extract_key('username'), $this->extract_key('api_key'));
		  
	
	
	}
	
	private function extract_key($key)

	{

		foreach($this->settings->result() as $setting)

		{

			if($setting->key == $key)

				return $setting->value;

		}

	}

	private function get($key)

	{
	
	  return $this->input->post($key);
	
	}

	private function auth(){
	
	  $result = $this->User->login($this->get('username'), $this->get('password'));

	  if($result)

	  {

	    $sess_array = array();

	    foreach($result as $row)

	    {

	      $sess_array = array(

		'id' => $row->id,

		'username' => $row->username,

		'last_login' => $row->last_login

	      );

	      $this->session->set_userdata('logged_in', $sess_array);

		$last_login = array('last_login' => date("Y-m-d H:i:s"));

		$this->User->update($row->id, $last_login);

	    }

	    return true;

	  }

	  else

	  {
	  
	    return false;

	  }
	
	}
	private function authenticate()

	{

		if(empty($this->session->userdata('logged_in')))

			redirect('/control/login');

	}

	private function show($view, $data = null)

	{
		
		$this->load->view('partial/header', $data);

		$this->load->view($view, $data);

		$this->load->view('partial/footer', $data);

	}
	
	public function subscription()

	/****** Subscription Callback URL (/control/subscription) ******/

	{



		$fh = fopen("log.txt", "a");
		fwrite($fh, print_r($this->input->post(), true));
		fclose($fh);
		$status = null;
		
		if(strtolower($this->get('updateType')) == 'addition')

		{

			$message = array('telephone' => $this->get('phoneNumber'), 'shortCode' => $this->get('shortCode'), 'keyword' => $this->get('keyword'));

			$this->Contact->save($message);	
		
		}

		else if(strtolower($this->get('updateType')) == 'deletion') 

		{

			$this->Contact->delete($this->get('phoneNumber'));

		}

		echo "Success";

	}

	public function delivered()

	/****** SMS Delivery Reports Callback URL (/control/delivered) ******/

	{

		$message = array('status' => $this->get('status'));

		$this->Outbox->update($this->get('id'), $message);

		echo "Success";
			
	}

	private function send_sms($recipients, $message, $shortCode, $keyword)

	{


		$this->authenticate();
		
		try 

		{ 
		  // Thats it, hit send and we'll take care of the rest. 

		$from = $this->extract_key('from');

		if($from == null)

			$results = $this->gateway->sendMessage($recipients, $message);

		else {

			$options = array('keyword' => 'Bible');
			
		  	$results = $this->gateway->sendMessage($recipients, $message, $from, 0, $options);
		  	
		  }
		$status = true;

		  foreach($results as $result) {
		    // Note that only the Status "Success" means the message was sent

			$outbox = array( 'telephone' => $result->number, 'status' => $result->status, 'messageID' => $result->messageId, 'amount' => $result->cost, 'datetime' => date("Y-m-d H:i:s") );
		
			$this->Outbox->save($outbox);
			
			if($result->status != 'Success')

				$status = false;
			
		   
		  }

		if($status == true)

		  $this->session->set_flashdata("success", "Message(s) successfully sent!");

		else

		  $this->session->set_flashdata("error", "An error was encountered sending the messages. Some may not have been sent!");

			return true;

		}

		catch ( AfricasTalkingGatewayException $e )

		{

		  $this->session->set_flashdata("error", "Encountered an error while sending: ".$e->getMessage());

			return false;

		}


	}

	public function settings()

	{

		$this->authenticate();

		if(!empty($this->input->post())){
		  
		    $settings = $this->Setting->get_list();
		    
		   $this->db->trans_start();
		    
		    foreach($settings->result() as $config){
		    
			$setting = array(
					'value' => $this->get($config->key)
					);
		    
			$this->Setting->update($config->key, $setting);			
		    
		    }
		
		$this->db->trans_complete();

		$this->session->set_flashdata("success", "AT Account settings successfully saved!");

		redirect('/control/settings', 'refresh');
		
		}

		$data['title'] = 'AT Account Settings';
		
		$data['configs'] = $this->Setting->get_list();
		
		$this->show('settings', $data);

	}

	private function cleanPhone($phone)

	{
	
		$return = "";
		
		if(strlen($phone) == 13 && substr($phone, 0, 3) == "+25")
		
		$return .= $phone;
		
		else if(strlen($phone) == 10 && substr($phone, 0, 2) == "07")
		
		$return .= "+254" . substr($phone, 1);
		
		else if(strlen($phone) == 9 && substr($phone, 0, 1) == "7")
		
		$return .= "+254" . $phone;
		
		return $return;
	}
	
	public function index()

	{

		$this->authenticate();
	
		if(!empty($this->input->post()))
		
		{
	
			$data['start_date'] = date("Y-m-d", strtotime($this->get('from')));

			$data['end_date'] = date("Y-m-d", strtotime($this->get('to')));

			$this->session->set_userdata('from', date("d-m-Y", strtotime($this->get('from'))));

			$this->session->set_userdata('to', date("d-m-Y", strtotime($this->get('to'))));
		
		}

		else

		{

			$data['start_date'] = date("Y-m-d H:i:s", strtotime($this->Subscription->get_start_date()));

			$data['end_date'] = date("Y-m-d H:i:s");

			$this->session->set_userdata('from', date("d-m-Y", strtotime($data['start_date'])));

			$this->session->set_userdata('to', date("d-m-Y"));


		}

		$data['title'] = 'Dashboard';

		$data['new_subscriptions'] = $this->Subscription->get_new_subscriptions($this->session->userdata('logged_in')['last_login']);

		$data['unsubscriptions'] = $this->Subscription->get_unsubscriptions($this->session->userdata('logged_in')['last_login']);

		$data['subscribers'] = $this->Contact->count_all();

		$data['delivered'] = $this->Outbox->count_it_all(date("Y-m-d", strtotime($data['start_date'])), date("Y-m-d", strtotime($data['end_date'])));
		
		$diff = date_diff(new DateTime($data['start_date']), new DateTime($data['end_date']))->format("%a");

		$data['categories'] = array();

		$data['messages'] = array();

		$data['subscriptions'] = array();

		if($diff <= 30)
		
		{

			for($i = 0; $i <= $diff; $i ++) 

			{

				$data['categories'][] = date("D", strtotime($data['start_date']. ' + '. $i .' days'));

				$data['messages'][] = $this->Outbox->count_for_date(date("Y-m-d", strtotime($data['start_date']. ' + '. $i .' days')));

				$data['subscriptions'][] = $this->Subscription->count_for_date(date("Y-m-d", strtotime($data['start_date']. ' + '. $i .' days'))) - $this->Subscription->count_for_date(date("Y-m-d", strtotime($data['start_date']. ' + '. $i .' days')), false);
		
			}

		}

		else if($diff <= 70)

		{
		
			$count = 0;

					$start_date  = date("Y-m-d", strtotime($data['start_date']));

			$i = 6;
			while(strtotime($start_date) <= strtotime($data['end_date'])) 

			{
				if($count == 0)
					$start_date  = date("Y-m-d", strtotime($data['start_date']));
				else
					$start_date  = date("Y-m-d", strtotime($start_date . ' + '. 7  . ' days'));

				$end_date = date("Y-m-d", strtotime($data['start_date']. ' + '. $i .' days'));
				$fh = fopen("log.txt", "a");
				fwrite($fh, "from: ". $start_date . " to: ". $end_date. "\n");
				fclose($fh);
				$data['categories'][] = "WK ". ($count + 1);

				$data['messages'][] = $this->Outbox->count_for_duration($start_date, $end_date);

				$data['subscriptions'][] = $this->Subscription->count_for_duration($start_date, $end_date) - $this->Subscription->count_for_duration($start_date, $end_date, false);
		
				$count += 1;

				$i += 7;

			}

		} 

		else if($diff > 70)

		{
		
			
			$count = 0;

					$start_date  = date("Y-m-d", strtotime($data['start_date']));

			$i = 30;
			while(strtotime($start_date) <= strtotime($data['end_date'])) 

			{
				if($count == 0)
					$start_date  = date("Y-m-d", strtotime($data['start_date']));
				else
					$start_date  = date("Y-m-d", strtotime($start_date . ' + '. 31  . ' days'));

				$end_date = date("Y-m-d", strtotime($data['start_date']. ' + '. $i .' days'));
				$fh = fopen("log.txt", "a");
				fwrite($fh, "from: ". $start_date . " to: ". $end_date. "\n");
				fclose($fh);
				$data['categories'][] = date("M", strtotime($start_date));

				$data['messages'][] = $this->Outbox->count_for_duration($start_date, $end_date);

				$data['subscriptions'][] = $this->Subscription->count_for_duration($start_date, $end_date) - $this->Subscription->count_for_duration($start_date, $end_date, false);
		
				$count += 1;

				$i += 31;

			}
	
		}

		$this->show('dashboard', $data);

	}

	public function outbox()

	{

		$this->authenticate();

		$page = $this->uri->segment(3);

		$data['outbox'] = $this->Outbox->get_list($this->limit, $page);

		/* Load the 'pagination' library */
		$this->load->library('pagination');
		
		/* Set the config parameters */
		$config['total_rows'] = $this->Outbox->count_all();
		
		$config['base_url'] = base_url() . 'control/outbox';
		
		$config['per_page'] = $this->limit; 

		$config['first_link'] = "&lt;&lt; First";

		$config['last_link'] = "Last &gt;&gt;";

		$config['full_tag_open'] = "<ul class='pagination'>";

		$config['full_tag_close'] ="</ul>";

		$config['num_tag_open'] = '<li>';

		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";

		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";

		$config['next_tag_open'] = "<li>";

		$config['next_tagl_close'] = "</li>";

		$config['prev_tag_open'] = "<li>";

		$config['prev_tagl_close'] = "</li>";

		$config['first_tag_open'] = "<li>";

		$config['first_tagl_close'] = "</li>";

		$config['last_tag_open'] = "<li>";

		$config['last_tagl_close'] = "</li>";

		/* Initialize the pagination library with the config array */
		$this->pagination->initialize($config);
		
		$data['pages'] = $this->pagination->create_links();

		$data['title'] = 'Sent Messages';

		$this->show('outbox', $data);

	}

	public function inbox()

	{


		$this->authenticate();
		
		$page = $this->uri->segment(3);

		$data['inbox'] = $this->Inbox->get_list($this->limit, $page);

		/* Load the 'pagination' library */
		$this->load->library('pagination');
		
		/* Set the config parameters */
		$config['total_rows'] = $this->Outbox->count_all();
		
		$config['base_url'] = base_url() . 'control/inbox';
		
		$config['per_page'] = $this->limit; 

		$config['first_link'] = "&lt;&lt; First";

		$config['last_link'] = "Last &gt;&gt;";

		$config['full_tag_open'] = "<ul class='pagination'>";

		$config['full_tag_close'] ="</ul>";

		$config['num_tag_open'] = '<li>';

		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";

		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";

		$config['next_tag_open'] = "<li>";

		$config['next_tagl_close'] = "</li>";

		$config['prev_tag_open'] = "<li>";

		$config['prev_tagl_close'] = "</li>";

		$config['first_tag_open'] = "<li>";

		$config['first_tagl_close'] = "</li>";

		$config['last_tag_open'] = "<li>";

		$config['last_tagl_close'] = "</li>";

		/* Initialize the pagination library with the config array */
		$this->pagination->initialize($config);
		
		$data['pages'] = $this->pagination->create_links();

		$data['title'] = 'Message Inbox';

		$this->show('inbox', $data);

	}

	public function logout(){
	
		$this->authenticate();
		
	  $this->session->unset_userdata('logged_in');
	  
	  redirect('/control/login', 'refresh');
	  
	}

	public function change()

	{
		$this->authenticate();
		
		if(!empty($this->input->post()))

		{
		
		  if($this->get('new_password') != $this->get('confirm_password'))

		{
		    
		    $this->session->set_flashdata('error', 'Your new passwords do not match!');
		      
		      redirect('/control/change', 'refresh');
		  
		  }

		 else 

		{

		    if($this->User->users_password($this->session->userdata('logged_in')['id'], md5($this->get('password'))) == true)

		{
		    
		      $data = array(
				    "password" => md5($this->get("new_password"))
				    );
		      $this->User->update($this->session->userdata('logged_in')['id'], $data);
		      
		      $this->session->set_flashdata('success', 'Your password was successfully changed');
		
		      redirect('/control', 'refresh');
		    
		 } 

		else

		 { 
		    
		      $this->session->set_flashdata('error', 'Your entered a wrong password!');
		      
		      redirect('/control/change', 'refresh');
		    
		    }
		  
		  }
		
		}
		
		$data ['title'] = 'Change Password';
				
		$this->show('change', $data);
		
	}
	public function send()

	{	
		$this->authenticate();
		
		if(!empty($this->input->post())){

		/******** We need to send sms *********/
			$shortCode = "22560";
			/*
			try 

			{

			  $lastReceivedId = 0;

			  do {

			    $results = $this->gateway->fetchPremiumSubscriptions($shortCode,	$this->get('keyword'), $lastReceivedId);
			    
			    print_r($results); die();

			    foreach($results as $result) {

			      echo $result->phoneNumber."n";

			      $lastReceivedId = $result->id;

			    }

			  } while ( count($results) > 0 ); 

			}

			catch ( AfricasTalkingGatewayException $e )

			{

			  echo "Encountered an error: ".$e->getMessage();

			}
			*/	
			
			$contacts = $this->Contact->get_send_list($this->get('keyword'));
			$receipients = null;
			$i = 0; 
	
			foreach($contacts->result() as $contact)

			{

				if($i > 0)

					$receipients .= ',';

				$receipients .= $contact->telephone;

				$i ++;

			}

			if($receipients != null)
	
				$this->send_sms($receipients, $this->get('message'), $shortCode, $this->get('keyword'));

			else
			
				$this->session->set_flashdata('error', 'There are no channel subscribers to send sms to');

			redirect('/control/send', 'refresh');		

		}

		$data['title'] = 'Send SMS';
		
		$data['keywords'] = $this->Keyword->get_list();
		
		$this->show('send', $data);

	}

	public function login()

	{
		if(!empty($this->session->userdata('logged_in')))
			
			redirect('/control/', 'refresh');

		if(!empty($this->input->post()))

		{
			
			if($this->auth())
			
			{

				redirect('/control/', 'refresh');

			}

			else

			{
				
				$this->session->set_flashdata('error', 'Login was unsuccessful. Username/password is invalid!');

				redirect('/control/login', 'refresh');

			}
	
		}
	
		$data['title'] = 'Login';
	
		$this->show('login', $data);
	}

}

/* End of file control.php */
/* Location: ./application/controllers/control.php */
