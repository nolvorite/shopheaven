    </div><!--row-->   
    </div><!-- /container -->  
    </div>
    <div id="footer">
    	<div class="container">
    		<div class="row" id="footer_columns">
    			<div class="col-md-6 col-lg-3">
    				<h5>Menu</h5>
    				<p class="links">
    					<a href="<?php echo site_url(); ?>">Home</a>
    					<a href="<?php echo site_url(); ?>main/products">Products</a>
                        <a href="<?php echo site_url(); ?>main/orders">Orders</a>
                        <a href="<?php echo site_url(); ?>main/admin">Admin</a>

    				</p>
    			</div>
    			<div class="col-md-6 col-lg-3">
    				<h5>Contact Info</h5>
    				<p class="textt">
    					1234 Address St.
    				</p>
    				<p class="textt">
    					<strong>Phone:</strong> 1-666-420-6969<br>
    					<strong>Email:</strong> rapbeh69420@gmail.com
    				</p>
    			</div>
    			<div class="col-md-6 col-lg-3">
    				<h5>Social Media</h5>
    				<p class="links">
    					<a href="<?php echo site_url(); ?>">Facebook</a>
    					<a href="<?php echo site_url(); ?>">Twitter</a>
    					<a href="<?php echo site_url(); ?>">Instagram</a>
    				</p>
    			</div>
    		</div>
    	</div>
    </div>

    <script src="<?php echo base_url(); ?>public/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("body").on("change","#quantity",function(){
                quantity = parseInt($("#quantity").val());
                price = $("#price").val().replace("PHP ","");
                price = parseFloat(price);
                console.log(quantity,price);
                total = (quantity * price);
                $("#total").val("PHP " +total);
            });

            $("#file_uploader").on("change",function(){
                dt = new FormData($("#uploader")[0]);
                $.ajax({
                    url: '<?php echo site_url();?>images/upload',
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: dt,
                    dataType: 'json',
                    success: function(response){
                        console.log(response);
                        if(response.success){
                            $("#file_uploader").after("<div id='image_handle'><img src='<?php echo site_url();?>images/"+ response.data.picture +"' alt='ID' class='img-fluid'></div><input type='hidden' name='picture_id' value='"+ response.data.picture +"'>").detach();
                        }
                    }, fail: function(error){
                        console.log(error);
                    }
                })
            });
        });
    </script>

    </body>
</html>