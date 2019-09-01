<?php


session_start() ; 
$pageTitle = 'Home page' ;
include "init.php";
?>
   
<!-- first part  -->
<div class="container">
    <div class="row"> 
  
        <div class="col-md-6 mt-5 mx-auto" style="border-right:solid ; ">
            <img style="height:500px;" class="img-fluid d-block mx-auto "src="jel.jpg" > 
        </div>
        <div class="col-md-6 mt-5 text-center" style="border-left:solid ; ">
            <h2 style="padding:60px ; font-size:50px ; "> مجموعة الربيع  </h2>
            <h4> تالقي مع مجموعة الربيع 2019 مع احدث التشكيلات والعروض والخصومات اللتي تصل الى </h4>
            <span style="font-size: 123px; padding-top: 57px;"> 50% </span>
        </div>
    </div> 
</div>

<!-- second part  -->

<section class="container text-center">
<p> تشكيلة فريده وخاصه لكم انتم </p>
<p>  لدينا ما يجعلك مميزة من احدث التصاميم الخاصه والفريدة من تصميم فتيات سعوديات </p>
<div class="row feat mt-5">
	<div class="col-md-4">
		<img src="support.svg">
	<h3>متواجدون  24/7  </h3>
	<hr style="border: none; border-bottom: 3px solid #ac2035;">
	<p> متواجدون لخدمتكم 24/7 </p>
	</div>
	<div class="col-md-4">
	<img src="calendar.svg">
	<h3> الدقه في المواعيد  </h3>
	<hr style="border: none; border-bottom: 3px solid #ac2035;">
	<p> نلتزم بمواعيدنا في تسليم طلباتكم  </p>
	</div><div class="col-md-4">
	<img src="free-delivery.svg">

	<h3> الشحن المجاني  </h3>
	<hr style="border: none; border-bottom: 3px solid #ac2035;">
	<p> الشحن المجاني داخل السعودية </p>
	</div>
</div>

</section>



<!-- third part  -->


<section >

		<div class="container-fluid">
        <h2 class="h1 text-center" style="padding:60px ; font-size:50px ; ">  احدث العروض </h2>
				<div class="row flex-xl-nowrap">
					<div class="col-md-12 col-xs-6">
						<div class="bd-example">
							<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
	
								<div class="carousel-inner">
									<div class="carousel-item active">
										<div class="block4-wrap">
											<div class="block4"><img class="d-block w-100" src="uploads/avatars/60542_990877906_MV_ZM.jpg" alt="First slide">
											<a href="items.php"><p class="text-center">  سلسال من الفضه </p></a> 
											</div>
											<div class="block4"><img class="d-block w-100" src="uploads/avatars/68392_neckless.jpg" alt="First slide">
                                            <a href="items.php"><p class="text-center">  سلسال من الذهب الابيض </p></a> 

											</div>
											<div class="block4"><img class="d-block w-100" src="uploads/avatars/72097_3-Star-Ring.jpg" alt="First slide">
                                            <a href="items.php"><p class="text-center">  سوارة من الذهب الاصفر </p></a> 

											</div>
										
										</div>
									</div>
									<div class="carousel-item">
											<div class="block4-wrap">
												<div class="block4"> <img class="d-block w-100" src="uploads/avatars/28327_mhaaaaabaood.jpg" alt="Second slide">
                                                <a href="items.php"><p class="text-center">  خاتم الالمآس </p></a> 

												</div>
												<div class="block4"> <img class="d-block w-100" src="uploads/avatars/42421_golden.jpg" alt="Second slide">
                                                <a href="items.php"><p class="text-center">  سلسال من الفضه </p></a> 

												</div>
												<div class="block4"> <img class="d-block w-100" src="uploads/avatars/76397_0001.jpg" alt="Second slide">
                                                <a href="items.php"><p class="text-center">  سلسال من الذهب الابيض </p></a> 

							            </div>
											</div>
										</div>

									<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev"> 
										<i class="fas fa-chevron-left fa-3x" aria-hidden="true"></i> 
										<span class="sr-only">Previous</span> </a> 
										<a class="carousel-control-next" href="#carouselExampleCaptions" 
										role="button" data-slide="next"> 
										<i class="fas fa-chevron-right fa-3x" aria-hidden="true"></i> 
										<span class="sr-only">Next</span> </a> </div>

								</div>
							</div>
						</div>
					</div>
					
</div>

</section>


<section class="container text-center">
<i class="fas fa-gem fa-4x pt-5 pb-5"></i> 
<br> 
<input type="email" class="form-group col-md-7" placeholder="اشترك ليصلك جديدنا" > <br>
<a href="#"> <img src="rotated-right-arrow.svg" style="width:100px;"> </a>


</section>




<?php 
include $tpl.'footer.php';
?> 