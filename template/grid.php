<?php
(!defined('THEMEPATH')) ? exit : '';

class sobad_grid
{

    public static function create_grid($data)
    {
?>
        <div class="row">
            <?php foreach ($data as $value) {
                if ($value['func'] == 'grid') {
                    self::grid(array($value));
                }
            } ?>
        </div>
    <?php
    }

    public static function grid($data)
    {
    ?>
        <?php foreach ($data as $value) { ?>
            <div class="col-md-<?= $value['col'] ?>">
                <div class="row">
                    <?php foreach ($value['data'] as $val) { ?>
                        <?php if ($val['func'] == 'grid') { ?>
                            <?= self::grid(array($val)); ?>
                        <?php } elseif ($val['func'] == '') {
                            echo $val['data'];
                        ?>
                        <?php } else {
                            sasi_template::{$val['func']}($val['data']);
                        } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
<?php
    }
}
