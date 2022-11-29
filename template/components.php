<?php
(!defined('THEMEPATH')) ? exit : '';

class create_components
{
    public static function components($data)
    {
?>
        <!-- Config Text -->
        <?php if (isset($data['components'])) { ?>

            <!-- TEXT -->
            <?php if ($data['components'] == 'text') { ?>
                <p class="<?= isset($data['class']) ? $data['class'] : '' ?>" style="font-size: <?= $data['size'] ?> !important;">
                    <?= $data['content'] ?>
                </p>
            <?php } ?>



            <!-- Carousel -->
            <?php if ($data['components'] == 'img-carousel') {  ?>
                <div id="carousel-<?= $data['id'] ?>" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <?php if ($data['dots'] == 'true') { ?>
                        <ol class="carousel-indicators">
                            <?php $i = -1 ?>
                            <?php foreach ($data['data'] as $val) {
                                $i++
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
            <?php } ?>

            <?php if ($data['components'] == 'progress_bar') { ?>
                <div class="progress" style="border-radius: 30px !important;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $data['value'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $data['value'] ?>%;">
                        <?= $data['value'] ?>%
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
<?php
    }
}
