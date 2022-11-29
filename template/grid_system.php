<?php
(!defined('THEMEPATH')) ? exit : '';

class grid_system
{
    public static function create_grid($data)
    {


?>
        <div class="row">
            <?php foreach ($data as $value) { ?>
                <div class="col-md-<?= $value['col'] ?>">
                    <div class="row">
                        <?php foreach ($value['data'] as $val) { ?>
                            <div class="col-md-<?= $val['col'] ?>">
                                <div class="m-md">
                                    <?php if (is_array($val['content'])) { ?>
                                        <?= sasi_template::{$val['func']}($val['content']) ?>
                                    <?php } else {
                                        echo $val['content'];
                                    } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
<?php
    }
}
