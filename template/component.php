<?php
!defined('THEMEPATH') ? exit() : '';

class create_component
{
    public static function component($data = [])
    {
        if (is_callable([new self(), $data['func']])) { ?>
            <div class="p-md">
                <?php self::{$data['func']}($data['data']); ?>
            </div>
        <?php
        } else {
            echo '<h3 style="color:red">Function <span style="font-style:italic;text-decoration:underline">' .
                $data['func'] .
                '</span> Tidak Ada!</h3>';
        }
    }

    private static function image($data = [])
    {
        $alt = isset($data['alt']) ? $data['alt'] : '';
        $class =  isset($data['class']) ? $data['class'] : '';
        ?>
        <img class="<?= $class ?>" src="<?= $data['url'] ?>" alt="<?= $alt ?>" width="<?= $data['width'] ?>" height="<?= $data['height'] ?>">
    <?php
    }

    private static function image_carousel($data = [])
    {
    ?>
        <div id="carousel-<?= $data['id'] ?>" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <?php if ($data['dots'] == 'true') { ?>
                <ol class="carousel-indicators">
                    <?php $i = -1; ?>
                    <?php foreach ($data['data'] as $val) {
                        $i++; ?>
                        <li data-target="#carousel-<?= $data['id'] ?>" data-slide-to="<?= $i ?>" class="<?= $i == 0
                                                                    ? 'active'
                                                                    : '' ?>"></li>
                    <?php
                    } ?>
                </ol>
            <?php } ?>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <?php $i = -1; ?>
                <?php foreach ($data['data'] as $value) {
                    $i++; ?>
                    <div class="item <?= $i == 0 ? 'active' : '' ?>">
                        <img src="<?= $value ?>">
                    </div>
                <?php
                } ?>
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

    private static function box_card($data = [])
    {
        $id = isset($data['id']) ? $data['id'] : ''; ?>
        <div id='<?= $id ?>'>
            <?php
            if (isset($data['title'])) {
                self::title_label($data);
            }

            $func = $data['func'];
            if (method_exists('sasi_template', $func)) {
                sasi_template::{$func}($data['data']);
            } ?>
        </div>
    <?php
    }

    private static function title_label($data = [])
    {
    ?>
        <h3 class="bold mb-xs mt-xs"><?= $data['title'] ?></h3>
        <h4><?= $data['label'] ?></h4>
    <?php
    }

    private static function progress_bar($data = [])
    {
        $value = isset($data['value']) ? $data['value'] : 0; ?>
        <div class="progress" style="border-radius: 30px !important;">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $value ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value ?>%;">
                <?= $value ?>%
            </div>
        </div>
<?php
    }
}
