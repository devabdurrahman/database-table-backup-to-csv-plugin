<?php 
/*
* Plugin Name: Database Table data CSV backup
* Author: Abdur Rahman
* Description: This plugin will export databse table data into a .CSV file
* Version: 1.0
* Plugin URI: devabdurrrahman.com
* Author URI: devabdurrrahman.com
*/

// plugin menu in admin
// we have to create a page
// export all table data into .csv file

add_action( "admin_menu", "tdcb_create_admin_menu" );
function tdcb_create_admin_menu(){
	add_menu_page('CSV data backup plugin', 'CSV data backup', 'manage_options', 'csv-data-backup', 'tdcb_export_form', 'dashicons-chart-bar', 8);
}

// for layout
function tdcb_export_form(){
	ob_start();

	include_once plugin_dir_path( __FILE__ ) . "/template/table_data_backup.php";

	$layout = ob_get_contents();

	ob_end_clean();

	echo $layout;
}

add_action("admin_init", "tdcb_handle_form_export");
function tdcb_handle_form_export(){
	if(isset($_POST['tdcb_export_button'])){

		global $wpdb;

		$table_name =  $wpdb->prefix . "wp_students_data";

		$students = $wpdb->get_results(
			"SELECT * FROM {$table_name}", ARRAY_A
		);

		if(empty($students)){
			// error message

		}

		$filename = "students_data".time().".csv";

		header("Content-Type: text/csv; charset=utf-8;");
		header("Content-Disposition: attachment; filename=".$filename);

		$output = fopen("php://output", "w");
		fputcsv($output, array_keys($students[0]));

		foreach($students as $student){
			fputcsv($output, $student);
		}

		fclose($output);

		exit();

	}
}