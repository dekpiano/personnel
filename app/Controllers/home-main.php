


	<!-- Start slider area -->
	<div id="mu-slider">
		<div class="mu-slide">

		<?php foreach ($sildbanner->result() as $v_sild) { ?>
			<!-- Start single slide  -->
			<div class="sild-img">
				<img src="<?php echo base_url();?>uploads/sildbanner/<?=$v_sild->sild_img;?>" alt="slider img" class="img-fluid" style="width:100%">
				<div class="mu-single-slide-content-area">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="mu-single-slide-content">
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End single slide  -->
		<?php } ?>

		</div>
	</div>
	<!-- End Slider area -->
	
	<!-- Start main content -->
	<main>
		<!-- Start About -->
		<section id="mu-about">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-about-area" style="padding: 90px 10px 0;">
							<!-- Title -->
							<div class="row">
								<div class="col-md-12">
									<div class="mu-title">
										<h2>&#3586;&#3656;&#3634;&#3623;&#3611;&#3619;&#3632;&#3594;&#3634;&#3626;&#3633;&#3617;&#3614;&#3633;&#3609;&#3608;&#3660;</h2>
									</div>
								</div>
							</div>
							<!-- Start Feature Content -->
							<div class="container">
								<div class="row">

								<?php foreach ($news->result() as $v_news) { ?> 
									<div class="col-lg-3 col-md-4 col-sm-6 col-12 mix c">
										<div class="card h-100">
											<a href="<?php echo base_url('news/'.$v_news->news_id);?>">
											<?php if($v_news->news_img !=""): ?>
											<img src="<?php echo base_url();?>/uploads/news/<?=$v_news->news_img;?>" alt="$v_news->news_title" class="img-responsive"></a>									
											<?php else:?>	
											<img src="<?php echo base_url();?>/uploads/img.jpg?>" alt="" class="img-responsive"></a>	
											<?php endif; ?>
											<div class="card-body">
											<h5 class="card-title">
												<a href="<?php echo base_url('news/'.$v_news->news_id);?>">
													<?=$v_news->news_title;?>
												</a>
											</h5>
											</div>
											<div class="card-footer">
											<small class="text-muted">
												<span class="meta-part"><i class="fa fa-calendar-o"></i> 
												<?php $d = $v_news->news_date;
												echo thai_date_short(strtotime($d));
												?>
												</span>
												<span class="meta-part"><i class="fa fa-newspaper-o"></i> <?=$v_news->news_type;?></span>
												
											</small>
											</div>
										</div>
									</div>
								<?php } ?>
								</div>
							</div>			
							<!-- End Feature Content -->
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End About -->
		<hr>
		<!-- Start Clients -->
		<div id="mu-clients">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-clients-area" style="padding: 10px 0;">

							<!-- Start Clients brand logo -->
							<div class="mu-clients-slider">
							<?php foreach ($insti->result() as $v_insti) { ?>
								<div class="mu-clients-single">
									<a href="<?=$v_insti->insti_link?>" target="_blank"><img src="uploads/institution/<?=$v_insti->insti_img?>" alt="<?=$v_insti->insti_title?>" ></a>
								</div>								
							<?php } ?>
							</div>
							<!-- End Clients brand logo -->

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Clients -->
		<!-- Start Counter -->
		<section id="mu-counter">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-counter-area">
                  
							<div class="mu-counter-block">
								<div class="row">
                                
									<!-- Start Single Counter -->
									<div class="col-md-3 col-sm-6">
										<div class="mu-single-counter">
											<span class="fa fa-user"></span>
											<div class="mu-single-counter-content">
												<div class="counter-value" data-count="1085">0</div>
												<h5 class="mu-counter-name">&#3609;&#3633;&#3585;&#3648;&#3619;&#3637;&#3618;&#3609;&#3607;&#3633;&#3657;&#3591;&#3627;&#3617;&#3604;</h5>
											</div>
										</div>
									</div>
									<!-- / Single Counter -->

									<!-- Start Single Counter -->
									<div class="col-md-3 col-sm-6">
										<div class="mu-single-counter">
											<span class="fa fa-graduation-cap"></span>
											<div class="mu-single-counter-content">
												<div class="counter-value" data-count="382">0</div>
												<h5 class="mu-counter-name">&#3592;&#3610;&#3585;&#3634;&#3619;&#3624;&#3638;&#3585;&#3625;&#3634;</h5>
											</div>
										</div>
									</div>
									<!-- / Single Counter -->

									<!-- Start Single Counter -->
									<div class="col-md-3 col-sm-6">
										<div class="mu-single-counter">
											<span class="fa fa-diamond"></span>
											<div class="mu-single-counter-content">
												<div class="counter-value" data-count="68">0</div>
												<h5 class="mu-counter-name">&#3588;&#3619;&#3641;&#3612;&#3641;&#3657;&#3626;&#3629;&#3609;</h5>
											</div>
										</div>
									</div>
									<!-- / Single Counter -->

									<!-- Start Single Counter -->
									<div class="col-md-3 col-sm-6">
										<div class="mu-single-counter">
											<span class="fa fa-flask"></span>
											<div class="mu-single-counter-content">
												<div class="counter-value" data-count="9">0</div>
												<h5 class="mu-counter-name">&#3627;&#3657;&#3629;&#3591;&#3649;&#3621;&#3655;&#3610; </h5>
											</div>
										</div>
									</div>
									<!-- / Single Counter -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- End Counter -->

	

		<section id="mu-service">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-service-area">
							<!-- Title -->
							<div class="row">
								<div class="col-md-12">
									<div class="mu-title">
										<h2>&#3612;&#3621;&#3591;&#3634;&#3609;&#3607;&#3637;&#3656;&#3626;&#3635;&#3588;&#3633;&#3597;</h2>
										<p>&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609;&#3648;&#3607;&#3614;&#3624;&#3634;&#3621;&#3634;&#3611;&#3619;&#3632;&#3594;&#3634;&#3626;&#3619;&#3619;&#3588;&#3660;</p>
									</div>
								</div>
							</div>
							<!-- Start Service Content -->
							<div class="row">
								<div class="col-md-8">
									<div class="mu-service-content">
										<div class="row">
											<!-- Start single service -->
											<div class="col-md-6">
												<div class="mu-single-service">
													<div class="mu-single-service-icon"><i class="fa fa-group" aria-hidden="true"></i></div>
													<div class="mu-single-service-content">
														<h3>&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609;&#3614;&#3619;&#3632;&#3619;&#3634;&#3594;&#3607;&#3634;&#3609;</h3>
														<p>&#3619;&#3632;&#3604;&#3633;&#3610;&#3617;&#3633;&#3608;&#3618;&#3634;&#3624;&#3638;&#3585;&#3625;&#3634; &#3586;&#3609;&#3634;&#3604;&#3585;&#3621;&#3634;&#3591; 2547</p>
													</div>
												</div>
											</div>
											<!-- End single service -->
											<!-- Start single service -->
											<div class="col-md-6">
												<div class="mu-single-service">
													<div class="mu-single-service-icon"><i class="fa fa-university" aria-hidden="true"></i></div>
													<div class="mu-single-service-content">
														<h3>&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609;&#3617;&#3634;&#3605;&#3619;&#3600;&#3634;&#3609;&#3626;&#3634;&#3585;&#3621;</h3>
														<p>World Class Standard School &#3626;&#3635;&#3609;&#3633;&#3585;&#3591;&#3634;&#3609;&#3588;&#3603;&#3632;&#3585;&#3619;&#3619;&#3617;&#3585;&#3634;&#3619;&#3585;&#3634;&#3619;&#3624;&#3638;&#3585;&#3625;&#3634;&#3586;&#3633;&#3657;&#3609;&#3614;&#3639;&#3657;&#3609;&#3600;&#3634;&#3609;</p>
													</div>
												</div>
											</div>
											<!-- End single service -->
											<!-- Start single service -->
											<div class="col-md-6">
												<div class="mu-single-service">
													<div class="mu-single-service-icon"><i class="fa fa-cogs" aria-hidden="true"></i></div>
													<div class="mu-single-service-content">
														<h3>&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609;&#3588;&#3640;&#3603;&#3608;&#3619;&#3619;&#3617;&#3594;&#3633;&#3657;&#3609;&#3609;&#3635;&#3605;&#3657;&#3609;&#3649;&#3610;&#3610;</h3>
														<p>&#3592;&#3634;&#3585;&#3626;&#3635;&#3609;&#3633;&#3585;&#3591;&#3634;&#3609;&#3648;&#3586;&#3605;&#3614;&#3639;&#3657;&#3609;&#3607;&#3637;&#3656;&#3609;&#3588;&#3619;&#3626;&#3623;&#3619;&#3619;&#3588;&#3660; &#3648;&#3586;&#3605; 2</p>
													</div>
												</div>
											</div>
											<!-- End single service -->
											<!-- Start single service -->
											<div class="col-md-6">
												<div class="mu-single-service">
													<div class="mu-single-service-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
													<div class="mu-single-service-content">
														<h3>&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609;&#3619;&#3634;&#3591;&#3623;&#3633;&#3621; Best of the Best</h3>
														<p>&#3650;&#3588;&#3619;&#3591;&#3585;&#3634;&#3619;&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609; ToPSTAR &#3592;&#3634;&#3585;&#3626;&#3606;&#3634;&#3610;&#3633;&#3609;&#3623;&#3636;&#3592;&#3633;&#3618;&#3649;&#3621;&#3632;&#3614;&#3633;&#3602;&#3609;&#3634;&#3585;&#3634;&#3619;&#3648;&#3619;&#3637;&#3618;&#3609;&#3619;&#3641;&#3657;</p>
													</div>
												</div>
											</div>
											<!-- End single service -->										
									
										</div>
									</div>
								</div>

                                <div class="col-md-4">
                                    <div class="mu-service-content" style="text-align: center;">
                                        <img src="<?php echo base_url();?>assets/images/pa.png" style="max-width: 60%;">
										<div>&#3612;&#3629;.&#3626;&#3617;&#3588;&#3636;&#3604;  &#3648;&#3592;&#3619;&#3636;&#3597;&#3626;&#3640;&#3586; <br> &#3612;&#3641;&#3657;&#3629;&#3635;&#3609;&#3623;&#3618;&#3585;&#3634;&#3619;&#3626;&#3606;&#3634;&#3609;&#3624;&#3638;&#3585;&#3625;&#3634;</div>
                                    </div>
                                </div>

							</div>
							<!-- End Service Content -->
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Start Video -->
		<div id="mu-video">
			<div class="row">
				<div class="col-md-6">
					<div class="mu-video-left">
						<a href="#" class="mu-video-play-btn"><i class="fa fa-play" aria-hidden="true"></i></a>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mu-video-right">
						<p>&#3623;&#3636;&#3604;&#3637;&#3650;&#3629;&#3605;&#3656;&#3634;&#3591; &#3654; &#3586;&#3629;&#3591;&#3650;&#3619;&#3591;&#3648;&#3619;&#3637;&#3618;&#3609;&#3648;&#3607;&#3614;&#3624;&#3634;&#3621;&#3634;&#3611;&#3619;&#3632;&#3594;&#3634;&#3626;&#3619;&#3619;&#3588;&#3660;</p>
						<p><a target="_black" href="https://www.youtube.com/channel/UC4rECycGV-TeangFMjWYIAA">&#3604;&#3641;&#3623;&#3636;&#3604;&#3637;&#3650;&#3629;&#3629;&#3639;&#3656;&#3609; &#3654; .. &#3607;&#3637;&#3656;&#3609;&#3637;&#3656;</a> </p>
					</div>
				</div>
			</div>

			<!-- Start Video Popup -->
			<div class="mu-video-popup">
				<div class="mu-video-iframe-area">
					<a class="mu-video-close-btn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
					<iframe width="854" height="480" src="https://www.youtube.com/embed/kEN4F5b-hE4" allowfullscreen=""></iframe>
				</div>
			</div>
			<!-- End Video Popup -->

		</div>
		<!-- End Video -->

		<!-- Start Portfolio -->
		<section id="mu-portfolio">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-portfolio-area">
							<!-- Title -->
							<div class="row">
								<div class="col-md-12">
									<div class="mu-title">
										<h2>&#3616;&#3634;&#3614;&#3585;&#3636;&#3592;&#3585;&#3619;&#3619;&#3617;</h2>								
									</div>
								</div>
							</div>

							<div class="row">
						<!-- Start Portfolio Content -->
								<?php foreach ($photo->result() as $v_photo ) { ?>
									<div class="col-xs-6 col-sm-6 col-md-4 col-6 filtr-item" data-category="4">
										<a class="" href="<?=$v_photo->photo_album;?>" title="PAINTING" target="_black">
												<img class="img-responsive" src="<?=$v_photo->photo_imgmain;?>" alt="image">
												<div class="mu-filter-item-content">
													<h4 class="mu-filter-item-title"><?=$v_photo->photo_title;?></h4>
													<span class="fa fa-long-arrow-right"></span>
												</div>
										</a>
									</div>
								<?php } ?>
						<!-- End Portfolio Content -->
					</div>
							
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Portfolio -->

	

		<!-- Start Clients -->
		<div id="mu-clients">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-clients-area">

							<!-- Start Clients brand logo -->
							<div class="mu-clients-slider">

								<div class="mu-clients-single">
									<img src="assets/images/client-brand-1.jpg" alt="Brand Logo">
								</div>

								<div class="mu-clients-single">
									<img src="assets/images/client-brand-2.jpg" alt="Brand Logo">
								</div>

								<div class="mu-clients-single">
									<img src="assets/images/client-brand-3.jpg" alt="Brand Logo">
								</div>

								<div class="mu-clients-single">
									<img src="assets/images/client-brand-4.jpg" alt="Brand Logo">
								</div>

								<div class="mu-clients-single">
									<img src="assets/images/client-brand-5.jpg" alt="Brand Logo">
								</div>

								<div class="mu-clients-single">
									<img src="assets/images/client-brand-6.jpg" alt="Brand Logo">
								</div>
							</div>
							<!-- End Clients brand logo -->

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Clients -->

	</main>
	
	<!-- End main content -->	
