<?php
(!defined('THEMEPATH')) ? exit : '';

class form_wizard
{
    public static function config_form_wizard($data)
    {
        $i = 0;
        $x = 0;
        $count = count($data);
?>
        <style>
            .stepwizard-step p {
                margin-top: 10px;
            }

            .stepwizard-row {
                display: table-row;
            }

            .stepwizard {
                display: table;
                width: 100%;
                position: relative;
            }

            .stepwizard-step button[disabled] {
                opacity: 1 !important;
                filter: alpha(opacity=100) !important;
            }

            .stepwizard-row:before {
                top: 14px;
                bottom: 0;
                position: absolute;
                content: " ";
                width: 100%;
                z-order: 0;

            }

            .stepwizard-step {
                display: table-cell;
                text-align: center;
                position: relative;
                padding: 2px;
            }

            .btn-circle {
                width: 30px;
                height: 30px;
                text-align: center;
                padding: 6px 0;
                font-size: 12px;
                line-height: 1.428571429;
                border-radius: 15px;
            }

            .radius-sm {
                border-radius: 7px;
            }
        </style>
        <div class="">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <?php foreach ($data as $val) {
                        $i = ++$i;
                    ?>
                        <div class="stepwizard-step">
                            <a href="#step-<?= $i ?>" type="button" class="btn btn-<?= $i == 1 ? 'primary' : 'default' ?> w-100 radius-sm" <?= $i == 1 ? '' : 'disabled="disabled"' ?>>
                                <span class="number"><?= $i ?></span>
                                <span class="desc">
                                    <i class="<?= isset($val['icon']) ? $val['icon'] : '' ?>"></i> <?= $val['title'] ?>
                                </span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <form role="form">
                <?php foreach ($data['data'] as $index => $value) {
                    $x = ++$x;
                    $idx = date('d-m-Y H:i:s');
                    $idx = strtotime($idx);
                    $idx = strval($idx);
                ?>
                    <div class="row setup-content" id="step-<?= $x ?>">
                        <div class="col-md-12">
                            <h2 class="bold mb-md pl-xs"> <?= $value['title'] ?></h2>
                            <?php foreach ($value['data'] as $val) {
                                if ($val['func'] !== '') {
                                    sasi_template::{$val['func']}($val['data']);
                                } else {
                                    echo $val['data'];
                                }
                            } ?>
                            <div class="col-md-12 mt-lg">
                                <?php if ($x !== $count) {  ?>
                                    <button class="btn btn-primary nextBtn radius-sm btn-lg pull-right mt-md" type="button">Next</button>
                                <?php } else { ?>
                                    <button type="button" id="btn_<?= $idx ?>" data-sobad="<?= $data['link'] ?>" data-load="<?= $data['load'] ?>" onclick="metronicSubmit_<?php print($idx); ?>()" data-index="#frm_<?php print($idx); ?>" onclick="metronicSubmit_<?php print($idx); ?>()" class="btn btn-primary nextBtn radius-sm btn-lg pull-right mt-md">Save</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </form>
        </div>

        <script type="text/javascript">
            function metronicSubmit_<?php print($idx); ?>() {
                $("form#frm_<?php print($idx); ?>").validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    highlight: function(element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group   
                    },
                    success: function(label, element) {
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set success class 
                    },
                    submitHandler: function(form) {
                        sobad_submitLoad('#btn_<?php print($idx); ?>');
                    }
                });

                $("form#frm_<?php print($idx); ?>>button.metronic-submit").trigger("click");

                setTimeout(function() {
                    $("form#frm_<?php print($idx); ?>>button.metronic-submit").removeAttr("type").attr("type", "submit");
                    $("form#frm_<?php print($idx); ?>>button.metronic-submit").trigger("click");
                }, 200);
            }
        </script>

        <script>
            $(document).ready(function() {

                var navListItems = $('div.setup-panel div a'),
                    allWells = $('.setup-content'),
                    allNextBtn = $('.nextBtn');

                allWells.hide();

                navListItems.click(function(e) {
                    e.preventDefault();
                    var $target = $($(this).attr('href')),
                        $item = $(this);

                    if (!$item.hasClass('disabled')) {
                        navListItems.removeClass('btn-primary').addClass('btn-default');
                        $item.addClass('btn-primary');
                        allWells.hide();
                        $target.show();
                        $target.find('input:eq(0)').focus();
                    }
                });

                allNextBtn.click(function() {
                    var curStep = $(this).closest(".setup-content"),
                        curStepBtn = curStep.attr("id"),
                        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                        curInputs = curStep.find("input[type='text'],input[type='url']"),
                        isValid = true;

                    $(".form-group").removeClass("has-error");
                    for (var i = 0; i < curInputs.length; i++) {
                        if (!curInputs[i].validity.valid) {
                            isValid = false;
                            $(curInputs[i]).closest(".form-group").addClass("has-error");
                        }
                    }

                    if (isValid)
                        nextStepWizard.removeAttr('disabled').trigger('click');
                });

                $('div.setup-panel div a.btn-primary').trigger('click');
            });
        </script>
<?php
    }
}
