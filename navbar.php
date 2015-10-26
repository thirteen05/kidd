<nav class="navbar navbar-inverse navbar-fixed-top hidden-xs" id="thirteen05-navbar" role="navigation">
  <div class="container relative">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>">
                <img height="100" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-header.svg"/>
            </a>
    </div>

    <?php
            wp_nav_menu( array(
                'menu'              => 'primary',
                'theme_location'    => 'primary',
                'depth'             => 2,
                'container'         => '',
                'container_class'   => 'collapse navbar-collapse',
        'container_id'      => 'bs-example-navbar-collapse-1',
                'menu_class'        => 'nav navbar-nav',
                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                'walker'            => new wp_bootstrap_navwalker())
            );
        ?>

      <ul class="nav navbar-nav navbar-right">
          <li>
            <button class="btn navbar-btn font-gothic shadow thirteen05-btn hidden-xs" data-toggle="modal" data-target="#contactModal" id="desktop-cta-btn">Free Evaluation</button>
          </li>
      </ul>
  </div>
</nav>