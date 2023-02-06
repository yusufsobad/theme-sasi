<?php
!defined('THEMEPATH') ? exit() : '';

class form_repeater
{
    public static function create_form_repeater($data = [])
    {
        $load_add = isset($data['load_add']) ? $data['load_add'] : 'repeat_add';
?>
        <style>
            .float-right {
                float: right;
            }

            .flex-center-bottom {
                display: flex;
                align-content: center;
                align-items: flex-end;
            }

            .radius-xs {
                border-radius: 7px !important;
            }

            .col-auto {
                flex: 0 0 auto;
                width: auto;
                max-width: none;
            }
        </style>

        <div class='repeater'>
            <!-- Make sure the repeater list value is different from the first repeater  -->
            <div data-repeater-list="<?= $data['id'] ?>">
                <?php foreach ($data['data'] as $key => $val) {
                    $load = $key == 0 ? $data['load'] : $data['load'] . $val['id'];
                ?>
                    <div data-repeater-item>
                        <div class="row flex-center-bottom mb-sm">
                            <div class="col-md-11">
                                <?php if ($val['func'] !== '') {
                                    sasi_template::{$val['func']}(
                                        $val['data']
                                    );
                                } else {
                                    echo $val['data'];
                                } ?>
                            </div>
                            <div class="col-md-1 p-0">
                                <a id='<?= $load ?>' href="javascript:" onclick="repeat_button_add(this,false)" class="btn btn-danger m-sm float-right m-0 radius-xs" data-load="" data-sobad="<?= $data['func_del'] ?>" data-type="<?= $data['type'] ?>" data-repeater-delete type="button">Delete</a>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            </div>
            <a data-load="<?= $data['load'] ?>" href="javascript:" onclick="repeat_button_add(this,false)" data-sobad="<?= $data['func_add'] ?>" data-type="<?= $data['type'] ?>" class="btn btn-primary m-sm radius-xs" data-repeater-create type="button">Add</a>
        </div>

        <script>
            $(document).ready(function() {

                $('.repeater').repeater({

                    // (Optional)
                    // start with an empty list of repeaters. Set your first (and only)
                    // "data-repeater-item" with style="display:none;" and pass the
                    // following configuration flag
                    initEmpty: false,
                    // (Optional)
                    // "defaultValues" sets the values of added items.  The keys of
                    // defaultValues refer to the value of the input's name attribute.
                    // If a default value is not specified for an input, then it will
                    // have its value cleared.
                    defaultValues: {
                        'text-input': ''
                    },
                    // (Optional)
                    // "show" is called just after an item is added.  The item is hidden
                    // at this point.  If a show callback is not given the item will
                    // have $(this).show() called on it.
                    show: function() {


                        $(this).slideDown();
                        $('.bs-select').selectpicker('refresh');
                        $(".btn-group.bootstrap-select.form-control.bs-select:nth-child(3)").remove();

                        // This Solution Fix Crash Selectpicker with Form Repeater
                        // $('select').selectpicker('refresh');


                    },
                    // (Optional)
                    // "hide" is called when a user clicks on a data-repeater-delete
                    // element.  The item is still visible.  "hide" is passed a function
                    // as its first argument which will properly remove the item.
                    // "hide" allows for a confirmation step, to send a delete request
                    // to the server, etc.  If a hide callback is not given the item
                    // will be deleted.
                    hide: function(deleteElement) {
                        // Script Untuk Menampilkan allert Delete Element
                        // if (confirm('Are you sure you want to delete this element?')) {
                        //     $(this).slideUp(deleteElement);
                        // }

                        // Tanpa Allert
                        $(this).slideUp(deleteElement);
                    },
                    // (Optional)
                    // You can use this if you need to manually re-index the list
                    // for example if you are using a drag and drop library to reorder
                    // list items.
                    ready: function(setIndexes) {},
                    // (Optional)
                    // Removes the delete button from the first list item,
                    // defaults to false.
                    isFirstItemUndeletable: false
                })

            });

            // function button
            function repeat_button_add(val, spin) {
                var id = $(val).attr('data-load');

                var ajx = $(val).attr("data-sobad");
                var lbl = $(val).attr('id');
                var msg = $(val).attr('data-alert');
                var tp = $(val).attr('data-type');

                var pg = $('#dash_pagination li.disabled a').attr('data-qty');
                var data = $("form").serializeArray();
                data = conv_array_submit(data);

                sobad_load_togle($(val).attr('href'));

                // loading	
                var html = $(val).html();
                if (spin) {
                    $(val).html('<i class="fa fa-spinner fa-spin"></i>');
                    $(val).attr('disabled', '');
                }

                data = "ajax=" + ajx + "&object=" + object + "&data=" + lbl + "&args=" + data + "&type=" + tp + "&page=" + pg + "&filter=" + filter;
                sobad_ajax('#' + id, data, call_repeat, msg, val, html);
            }

            function call_repeat(data, id) {

                hasTag = id.replace('#', '');
                $(id).attr('id', hasTag + data);
                $('#<?= $load_add ?>').val(data);
                $('#<?= $load_add ?>').attr('id','<?= $load_add ?>' + data);
            }

            function repeater_submit(val) {
                repeater = JSON.stringify($('.repeater').repeaterVal());
                sobad_submitLoad(val);
            }
        </script>
<?php
    }
}
