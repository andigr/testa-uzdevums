<?php
require('helper.php');
require('classes/city.class.php');
require('classes/contact.class.php');
require('classes/validation.class.php');
require('classes/form_error.class.php');

$mysqli = make_connection();

$city = new City($mysqli);
$citiesJSON = $city->get_json();

$report_result_array = array();
$errors = new FormError();
if(isset($_POST['add-contact'])) {
	$add_contact_data = $_POST['add-contact'];

	$city_name = $add_contact_data['city'];
	$name = $add_contact_data['name'];
	$email = $add_contact_data['email'];
	$phone = $add_contact_data['phone'];

	if(!Validation::length_interval($city_name, 3, 100)) {
		$errors->add_error("city", "Pilsetas nosaukumam jābūt no 3 līdz 100 simboliem garam");
	}
	if(!Validation::length_interval($name, 1, 100)) {
		$errors->add_error("name", "Vārdam jābūt no 1 līdz 100 simboliem garam");
	}
	if(!Validation::email($email)) {
		$errors->add_error("email", "Nav derīga e-pastas adrese");	
	}
	if(!Validation::length_interval($phone, 1, 20)) {
		$errors->add_error("phone", "Tālrunim jābūt no 1 līdz 20 simboliem garam");
	}

	if(!$errors->has_any()) {
		$city_id = $city->find_or_create($city_name);
		$contact = new Contact($mysqli);
		$contact->create($city_id, $name, $email, $phone);

		$report_result = $city->report();
		$report_result_array = mysqli_result_to_array($report_result);
	}

}
$mysqli->close();
?><!doctype html>
<html>
<head>
<title>Uzdevums</title>
<meta charset='utf-8'>
<link href="assets/main.css" rel="stylesheet">
</head>
<body>
<div id="json-container" data-json='<?php echo $citiesJSON;?>'></div>
<div class="container">
	<?php if($errors->has_any()) echo $errors->output_html(); ?>
	<div class="form-container">
		<form action="" method="POST">
			<div class="form-group">
				<div class="form-left"><label for="city" >Pilsēta</label></div>
				<div class="form-right">
					<input type="text" id="city" name="add-contact[city]" value="<?php echo set_value('city'); ?>" 
						class="<?php if($errors->has_error('city')) echo 'error'?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="form-left"><label for="name" >Vārds</label></div>
				<div class="form-right">
					<input type="text" id="name" name="add-contact[name]" value="<?php echo set_value('name'); ?>" 
						class="<?php if($errors->has_error('name')) echo 'error'?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="form-left"><label for="email" >E-pasts</label></div>
				<div class="form-right">
					<input type="text" id="email" name="add-contact[email]" value="<?php echo set_value('email'); ?>" 
						class="<?php if($errors->has_error('email')) echo 'error'?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="form-left"><label for="phone" >Tālrunis</label></div>
				<div class="form-right">
					<input type="text" id="phone" name="add-contact[phone]" value="<?php echo set_value('phone'); ?>" 
						class="<?php if($errors->has_error('phone')) echo 'error'?>"/>
				</div>
			</div>
			<div class="form-group">
				<input type="submit" name="add-contact[submit]" value="Nosūtīt">
			</div>
		</form>
	</div>
	<?php if(count($report_result_array)) echo cities_report_html($report_result_array); ?>
</div>
<script src="assets/jquery.js"></script>
<script src="assets/jquery.autocomplete.js"></script>
<script>
var cities = $('#json-container').data('json');
$('#city').autocomplete({lookup: cities});
</script>
</body>
</html>