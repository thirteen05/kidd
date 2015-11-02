  <?php get_template_part('modal'); ?>
                </div>      
                <div id="FooterWrapper">
                    <div id="Footer">
                        <div class="row">
                            <div class="col-md-6 col-md-push-6">
                                <div id="Socials">
                            <a href="https://www.facebook.com/captainkiddfishing" target="_blank">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-facebook.png" width="62" height="60">
                            </a>
                            <a href="https://plus.google.com/103919644994830458597/posts" target="_blank">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-googleplus.png" width="62" height="60">
                            </a><a href="https://twitter.com/captainkiddfish" target="_blank">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-twitter.png" width="62" height="60">
                            </a>
                            <a href="http://www.linkedin.com/pub/capt-robert-kidd/68/442/168" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-linkedin.png" width="62" height="60">
                            </a>
                        </div>
                            </div>
                            <div class="col-md-6 col-md-pull-6">
                                <div class="left-side">
                                    <a href="index.html">Home</a>
                                    | 
                                    <a href="charter-boat-trips.html">Trips</a>
                                    | 
                                    <a href="fishing-boat.html">Boat Specs</a>
                                    | 
                                    <a href="testimonials.html">Testimonials</a>
                                    | 
                                    <a href="contact.html">Contact</a>
                                    <br>
                                    © 2015 Captain Kidd Fishing Services.
                                    <a href="http://www.fastf.com" target="_blank">Web Design by Fast F</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <script src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/jquery.stellar/jquery.stellar.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/plugins.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/main.js"></script>

    <script>
      var stage = new swiffy.Stage(document.getElementById('swiffycontainer'),
          swiffyobject, {});
      stage.setBackground(null);
      stage.start();
    </script>
<script>
    $('.mobile-trigger').click(function(){
        $(this).toggleClass('hook-out');
        $('header').toggleClass('opened');
    });
</script>

        <?php wp_footer(); ?>

    </body>
</html>