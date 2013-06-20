<article>
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo get_twitter_profile_img($blog_twitter) ?>" alt="profile" />
            </div>

            <ul>
                <li><?php echo $blog_title ?></li>
                <li><a href="mailto:<?php echo $blog_email ?>?subject=Hello"><?php echo $blog_email ?></a></li>
                <li><a href="/rss">RSS feed</a></li>
                <li></li>
            </ul>
        </div>

        <div class="three-quarters post">
            <h2><?php echo $intro_title ?></h2>

            <p><?php echo $intro_text ?></p>

            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</article>
