<?php
(!defined('THEMEPATH')) ? exit : '';

class sobad_grid
{

    public static function create_grid($data=array())
    {
    ?>
        <div class="p-lg">
            <div class="row">
                <?php foreach ($data as $key => $val) {
                    self::grid($val);
                } ?>
            </div>
        </div>
    <?php
    }

   private static function grid($data=array())
    {
        $id = isset($data['id']) ? $data['id'] : '';
        $col = isset($data['col']) ? $data['col'] : 12;
        ?>
            <div id="<?= $id; ?>" class="col-md-<?= $col; ?>">
                <?php
                    $func = isset($val['func']) ? $val['func'] : '';
                    if (method_exists('sasi_template', $func)) {
                        sasi_template::{$func}($val['data']);
                    }else{
                        self::create_grid($val['data']);
                    }
                ?> 
            </div>
        <?php
    }
}
