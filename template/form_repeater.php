<?php
(!defined('THEMEPATH')) ? exit : '';

class form_repeater
{
    public static function create_form_repeater($data)
    {
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
        </style>

        <?php foreach ($data as $value) { ?>
            <div class="col-md-12 pl-0 pb-md pt-0">
                <h3><?= isset($value['title']) ? $value['title'] : '' ?></h3>
            </div>
            <div class='repeater'>
                <!-- Make sure the repeater list value is different from the first repeater  -->
                <div data-repeater-list="<?= $value['id'] ?>">
                    <div data-repeater-item>
                        <div class="row flex-center-bottom mb-sm">
                            <div class="col-md-11">
                                <?php foreach ($value['data'] as $val) { ?>
                                    <?php if ($val['func'] !== '') {
                                        sasi_template::{$val['func']}($val['data']);
                                    } else {
                                        echo $val['data'];
                                    } ?>
                                <?php } ?>
                            </div>
                            <div class="col-md-1 p-0">
                                <input class="btn btn-danger m-sm float-right m-0 radius-xs" data-repeater-delete type="button" value="Delete" />
                            </div>

                        </div>
                    </div>
                </div>
                <input class="btn btn-primary m-sm radius-xs" data-repeater-create type="button" value="Add" />
            </div>

        <?php } ?>


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
                    isFirstItemUndeletable: true
                })
            });
        </script>
<?php
    }
}
