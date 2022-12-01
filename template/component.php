<?php
(!defined('THEMEPATH')) ? exit : '';

class create_component
{
    public static function component($data)
    {
        $data = array($data);
        foreach ($data as $val) {
            if (is_callable(array(new self(), $val['func']))) {
                self::begin_component($val);
            } else {
                echo  '<h3 style="color:red">Function Tidak Ada!</h3>';
            }
        }
    }

    private static function begin_component($data)
    {
?>
        <div class="p-md">
            <?php self::{$data['func']}($data); ?>
        </div>
    <?php
    }

    private static function image_carousel($data)
    {
    ?>
        <div id="carousel-<?= $data['id'] ?>" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <?php if ($data['dots'] == 'true') { ?>
                <ol class="carousel-indicators">
                    <?php $i = -1 ?>
                    <?php foreach ($data['data'] as $val) {
                        $i++;
                    ?>
                        <li data-target="#carousel-<?= $data['id'] ?>" data-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>"></li>
                    <?php } ?>
                </ol>
            <?php } ?>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <?php $i = -1 ?>
                <?php foreach ($data['data'] as $value) {
                    $i++;
                ?>
                    <div class="item <?= $i == 0 ? 'active' : '' ?>">
                        <img src="<?= $value['img_url'] ?>">
                    </div>
                <?php } ?>
            </div>
            <!-- Left and right controls -->
            <a class="left carousel-control" href="#carousel-<?= $data['id'] ?>" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-<?= $data['id'] ?>" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    <?php
    }

    private static function title_label($data)
    {
    ?>
        <h3 class="bold mb-xs mt-xs"><?= $data['title'] ?></h3>
        <h4><?= $data['value'] ?></h4>
    <?php
    }

    private static function progress_bar($data)
    {
    ?>
        <div class="progress" style="border-radius: 30px !important;">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $data['value'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $data['value'] ?>%;">
                <?= $data['value'] ?>%
            </div>
        </div>
<?php
    }
}
