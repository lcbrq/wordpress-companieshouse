<?php $query = (isset($_GET['q']) ? sanitize_text_field($_GET['q']) : ''); ?>
<div class="row ">
    <div class="span12 ">
        <div class="search-company-container">
            <h1><?php _e('Is your company name available? Search now!'); ?></h1>
            <div class="search-company-action">
                <form class="form-search form-horizontal" action="<?php echo get_site_url(null, 'company/search'); ?>" method="GET">
                    <input class="input-medium search-query" type="text" name="q" value="<?php echo $query; ?>"> 
                    <button type="submit" class="btn btn-info">
                        <?php _e('Search'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>