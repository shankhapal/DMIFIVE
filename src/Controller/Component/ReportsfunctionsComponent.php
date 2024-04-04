<?php
namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;

class ReportsfunctionsComponent extends Component {

    public array $components= array('Session','Customfunctions');
    public $controller = null;
    public $session = null;


    public function initialize(array $config): void{
        parent::initialize($config);
        $this->Controller = $this->_registry->getController();
        $this->Session = $this->getController()->getRequest()->getSession();

    }

    /*this function used for ESigned Yashwant 20-Mar-2023*/
    public function checkApplicantFormTypeForReports($customer_id,$appl_type=null) {

        $Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');

        $split_customer_id = explode('/',$customer_id);

        //ADDED ON 03-06-2021 BY ANKUR
        if ($customer_id == null)
        {
            $split_customer_id[1] = null;
            $form_type = null;
        }


        if ($split_customer_id[1] == 1)
        {

            if ($this->Customfunctions->checkApplicantExportUnit($customer_id) == 'yes')
            {

                $form_type = 'F';

            }
            else
            {

                $check_application_type = $Dmi_firm->find('all',array('fields'=>array('commodity','export_unit'),'conditions'=>array('customer_id IS'=>$customer_id)))->first();

                //added id '11' on 05-09-2022 for Fat Spread updates after UAT
                if (isset($check_application_type['commodity']) == 106 || isset($check_application_type['commodity']) == 11)
                {

                    $form_type = 'E';

                }
                else
                {

                    $form_type = 'A';
                }


            }

        } elseif ($split_customer_id[1] == 2)
        {

            $form_type = 'B';

        } elseif ($split_customer_id[1] == 3)
        {

            //added this condition to check laboratory form type(export/Non export) //on 31-08-2017 by Amol
            if ($this->Customfunctions->checkApplicantExportUnit($customer_id) == 'yes')
            {

                $form_type = 'C';

            } else {

                $form_type = 'D';
            }

        } elseif ($split_customer_id[0] == 'CHM') { #For Chemist Approval (CHM) - Akash [15/05/2022]

            $form_type = 'CHM';
        }

        //check application type for other type of forms
        //added on 15-11-2021 for other modules applications form type
        if (empty($appl_type))
        {
            $appl_type = $this->Session->read('application_type');
        }

        if ($appl_type == 5) { #For Fifteen Digit Code (FDC) - Amol [15/05/2022]

            $form_type = 'FDC';

        } elseif ($appl_type == 6) { #For Approval of E-Code (EC) - Amol [15/05/2022]

            $form_type = 'EC';

        } elseif ($appl_type == 8) {  #For Approval of Desginated Person (ADP) - Shankhpal [17/11/2022]

            $form_type = 'ADP';
        }

        return $form_type;
    }


    //Yashwant 02 mar-2023

	//This Function Used For IN Process Report New Application Count Showing in kPI's
	public function newApplicantType($customer_id) {

		//$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');

		$split_customer_id = explode('/',$customer_id);

		if ($split_customer_id[1] == 1)
		{
			return "CA";
		}
		elseif ($split_customer_id[1] == 2)
		{
			return "PP";
		}
		elseif ($split_customer_id[1] == 3)
		{
			return "LAB";
		}
	}

}
?>
