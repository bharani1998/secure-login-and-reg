<?php include("includes/header.php")?>
<?php include("includes/navi.php")?>



	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
<?php validate()?>
								
		</div>



	</div>
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="login.php">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="register.php" class="active" id="">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="register-form" method="post" role="form" >

									
									<div class="form-group">
										<input type="text" name="name" id="name" tabindex="1" class="form-control" placeholder="Name" value="" required >
									</div>
                                <div class="form-group">
										<input type="email" name="mail" id="mail" tabindex="1" class="form-control" placeholder="Mail" value="" required >
									</div>    
                                <div class="form-group">
                                    
                                    
                                
                                    
                                    </div>  
                                    
                                    
                                    <div class="form-group">
										<input type="text" name="city" id="city" tabindex="1" class="form-control" placeholder="City" value="" required >
									</div>
                                    
									<div class="form-group">
										<input type="tel" name="phone" pattern="[0-9]{10}" id="phone" tabindex="2" class="form-control" placeholder="Phone number" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
									</div>
                                    <div class="form-group">
										<input type="password" name="cpassword" id="cpassword" tabindex="2" class="form-control" placeholder="Confirm Password" required>
									</div>
                                    
                                    
                                    
        
                                    
                                    
                                    
                                    <div class="form-group">
    
    <input type="text"name="address" class="form-control" id="Address" placeholder="Address">
  </div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php include("includes/footer.php")?>
