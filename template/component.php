<?php
!defined('THEMEPATH') ? exit() : '';

class create_component
{
    public static function component($data = [])
    {
        if ($data['func'] !== '') {
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
        } else {
            echo $data['data'];
        }
    }

    private static function image($data = [])
    {
        $alt = isset($data['alt']) ? $data['alt'] : '';
        $id =  isset($data['id']) ? $data['id'] : '';
        ?>
        <img id="<?= $id ?>" src="<?= $data['url'] ?>" alt="<?= $alt ?>" width="<?= $data['width'] ?>" height="<?= $data['height'] ?>" style="border-radius: 20px !important;">
    <?php
    }

    public static function image_carousel($data)
    {
        $class = isset($data['class']) ? $data['class'] : '';
        $count_data = count($data['data']);
        $show = isset($data['show']) ? $data['show'] : '6';
        switch ($show) {
            case '1':
                $width = '100%';
                break;
            case '2':
                $width = '50%';
                break;
            case '3':
                $width = '33.3%';
                break;
            case '4':
                $width = '25%';
                break;
            case '5':
                $width = '20%';
                break;
            case '6':
                $width = '16.6%';
                break;
            case '7':
                $width = '14.2%';
                break;
            case '8':
                $width = '12.5%';
                break;
            case '9':
                $width = '11.1%';
                break;
            case '10':
                $width = '10%';
                break;

            default:
                $width = '100%';
        }

    ?>
        <style>
            #slide_carousel {
                position: relative;
            }

            #slide_carousel .MS-content {
                overflow: hidden;
                white-space: nowrap;
            }

            #slide_carousel .MS-content .item {
                display: inline-block;
                height: 100%;
                overflow: hidden;
                position: relative;
                vertical-align: top;
                padding: 0 10px;
                width: <?= $width ?>;
            }

            #slide_carousel .MS-controls button {
                position: absolute;
                border: none;
                background: transparent;
                font-size: 30px;
                outline: 0;
                top: 0;
                bottom: 0;
            }

            #slide_carousel .MS-controls .MS-right {
                right: -10px;
            }

            #slide_carousel .MS-controls .MS-left {
                left: -10px;
            }
        </style>
        <?php if (isset($data['data'][0])) { ?>
            <div id="slide_carousel">
                <div class="MS-content">
                    <?php foreach ($data['data'] as $value) { ?>
                        <div class="item <?= $class ?>">
                            <img style="width:100%" height="auto" src="<?= $value ?>">
                        </div>
                    <?php } ?>
                </div>
                <div class="MS-controls">
                    <button type='button' class="MS-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                    <button type='button' class="MS-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                </div>
            </div>
        <?php } else { ?>
            <h2>Data Image Kosong !</h2>
        <?php } ?>

        <script>
            var show = <?= $show; ?>;
            var data = <?= $count_data; ?>;

            $('#slide_carousel').multislider({
                continuous: false,
                slideAll: false,
                interval: 2000,
                duration: 500,
                hoverPause: true,
                pauseAbove: null,
                pauseBelow: null
            })

            if (show > data) {
                $('#slide_carousel').multislider('pause')
            } else {
                $('#slide_carousel').multislider('unPause')
            }
        </script>
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

    // ---------------------------------------------
    // Create Tabs ---------------------------------
    // ---------------------------------------------
    public static function list_tab($args = array())
    {
        $check = array_filter($args);
        if (empty($check)) {
            return '';
        }

        if (isset($args['object'])) {
            if (class_exists($args['object'])) {
                $object = $args['object'];
            } else {
                return '';
            }
        } else {
            $object = 'sasi_template';
        }

    ?>
        <div class="col-md-12">
            <div id="sobad_tabs" class="tabbable">
                <ul class="nav nav-tabs nav-tabs-lg">
                    <?php

                    $script = 'sobad_tabs(this)';
                    if (isset($args['script'])) {
                        $script = $args['script'];
                    }

                    $active = isset($args['active']) ? $args['active'] : '';

                    $li_cls = 'active';
                    foreach ($args['tab'] as $key => $val) {
                        $li_cls = empty($active) ? $li_cls : $active == $val['key'] ? 'active' : '';
                        $info = isset($val['info']) ? $val['info'] : "badge-success";
                        $load = isset($val['load']) ? $val['load'] : "tab_sasi";

                        echo '
                                <li class="' . $li_cls . '">
                                    <a id="' . $val['key'] . '" data-toggle="tab" href="#' . $load . $key . '" aria-expanded="true">
                                    ' . $val['label'] . ' 
                                    <span class="badge ' . $info . '">' . $val['qty'] . '</span>
                                    </a>
                                </li>
                            ';

                        $li_cls = '';
                    }
                    ?>
                </ul>
                <div class="tab-content">
                    <?php
                    $no_tab = 0;
                    foreach ($args['tab'] as $key => $val) {
                        $li_cls = empty($active) ? $li_cls : $active == $val['key'] ? 'active' : '';
                        $load = isset($val['load']) ? $val['load'] : "tab_sasi";

                    ?>
                        <div class="tab-pane <?= $li_cls; ?>" id="<?= $load . $key; ?>">
                            <div class="row">

                                <?php
                                $func = $val['func'];
                                if (method_exists($object, $func)) {
                                    $object::{$func}($val['data']);
                                }
                                ?>

                            </div>
                        </div>
                    <?php
                        $no_tab += 1;
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
