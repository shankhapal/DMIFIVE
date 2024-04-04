<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrRevenueStatementAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrRevenueStatementAddMoreDetails";


      public function revenueDetails(){
		
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
		
			if(isset($_SESSION['edit_statement_id']))
      { 
        $hide_edit_id = array('id !='=>$_SESSION['edit_statement_id']); 
      }else{ 
        $hide_edit_id = array('id IS NOT NULL'); 
       }
      $added_statement_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();

      $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
      $MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
      $DmiBgrAnalysisAddMoreDetails = TableRegistry::getTableLocator()->get('DmiBgrAnalysisAddMoreDetails');
      $added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
      $added_firm_field = $added_firms[0];	

		
		//taking id of multiple sub commodities	to show names in list	
		$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
		$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();

		  $abc = [];
			return array($abc,$added_statement_details,$sub_commodity_value);
		}

    public function saveRevenueDetails($commodity,$approved_tbl_brand,$grade_designation,$pack_size,$total_quantity,$bmlt_no,$total_estimated_value,$agmark_advance_rc,$agmark_rc_fresh_amt_received,$total_amount,$agmark_Revenue_closing_balance,$remarks){

     
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
                        
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
        'commodity' =>$commodity,
        'approved_tbl_brand' =>$approved_tbl_brand,
        'grade_designation' => $grade_designation,
        'bmlt_no' => $bmlt_no,
        'pack_size' =>$pack_size,
        'total_quantity'=> $total_quantity,
        'total_estimated_value' => $total_estimated_value,
        'agmark_advance_rc'=>$agmark_advance_rc,
        'agmark_rc_fresh_amt'=>$agmark_rc_fresh_amt_received,
        'total_amount'=>$total_amount,
        'agmark_close_balance'=>$agmark_Revenue_closing_balance,
        'remarks'=>$remarks,
				'created'=>date('Y-m-d H:i:s')
			
			)); 

			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}

    public function editRevenueDetails($record_id,$commodity,$approved_tbl_brand,$grade_designation,$bmlt_no,$pack_size,$total_quantity,$total_estimated_value,$agmark_advance_rc,$agmark_rc_fresh_amt,$total_amount,$agmark_close_balance,$remarks){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'commodity'=>$commodity,
				'approved_tbl_brand'=>$approved_tbl_brand,
				'grade_designation'=>$grade_designation,
        'bmlt_no' =>$bmlt_no,
				'pack_size'=>$pack_size,
			  'total_quantity' =>$total_quantity,
        'total_estimated_value' =>$total_estimated_value,
        'agmark_advance_rc' =>$agmark_advance_rc,
        'agmark_rc_fresh_amt' =>$agmark_rc_fresh_amt,
        'total_amount' =>$total_amount,
        'agmark_close_balance' =>$agmark_close_balance,
        'remarks' =>$remarks,
				'modified'=>date('Y-m-d H:i:s')
			)); 
      if($this->save($newEntity)){				
				return true;				
			}			

  }

	public function deleteRevenueDetails($record_id){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}

  }