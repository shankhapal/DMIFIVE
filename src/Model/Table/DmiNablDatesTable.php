<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;

class DmiNablDatesTable extends Table{
	
    public function getNablDate($customer_id) {
        $result = $this->find()->where(['customer_id' => $customer_id])->last();
        return $result ?: null;
    }
    
			
    //updating mo ro comments table
    public function updateNablDate($customer_id,$date){
            
        $CustomersController = new CustomersController;
        $nabl_accreditated_upto = $CustomersController->Customfunctions->dateFormatCheck(htmlentities($date, ENT_QUOTES));

        $newEntity = $this->newEntity(array(
            'customer_id'=>$customer_id,
            'nabl_date'=>chop($nabl_accreditated_upto,' 00:00:00'),
            'created'=>date('Y-m-d H:i:s')
        ));
        
        return $this->save($newEntity);

    }

}