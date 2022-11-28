<?php
(!defined('THEMEPATH')) ? exit : '';

class custom_form
{
    public static function config_form($data)
    {
?>

        <div class="row">
            <?php foreach ($data as $val) { ?>
                <div class="<?= isset($val['grid']) ?  $val['grid'] : '' ?>">

                    <div class="form-body">
                        <?php foreach ($val['form'] as $key) { ?>

                            <!-- Form Input Text -->
                            <?php if ($key['input-type'] == 'text') { ?>
                                <div class="form-group">
                                    <label><?= $key['title'] ?></label>
                                    <input type="text" class="form-control" required="<?= $key['required'] == 'true' ? 'required' : 'false' ?>" placeholder="<?= $key['placeholder'] ?>" <?= $key['readonly'] == 'true' ? 'readonly' : '' ?> id="<?= $key['id'] ?>" name="<?= $key['name'] ?>" value="<?= $key['value'] ?>">
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
                        <?php } ?>
                    </div>


                </div>
            <?php } ?>
        </div>

<?php
    }
}
