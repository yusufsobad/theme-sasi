<?php
(!defined('THEMEPATH')) ? exit : '';

class sobad_grid
{

    public static function create_grid($data)
    {
?>
        <div class="p-lg">
            <div class="row">
                <?php foreach ($data as $value) {
                    if ($value['func'] == 'grid') {
                        self::grid(array($value));
                    }
                } ?>
            </div>
        </div>
    <?php
    }

    public static function grid($data)
    {
    ?>
        <?php foreach ($data as $value) { ?>
            <div id="<?= isset($value['id']) ? $value['id'] : '' ?>" class="col-md-<?= $value['col'] ?>">
                <div class="row">
                    <?php foreach ($value['data'] as $val) { ?>
                        <?php if ($val['func'] == 'grid') { ?>
                            <?= self::grid(array($val)); ?>
                        <?php } elseif ($val['func'] == '') { ?>
                            <div class="p-md">
                                <?php echo $val['data']; ?>
                            </div>
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
