<?php
(!defined('THEMEPATH')) ? exit : '';

class grid_system
{
    public static function create_grid($data)
    {


?>
        <style>
            .italic {
                font-style: italic;
            }
        </style>

        <div class="row">
            <?php foreach ($data as $value) { ?>
                <div class="col-md-<?= $value['col'] ?>">
                    <?php foreach ($value['data'] as $val) { ?>
                        <?php if (is_array($val['content'])) { ?>
                            <?= sasi_template::components($val['content']) ?>
                        <?php } else {
                            echo $val['content'];
                        } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
<?php
    }
}
