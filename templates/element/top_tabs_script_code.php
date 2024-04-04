<?php echo $this->Html->script('element/top_tabs_script_code/top_tabs_script_code'); ?>

<!-- For Inspection window -->
<?php
    if ($this->request->getParam('controller') == 'inspections') {

        if ($this->request->getParam('action') == 'pending_applications' || $this->request->getParam('action') == 'renewal_pending_applications' ||
            $this->request->getParam('action') == 'referred_back_applications' || $this->request->getParam('action') == 'renewal_referred_back_applications' ||
            $this->request->getParam('action') == 'replied_applications' || $this->request->getParam('action') == 'renewal_replied_applications' ||
            $this->request->getParam('action') == 'verified_applications' || $this->request->getParam('action') == 'renewal_verified_applications') {

            echo $this->Html->script('element/top_tabs_script_code/if_inspection');
        }

    }


    if ($this->request->getParam('controller') == 'siteinspections') {

		echo $this->Html->script('element/top_tabs_script_code/if_site_inspection');

	}

    if ($this->request->getParam('controller') == 'roinspections') {

		echo $this->Html->script('element/top_tabs_script_code/roinspections');

	}

    if ($this->request->getParam('controller') == 'hoinspections') {

        if ($this->request->getParam('action') == 'dyama_pending' || $this->request->getParam('action') == 'dyama_commented'|| $this->request->getParam('action') == 'dyama_replied') {
            echo $this->Html->script('element/top_tabs_script_code/hoinspections');
        }

        if ($this->request->getParam('action') == 'ho_mo_pending' || $this->request->getParam('action') == 'ho_mo_commented'|| $this->request->getParam('action') == 'ho_mo_replied') {
            echo $this->Html->script('element/top_tabs_script_code/ho_mo');
        }


        if ($this->request->getParam('action') == 'ho_jtama_pending' || $this->request->getParam('action') == 'ho_jtama_commented'|| $this->request->getParam('action') == 'ho_jtama_replied') {
            echo $this->Html->script('element/top_tabs_script_code/ho_jtama');
        }

        if ($this->request->getParam('action') == 'ho_ama_pending' || $this->request->getParam('action') == 'ho_ama_commented'|| $this->request->getParam('action') == 'ho_ama_replied') {
            echo $this->Html->script('element/top_tabs_script_code/ho_ama');
        }
	}








	//For allocation window-->

	if ($this->request->getParam('controller') == 'allocations') {
	    echo $this->Html->script('element/top_tabs_script_code/allocations');
	}


	if ($this->request->getParam('action') == 'pending_forms' ||
        $this->request->getParam('action') == 'allocated_forms' ||
        $this->request->getParam('action') == 'approved_forms' ||
        $this->request->getParam('action') == 'renewal_pending_forms' ||
        $this->request->getParam('action') == 'renewal_allocated_forms' ||
        $this->request->getParam('action') == 'renewal_approved_forms'
        //$this->request->getParam['action'] == 'home'
    ){

		echo $this->Html->script('element/top_tabs_script_code/pending_forms');


	} elseif ($this->request->getParam('action') == 'pending_sites' ||
        $this->request->getParam('action') == 'allocated_sites' ||
        $this->request->getParam('action') == 'approved_sites' ||
        $this->request->getParam('action') == 'renewal_pending_sites' ||
        $this->request->getParam('action') == 'renewal_allocated_sites' ||
        $this->request->getParam('action') == 'renewal_approved_sites')
    {

		echo $this->Html->script('element/top_tabs_script_code/pending_sites');

    } elseif ($this->request->getPara('action') == 'ho_pending' || $this->request->getParam('action') == 'ho_allocated') {

		echo $this->Html->script('element/top_tabs_script_code/ho_pending_or_ho_allocated');

	} elseif ($this->request->getParam('action') == 'ho_mo_pending' || $this->request->getParam('action') == 'ho_mo_allocated') {

		echo $this->Html->script('element/top_tabs_script_code/ho_mo_pending_or_ho_mo_allocated');

	} elseif ($this->request->getParam('action') == 'ho_jtama_pending' || $this->request->getParam('action') == 'ho_jtama_allocated') {

		echo $this->Html->script('element/top_tabs_script_code/ho_jtama_pending_or_ho_jtama_allocated');

	} elseif ($this->request->getParam('action') == 'ho_ama_pending' || $this->request->getParam('action') == 'ho_ama_allocated') {

		echo $this->Html->script('element/top_tabs_script_code/ho_ama_pending_or_ho_ama_allocated');

	}

    ?>
