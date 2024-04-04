<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrStatementAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrStatementAddMoreDetails";


        public function statementDetails(){
		
      
     
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
		
			if(isset($_SESSION['edit_analysis_id']))
      { 
        $hide_edit_id = array('id !='=>$_SESSION['edit_analysis_id']); 
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

    	public function saveStatementDetails($date,$commodity,$approved_tbl_brand,$agmark_grade,$pack_size,$from_a,$to_a,$total_a,$from_b,$to_b,$total_b,$from_c,$to_c,$total_c,$from_d,$to_d,$total_d,$from_e,$to_e,$total_e,$total_q,$remark){
	
     
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
                        
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'date' =>$date,
        'commodity' =>$commodity,
        'approved_tbl_brand' =>$approved_tbl_brand,
        'agmark_grade' =>$agmark_grade,
        'pack_size' =>$pack_size,
        'from_a' =>$from_a,
        'to_a' =>$to_a,
        'total_a' =>$total_a,
        'from_b' =>$from_b,
        'to_b' =>$to_b,
        'total_b' =>$total_b,
        'from_c' =>$from_c,
        'to_c' =>$to_c,
        'total_c' =>$total_c,
        'from_d' =>$from_d,
        'to_d' =>$to_d,
        'total_d' =>$total_d,
        'from_e' =>$from_e,
        'to_e' =>$to_e,
        'total_e' =>$total_e,
        'total_q' =>$total_q,
        'remark' =>$remark,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
	
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}

    public function editStatementDetails($record_id,$date,$commodity,$approved_tbl_brand,$agmark_grade,$pack_size,$from_a,$to_a,$total_a,$from_b,$to_b,$total_b,$from_c,$to_c,$total_c,$from_d,$to_d,$total_d,$from_e,$to_e,$total_e,$total_q,$remark){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'date'=>$date,
				'commodity'=>$commodity,
				'approved_tbl_brand'=>$approved_tbl_brand,
				'agmark_grade'=>$agmark_grade,
				'pack_size'=>$pack_size,
				'from_a'=>$from_a,
				'to_a'=>$to_a,
				'total_a'=>$total_a,
				'from_b'=>$from_b,
				'to_b'=>$to_b,
				'total_b'=>$total_b,
				'from_c'=>$from_c,
				'to_c'=>$to_c,
				'total_c'=>$total_c,
				'from_d'=>$from_d,
				'to_d'=>$to_d,
				'total_d'=>$total_d,
				'from_e'=>$from_e,
				'to_e'=>$to_e,
				'total_e'=>$total_e,
				'total_q'=>$total_q,
				'remark'=>$remark,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
      if($this->save($newEntity)){				
				return true;				
			}			

  }

	public function deleteStatementDetails($record_id){
			
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