
<?php /*
<<?php echo $tag.$id.$class.$style?>>
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<ul class="bxslider 1image-slider-webpart">
					<?php while (have_rows('slides')) : the_row(); ?>
						<?php $img = get_sub_field('image') ?>
						<li class="1image-slide text-center"<?php //echo (@$ii++>0) ? ' style="display:none1"' : ''?>>
							<img id="" src="<?php echo $img['url'] ?>" alt="<?php echo $img['alt'] ?>" />
						</li>
					<?php endwhile; ?>
				</ul>
				<!--
					<span class="prev-slide"></span>
					<span class="next-slide"></span>-->
			</div>
		</div>
	</div>
</<?php echo $tag ?>>
*/ ?>


<amp-carousel class="hero-carousel" width="800" height="300" layout="responsive" type="slides" autoplay delay="2000">
<?php
    while (have_rows('slides')) :
        the_row(); 
        $slide_img = get_sub_field('image');
?>
    <div class="slide">
        <amp-img src="<?php echo $slide_img['url'] ?>" layout="fill" alt="<?php echo $slide_img['alt'] ?>"></amp-img>
        <amp-fit-text layout="responsive" width="500" height="110">
        </amp-fit-text>
    </div>
<?php
    endwhile;
?>
</amp-carousel>