<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	
</head>
<body>
	<div class="container">
		<div class="login_form">
			<form action="{{ route('admin.signin') }}" method="post">
	        	@csrf

	        	@foreach ($errors->all() as $error)
	            	<div>{{ $error }}</div>
	          	@endforeach
	          	<div class="form-group">
	            <label class="label">Email</label>
	            	<div class="input-group">
	              		<input type="email" class="form-control" name="email" placeholder="email">
	              	<div class="input-group-append">
	                <span class="input-group-text">
	                  <i class="mdi mdi-check-circle-outline"></i>
	                </span>
	              </div>
	            </div>
	          	</div>
	          <div class="form-group">
	            <label class="label">Password</label>
	            <div class="input-group">
	              <input type="password" name="password" class="form-control" placeholder="*********">
	              <div class="input-group-append">
	                <span class="input-group-text">
	                  <i class="mdi mdi-check-circle-outline"></i>
	                </span>
	              </div>
	            </div>
	          </div>
	          <div class="form-group">
	            <button class="btn btn-primary submit-btn btn-block">Login</button>
	          </div>
	          <div class="form-group d-flex justify-content-between">
	            <!-- <div class="form-check form-check-flat mt-0">
	              <label class="form-check-label">
	                <input type="checkbox" class="form-check-input" checked> Keep me signed in </label>
	            </div> -->
	            <a href="#" class="text-small forgot-password text-black">Forgot Password</a>
	          </div>
	          
	        </form>
		</div>

		
	</div>
</body>
</html>