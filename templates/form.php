<?php $query = (isset($_GET['q']) ? sanitize_text_field($_GET['q']) : ''); ?>
<div class="row ">
    <div class="span12 ">
        <div class="search-company-container">
            <h1><?php _e('Is your company name available? Search now!'); ?></h1>
            <div class="search-company-action">
                <form class="form-search form-horizontal" method="GET">
                    <input class="input-medium search-query" type="text" name="q" value="<?php echo $query; ?>"> 
                    <button type="submit" class="btn btn-info">
                        <?php _e('Search'); ?>
                    </button>
                </form>
            </div>

            <?php
            if ($query):
                $companieshouse = new Companieshouse;
                $result = $companieshouse->search($query);
                if (isset($result->items)):
                    ?>
                    <table>
                        <?php
                        foreach ($result->items as $item):
                            $address = $item->address;
                            ?>
                            <tr>
                                <td><?php echo $item->title; ?></td>
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