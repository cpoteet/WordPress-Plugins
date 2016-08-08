<?php
/*
Plugin Name: Harvest Reports
Plugin URI: http://www.siolon.com/
Description: Returns report via Harvest API.  Please configure upon activation on the <a href="options-general.php?page=harvestoptions">options page</a>.
Author: Chris Poteet
Author URI: http://www.siolon.com/
Version: 1.0
*/ 

function retriveapi() {
		
	$harvesturl = get_option('harvesturl');
	$harvestemail = get_option('harvestemail');
	$harvestpassword = get_option('harvestpassword');
	$harvestprojectid = get_option('harvestprojectid');
	$harveststart = get_option('harveststart');
	$harvestend = get_option('harvestend');
	$harvesttitle = get_option('harvesttitle');
	
	$auth = $harvestemail.":".$harvestpassword; 
	$authentication = base64_encode($auth);
	$authenticationstring = "Authorization: Basic ";
	$authenticationstring .= (string)$authentication;
	
	/* Check for nulls on end date */
	
	if ($harvestend == NULL) {
		$harvestend = date("Ymd");
	}
	
	/* Build REST query string */
	
	$url = 'http://'.$harvesturl.'.harvestapp.com/projects/'.$harvestprojectid.'/entries?from='.$harveststart.'&to='.$harvestend.'';

	// Initialize request to Harvest API
	
	$curl = curl_init();
	
	$header[0] = "Accept: application/xml";
	$header[] = "Content-Type: application/xml";
	$header[] = $authenticationstring;

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	
	$responseXML = curl_exec($curl); // execute the curl command

	curl_close($curl); // close the connection

	$xml = new SimpleXMLElement($responseXML);
	
	$oddcomment = 'alternate';
	
	?>
	<div class="wrap">
		<h2 style="margin-bottom: 10px;"><?php echo $harvesttitle; ?></h2>
	<table class="widefat">
		<thead>
		<tr class="thead">
			<th>Task</th>
			<th>Notes</th>
			<th>Hours</th>
			<th>Cost</th>
		</tr>
		</thead>
		<tbody id="users" class="list:user user-list">
		<?php
		foreach($xml as $url) {

			/* Get task name and billing rate */
			
			$taskid = (string)$url->{'task-id'};
			$taskinfo = gettasknames($taskid);
			$taskname = $taskinfo[0];
			$hourlyrate = $taskinfo[1];

			/* Calculate costs */

			$time = $url->hours;
			$time = (float)$time;
			$hourlyrate = (float)$hourlyrate;
			$cost = $time * $hourlyrate;
			$class = $oddcomment = ( empty( $oddcomment ) ) ? 'alternate' : ''; // For alternate styles on tr
			
			echo "<tr class='".$class."'><td>".$taskname."</td>";
			echo "<td>{$url->notes}</td><td>".$time."</td><td>".$cost."</td></tr>";
			$sum += $time;

		} ?>
		<tr><td colspan="3" align="right"><strong>Total:</strong> </td>
		<td>$<?php echo number_format($sum * $hourlyrate, 2, '.', ',') ?></td>
		</tr>

		</tbody>
		</table>
		
		<p><a href="http://<?php echo $harvesturl; ?>.harvestapp.com/">Harvest Dashboard</a></p>
		
		</div>
<?php

}

function addoptions() {

	if ($_REQUEST['submit']) {
		updateoptions();
	}	
	
	$harvesturl = get_option('harvesturl');
	$harvestemail = get_option('harvestemail');
	$harvestpassword = get_option('harvestpassword');
	$harvestprojectid = get_option('harvestprojectid');
	$harveststart = get_option('harveststart');
	$harvestend = get_option('harvestend');
	$harvesttitle = get_option('harvesttitle');
	
	?>
	<style type="text/css">
		.optionstable th {vertical-align: top; text-align: right;}
		.optionstable td {text-align: left;}
	</style>
	<h2>Harvest Reporting Options</h2>
		<div class="wrap">
		<table class="optionstable form-table">
		<form action="" method="post">		
		<?php wp_nonce_field('update-options') ?>	

		<tr>
			<th scope="row">URL:</th>
			<td><input type="text" name="harvesturl" value="<?php echo $harvesturl; ?>" />.harvestapp.com</td>
		</tr>
		<tr>
			<th scope="row">E-mail:</th>
			<td><input type="text" name="harvestemail" value="<?php echo $harvestemail; ?>" /></td>
		</tr>
		<tr>
			<th scope="row">Password:</th>
			<td><input type="password" name="harvestpassword" value="<?php echo $harvestpassword; ?>" /></td>
		</tr>
		<tr>
			<th scope="row">Project ID:</th>
			<td> <input type="text" name="harvestprojectid" value="<?php echo $harvestprojectid; ?>" /></td>
		</tr>
		<tr>
			<th scope="row">Report Start Date:</th>
			<td> <input type="text" name="harveststart" value="<?php echo $harveststart; ?>" /> (format: YYYYMMDD)</td>
		</tr>
		<tr>
			<th scope="row">Report End Date:</th>
			<td> <input type="text" name="harvestend" value="<?php echo $harvestend; ?>" /> (Leave blank to extend until the current date)</td>
		</tr>		
		<tr>
			<th scope="row">Report Title: </th>
			<td><input type="text" name="harvesttitle" value="<?php echo $harvesttitle; ?>" /> (e.g. "Time Sheet")</td>
		</tr>		
		<tr>
			<td colspan="2" class="submit"><input type="submit" name="submit" value="Update Options &raquo;" /></td>
		</tr>		
	</form>
	</table>
	</div>
<?php	
}

