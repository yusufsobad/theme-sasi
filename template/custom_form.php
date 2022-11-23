<?php
(!defined('THEMEPATH')) ? exit : '';

class custom_form
{
    public static function config_form()
    {
?>
        <form role="form">
            <div class="form-body">
                <div class="form-group">
                    <label>Large Input</label>
                    <input type="text" class="form-control input-lg" placeholder="input-lg">
                </div>
                <div class="form-group">
                    <label>Default Input</label>
                    <input type="text" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label>Small Input</label>
                    <input type="text" class="form-control input-sm" placeholder="input-sm">
                </div>
                <div class="form-group">
                    <label>Large Select</label>
                    <select class="form-control input-lg">
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                        <option>Option 4</option>
                        <option>Option 5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Default Select</label>
                    <select class="form-control">
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                        <option>Option 4</option>
                        <option>Option 5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Small Select</label>
                    <select class="form-control input-sm">
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                        <option>Option 4</option>
                        <option>Option 5</option>
                    </select>
                </div>
            </div>
            <div class="form-actions right">
                <button type="button" class="btn default">Cancel</button>
                <button type="submit" class="btn green">Submit</button>
            </div>
        </form>
<?php
    }
}
