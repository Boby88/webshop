<h2>Personal info</h2>
<div class="form-group">
<?php echo form_input(['name' => 'first_name', 'class' => 'form-control', 'placeholder' => 'First name']); ?>
</div>
<div class="form-group">
<?php echo form_input(['name' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Last name']); ?>
</div>

<p>image upload</p>

<h2>Billing info</h2>
<div class="form-group">
<?php echo form_input(['name' => 'zip', 'class' => 'form-control', 'placeholder' => 'Zip code']); ?>
</div>
<div class="form-group">
<?php echo form_input(['name' => 'city', 'class' => 'form-control', 'placeholder' => 'City']); ?>
</div>
<div class="form-group">
<?php echo form_input(['name' => 'address', 'class' => 'form-control', 'placeholder' => 'Address']); ?>
</div>