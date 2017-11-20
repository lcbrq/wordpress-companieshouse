<?php
$query = (isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '');
$show_results = true;
?>
<?php get_header(); ?>

<div class="container">

    <?php echo do_shortcode('[companieshouse]'); ?>

    <div class="row">
        <div class="col-md-12">

            <?php
            if ($query):
                $api = new Companieshouse;
                $companies = $api->get_companies($query);
                $name_exists = isset($companies[$query]);
                $similar_exists = array_filter($companies, function ($company) use ($query) {
                    if (stripos($company->title, $query) !== false) {
                        return true;
                    }
                });
                ?>

                <?php if ($name_exists): ?>
                    <div class="alert">
                        <?php echo _('There is already a company with same name'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($similar_exists): ?>
                    <div class="alert">
                        <?php echo _('There is already a company with similar name'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($companies && $show_results):
                    ?>
                    <table>
                        <?php
                        foreach ($companies as $company):
                            $address = $company->address;
                            ?>
                            <tr>
                                <td><?php echo $company->title; ?></td>
                                <td><?php echo $address->address_line_1; ?></td>
                                <td><?php echo $address->postal_code; ?></td>
                                <td><?php echo $address->locality; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php
                endif;
            endif;
            ?>

        </div>
    </div>

</div>

<?php get_footer(); ?>