function updateoptions() {
	
	$updated = false;	
		
	if ($_REQUEST['harvesturl']) {
		update_option('harvesturl', $_REQUEST['harvesturl']);
		$updated = true;
	}
	if ($_REQUEST['harvestemail']) {
		update_option('harvestemail', $_REQUEST['harvestemail']);
		$updated = true;
	}
	if ($_REQUEST['harvestpassword']) {
		update_option('harvestpassword', $_REQUEST['harvestpassword']);
		$updated = true;
	}
	if ($_REQUEST['harvestprojectid']) {
		update_option('harvestprojectid', $_REQUEST['harvestprojectid']);
		$updated = true;
	}
	if ($_REQUEST['harvesttitle']) {
		update_option('harvesttitle', $_REQUEST['harvesttitle']);
		$updated = true;
	}
	if ($_REQUEST['harveststart']) {
		update_option('harveststart', $_REQUEST['harveststart']);
		$updated = true;
	}
	if ($_REQUEST['harvestend']) {
		update_option('harvestend', $_REQUEST['harvestend']);
		$updated = true;
	}
	if ($updated) {
        echo '<div id="message" class="updated fade">';
    	echo '<p>Options Updated</p>';
        echo '</div>';
    } else {
        echo '<div id="message" class="error fade">';
        echo '<p>Unable to update options</p>';
        echo '</div>';
    }
}

function addharvestoptions() {
     add_option("harvesturl","","");
     add_option("harvestemail","","");
	 add_option("harvestpassword","","");
	 add_option("harvestprojectid","","");
	 add_option("harveststart","","");
	 add_option("harvestend","","");
	 add_option("harvesttitle","","");
}

function deleteharvestoptions() {
     delete_option("harvesturl");
     delete_option("harvestemail");
     delete_option("harvestpassword");
     delete_option("harvestprojectid");
     delete_option("harveststart");
     delete_option("harvestend");	 
     delete_option("harvesttitle");	 
}

function gettasknames($taskid) {
	
	$harvesturl = get_option('harvesturl');
	$harvestemail = get_option('harvestemail');
	$harvestpassword = get_option('harvestpassword');
	
	$auth = $harvestemail.":".$harvestpassword; 
	$authentication = base64_encode($auth);
	$authenticationstring = "Authorization: Basic ";
	$authenticationstring .= (string)$authentication;

	$url = 'http://'.$harvesturl.'.harvestapp.com/tasks/';
	$url .= $taskid;
	
	$header[0] = "Accept: application/xml";
	$header[] = "Content-Type: application/xml";
	$header[] = $authenticationstring;

	$curl2 = curl_init();
	curl_setopt($curl2, CURLOPT_URL, $url);
	curl_setopt($curl2, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl2, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl2, CURLOPT_TIMEOUT, 10);
	
	$responseXML = curl_exec($curl2);
	curl_close($curl2); // close the connection
	
	$xml = new SimpleXMLElement($responseXML);

	$taskinfo[0] = $xml->name;
	$taskinfo[1] = $xml->{'default-hourly-rate'};
	return $taskinfo;
}

function report_admin_menu() {
	$harvesttitle = get_option('harvesttitle');
	add_management_page(''.$harvesttitle.'', ''.$harvesttitle.'', 8, '__FILE__', 'retriveapi');
}

function addoptionspage() {
	add_options_page('Harvest Reports', 'Harvest Reports', 8, 'harvestoptions', 'addoptions');
}
	
add_action('admin_menu','addoptionspage');
add_action('admin_menu','report_admin_menu');
register_activation_hook(__FILE__,"addharvestoptions");
register_deactivation_hook(__FILE__,"deleteharvestoptions");
?>
