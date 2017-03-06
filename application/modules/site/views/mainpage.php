<div class="container-fluid">
	<div class="row">
		<div class="container-fluid">
			<h1>Főoldal</h1>
		</div>
	</div>
	<?php $products = 12; ?>
	<?php for($i=0; $i < $products; $i++):?>
	<?php if($i % 4 == 0):?>
	<div class="row">
	<?php  endif; ?>
		<div class="col-xs-3 product">
			<div class="row">
				<div class="col-xs-12">
					<h6>
						<a href="<?php echo site_url(array('product','details',$i));?>">Product name #<?php echo ($i+1);?></a>
					</h6>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<a href="<?php echo site_url(array('product','details',$i));?>">
						<img class="img-responsive" src="<?php echo base_url('assets/img/'); ?>empty_product_150x150.jpg"/>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="container-fluid">Size</div>
					</div>
					<div class="row">
						<div class="container-fluid">Color</div>
					</div>
					<div class="row">
						<div class="container-fluid">Price</div>
					</div>
					<div class="row">
						<div class="container-fluid">
							<button class="btn btn-warning btn-xs">Add to cart</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php if($i % 4 == 3):?>
	</div>
	<?php endif; ?>
	<?php endfor; ?>

</div>



