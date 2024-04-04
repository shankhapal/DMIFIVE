<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Core\Configure;

class FeedbacksController extends AppController{

    private $DmiFeedbackTypes;
    private $DmiFeedbacks;
    private $Dmiusers;

    private $layout = '';
    private $autoLayout = '';
	public function beforeFilter($event) {
		parent::beforeFilter($event);

            //Load Components
            // added by shankhpal on 27-03-2024
            $components = [
                'Customfunctions',
                'Createcaptcha',
                'AqcmsWrapper'
            ];

            foreach($components as $component){
                $this->loadComponent($component);
            }


			$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->viewBuilder()->setLayout('form_layout');


        // Call the loadAllModels method to load necessary models.
        $this->loadAllModels();
	}




    /**
     * Loads all necessary models for the current controller.
     * This method is responsible for loading models required for the controller's functionality.
     * Author: Shankhpal Shende
     * Date: 28-03-2024
     */
    private function loadAllModels(): void {

        $this->DmiFeedbackTypes = $this->AqcmsWrapper->customeLoadModel('DmiFeedbackTypes');
		$this->DmiFeedbacks = $this->AqcmsWrapper->customeLoadModel('DmiFeedbacks');
        $this->Dmiusers = $this->AqcmsWrapper->customeLoadModel('Dmiusers');

    }

	//To create captcha code, called from component
	public function createCaptcha(){
		$this->autoRender = false;
		$this->Createcaptcha->createCaptcha();
	}

	public function refreshCaptchaCode(){
		$this->autoRender = false;
		$this->Createcaptcha->refreshCaptchaCode();
		exit;
	}

    //This function is use to check user login or not
	public function userValidation(){


	   $username = $this->Session->read('username');

	   if($username == null){
			$this->customAlertPage("Sorry You are not authorized to view this page..");
		   exit();
	   }else{
			//check if user entry in Dmi_users table for valid user by Mohnish
			$check_user = $this->Dmiusers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();

			if(empty($check_user)){

				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit();

			}
		}
	}


	public function home(){

		$this->viewBuilder()->setLayout('admin_dashboard');
		$this->Customfunctions->userLastLogins();
		$username = $this->Session->read('username');
		$this->set('username',$username);
	}


	public function addFeedbacks() {

		$this->viewBuilder()->setLayout('form_layout');
		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';


		// Fetch list of feedback types,
		$list_of_feedback = $this->DmiFeedbackTypes->find('list', array('keyField' => 'id','valueField' => 'title','conditions'=>array('delete_status IS NULL'),'order'=>'id ASC'))->toArray();
		$this->set('list_of_feedback', $list_of_feedback);

		if ($this->request->is('post')){

			$table = 'DmiFeedbacks';
			$email     = htmlentities($this->request->getData('email'), ENT_QUOTES);
			$type      = htmlentities($this->request->getData('type'), ENT_QUOTES);;
			$firstname = htmlentities($this->request->getData('firstname'), ENT_QUOTES);
			$lastname  = htmlentities($this->request->getData('lastname'), ENT_QUOTES);
			$mobile    = htmlentities($this->request->getData('mobile'), ENT_QUOTES);
			$address   = htmlentities($this->request->getData('address'), ENT_QUOTES);
			$othertype = htmlentities($this->request->getData('other'), ENT_QUOTES);
			$comment   = htmlentities($this->request->getData('comment'), ENT_QUOTES);
			$captcharequest = $this->request->getData('captcha');

			//to check server side validation
			if(!empty($email) && !empty($type) && !empty($firstname)  && !empty($lastname)  && !empty($mobile)  && !empty($address) && !empty($comment) ){
				//captcha check
				if($captcharequest !="" && $this->Session->read('code') == $captcharequest){

					$DmiFeedbacksEntity = $this->DmiFeedbacks->newEntity(array(

						'email'=>base64_encode($email), //for email encoding
						'type'=>$list_of_feedback[$type],
						'first_name'=>$firstname,
						'last_name'=>$lastname,
						'mobile'=>$mobile,
						'address'=>$address,
						'comments'=>$comment,
						'mobile_no'=>base64_encode($mobile), //This is addded on 27-04-2021 for base64encoding by AKASH
						'other_type'=>$othertype,
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s')
					));

					if($this->DmiFeedbacks->save($DmiFeedbacksEntity)){

						$message = 'Feedback Sent Successfully,Thank You.';
						$message_theme = 'success';
						$redirect_to = '../feedbacks/add_feedbacks';

					}else{

						$message = 'Please Check Details Again.';
						$message_theme = 'failed';
						$redirect_to = '../feedbacks/add_feedbacks';

					}

				}else{

					$captcha_error_msg = 'Sorry... Wrong Code Entered';
					$this->set('captcha_error_msg',$captcha_error_msg);
					return null;
					exit;
				}

			}else{

				$this->set('return_error_msg','Please check some fields are not entered');
				return null;
				exit;

			}
		}

		// set variables to show popup messages from view file
	   $this->set('message',$message);
	   $this->set('message_theme',$message_theme);
	   $this->set('return_error_msg',null);
	   $this->set('redirect_to',$redirect_to);

	}


	//functionality to show all feedback list
	public function allFeedback() {

		$this->userValidation();
		$this->viewBuilder()->setLayout('admin_dashboard');


		$all_feedback = $this->DmiFeedbacks->find('all',array('order'=>'id DESC'))->toArray();
		$this->set('all_feedback',$all_feedback);

	}

	public function fetchFeedbackId($id){

		$this->Session->write('feedback_id',$id);
		$this->redirect(array('controller'=>'Feedbacks','action'=>'feedback_details'));

	}

	public function feedbackDetails(){

		$this->userValidation();
		$user_email_id = $this->Session->read('username');
		$this->viewBuilder()->setLayout('admin_dashboard');

		$id = $this->Session->read('feedback_id');


		$feedback_details = $this->DmiFeedbacks->find('all',array('conditions'=>array('id'=>$id)))->first();
		$this->set('feedback_details',$feedback_details);

	}

	//to download excel sheet
	function download(){

		$this->userValidation();


		$array= $this->DmiFeedbacks->find('all',array('order'=>'id DESC'))->toArray();
		$this->set('orders',$array);

		$this->layout = null;
		$this->autoLayout = false;
		Configure::write('debug', false);
	}


}




?>
