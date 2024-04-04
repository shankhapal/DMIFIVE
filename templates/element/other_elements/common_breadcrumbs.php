<?php 
    if ($userType == 'User') { 
        echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));
    } elseif ($userType == 'Primary') { 
        echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'primary_home'));
    } elseif ($userType == 'Chemist') { 
        echo $this->Html->link('Dashboard', array('controller' => 'chemist', 'action'=>'home'));
    } elseif ($userType == 'Secondary') { 
        echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));
    }
?>