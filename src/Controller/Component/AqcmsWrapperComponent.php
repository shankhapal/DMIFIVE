<?php
namespace app\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AqcmsWrapperComponent extends Component {

	public $controller = null;
	public $session = null;

	public function initialize(array $config): void {
		parent::initialize($config);
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();

	}


    public function customeLoadModel($table){

        return TableRegistry::getTableLocator()->get($table);
    }

}
