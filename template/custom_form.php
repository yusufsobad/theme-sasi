<?php
(!defined('THEMEPATH')) ? exit : '';

class custom_form
{
    public static function config_form($data)
    {
?>


        <div class="form-body">
            <?php foreach ($data as $key) { ?>

                <!-- Form Input Text -->
                <?php if ($key['input-type'] == 'text') { ?>
                    <div class="form-group">
                        <label><?= $key['title'] ?></label>
                        <input type="<?= $key['input-type'] ?>" class="form-control" required="<?= $key['required'] == 'true' ? 'required' : 'false' ?>" placeholder="<?= $key['placeholder'] ?>" <?= $key['readonly'] == 'true' ? 'readonly' : '' ?> id="<?= $key['id'] ?>" name="<?= $key['name'] ?>" value="<?= $key['value'] ?>">
                        <small class="form-text text-muted"><?= isset($key['note']) ? $key['note'] : '' ?></small>
                    </div>
                <?php } ?>
                <!-- End Form Input Text -->


                <!-- Form Input Text-Area -->
                <?php if ($key['input-type'] == 'text-area') { ?>
                    <div class="form-group">
                        <label><?= $key['title'] ?></label>
                        <textarea class="form-control" required="<?= $key['required'] == 'true' ? 'required' : 'false' ?>" id="<?= $key['id'] ?>" name="<?= $key['name'] ?>" value="<?= $key['value'] ?>" placeholder="<?= $key['placeholder'] ?>" rows="3"></textarea>
                        <small class="form-text text-muted"><?= $key['note'] ?></small>
                    </div>
                <?php } ?>
                <!-- End Form Input Text-Area -->


                <!-- Config Select Default -->
                <?php if ($key['input-type'] == 'select') { ?>
                    <div class="form-group">
                        <label><?= $key['title'] ?></label>
                        <select class="select2-container form-control select2me" required="<?= $key['required'] == 'true' ? 'required' : 'false' ?>" data-live-search="true" id="<?= $key['id'] ?>" name="<?= $key['name'] ?>">
                            <?php foreach ($key['data'] as $value) { ?>
                                <?php if (@$value[$key['content_id']] == @$key['value']) { ?>
                                    <option value="<?= @$value[$key['content_id']] ?>" selected><?= @$value[$key['content']]  ?></option>
                                <?php }  ?>
                                <option value="<?= @$value[$key['content_id']] ?>"><?= @$value[$key['content']] ?></option>
                            <?php } ?>
                        </select>
                        <small class="form-text text-muted"><?= $key['note'] ?></small>
                    </div>
                <?php } ?>
                <!-- End Config Select Default -->

                <!-- Config Preview Image -->
                <?php if ($key['input-type'] == 'preview-image') { ?>
                    <img src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg" alt="" style="width: 100%;height:100px">
                <?php } ?>
                <!-- End Config Preview Image -->


                <?php if ($key['input-type'] == 'preview-text') { ?>
                    <div class="" style="vertical-align: bottom;">
                        <h2>Rp. 150.000</h2>
                    </div>
                <?php } ?>

                <!-- RANGE SLIDER -->
                <?php if ($key['input-type'] == 'range-slider') { ?>
                    <div class="form-group">
                        <label><?= $key['title'] ?></label>
                        <div class="range-slider">
                            <div class="row">
                                <div class="col-md-11 p-0"><input class="range-slider__range" type="range" value="<?= $key['value'] ?>" min="0" max="100" step="<?= isset($key['range']) ? $key['range'] : '1' ?>" id="<?= $key['id'] ?>" name="<?= $key['name'] ?>" style="height: 20px;"></div>
                                <div class="col-md-1 "><span class="range-slider__value"><?= $key['value'] ?></span>%</div>
                            </div>

                        </div>
                        <small class="form-text text-muted"><?= $key['note'] ?></small>
                    </div>
                <?php } ?>

                <script>
                    const settings = {
                        fill: '#1abc9c',
                        background: '#d7dcdf'
                    }
                    const sliders = document.querySelectorAll('.range-slider');
                    Array.prototype.forEach.call(sliders, (slider) => {
                        slider.querySelector('input').addEventListener('input', (event) => {
                            // 1. apply our value to the span
                            slider.querySelector('span').innerHTML = event.target.value;
                            // 2. apply our fill to the input
                            applyFill(event.target);
                        });
                        // Don't wait for the listener, apply it now!
                        applyFill(slider.querySelector('input'));
                    });
                    // This function applies the fill to our sliders by using a linear gradient background
                    function applyFill(slider) {
                        // Let's turn our value into a percentage to figure out how far it is in between the min and max of our input
                        const percentage = 100 * (slider.value - slider.min) / (slider.max - slider.min);
                        // now we'll create a linear gradient that separates at the above point
                        // Our background color will change here
                        const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage+0.1}%)`;
                        slider.style.background = bg;
                    }
                </script>
                <!-- END RANGE SLIDER -->


            <?php } ?>
        </div>
<?php
    }
}
