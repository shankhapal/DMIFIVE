<?php ?>
<!-- on 23-10-2017, Below noscript tag added to check if browser Scripting is working or not, if not provided steps --> 
<noscript>
		<?php echo $this->element('javascript_disable_msg_box'); ?>
</noscript>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php
			
		echo $this->Html->meta('icon');
		echo $this->Html->charset();

		//newly added by laxmi on 08/02/2023
		echo $this->Html->css('dataTables.bootstrap.min');
		//Load Datatables Scripts
		echo $this->Html->script('jquery_main.min');
		echo $this->Html->script('jquery.dataTables.min');
		echo $this->Html->script('dataTables.bootstrap.min');
		echo $this->Html->script('dataTables.responsive.min');
		echo $this->Html->script('table_filter');//end


		echo $this->Html->script('responsive.bootstrap.min');
		echo $this->Html->script('jquery-confirm.min');
		
		echo $this->Html->css('custom-style');
		echo $this->Html->css('../site-home-page/layout/styles/layout');
		echo $this->Html->css('bootstrap.min');		
		echo $this->Html->css('document_checklist');
		echo $this->Html->script('jquery_main.min'); //newly added on 24-08-2020 updated js
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('no_back');
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	
<title>Directorate of Marketing & Inspection</title>
</head>
<?php echo $this->element('common_loader'); ?>
<body id="top">

	<header><?php echo $this->element('home-page-elements/home-page-header'); ?> </header>
	<div class="clear"></div>
	
		<div class="container site-page">
			<?php echo $this->fetch('content'); ?>
		</div>
	<?php echo $this->element('home-page-elements/home-page-footer'); ?>

</body>
</html>
