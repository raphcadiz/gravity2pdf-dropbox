<div class="wrap" id="gravity-merge-wrap">
    <h1>Gravity 2 PDF - Dropbox</h1>
    <br />
    <?php settings_errors() ?>
    <div class="content-wrap">
        <?php
            $gmergedropbox_settings_options = get_option('gmergedropbox_settings_options');
            $dropbox_app_key                = isset($gmergedropbox_settings_options['dropbox_app_key']) ? $gmergedropbox_settings_options['dropbox_app_key'] : '';
        ?>
        <br />
        <form method="post" action="options.php">
            <?php settings_fields( 'gmergedropbox_settings_options' ); ?>
            <?php do_settings_sections( 'gmergedropbox_settings_options' ); ?> 
            <table class="form-table">
                <tbody>
                    <tr class="form-field form-required term-name-wrap">
                        <th scope="row">
                            <label>Dropbox App Key</label>
                        </th>
                        <td>
                            <input type="text" name="gmergedropbox_settings_options[dropbox_app_key]" size="40" width="40" value="<?= $dropbox_app_key ?>">
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                <input type="submit" name="save_settings" class="button button-primary" value="Save">
            </p>
        </form>
    </div>
</div>