                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Persons <small>Manage Persons Database</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-edit"></i> Add person
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo validation_errors(); ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-6">

                        <?php echo form_open('persons/add', array('id' => 'person-form'))?>

                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>"></input>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input name="last_name" class="form-control" value="<?php echo set_value('last_name'); ?>"></input>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description" class="form-control" rows="3"><?php echo set_value('description'); ?></textarea>
                            </div>

                            <input type="hidden" name="scan_tokenid" value="<?php echo set_value('scan_tokenid'); ?>"></input>

                            <button type="submit" class="btn btn-primary" id="save-button">Save</button>
                            <button type="button" class="btn btn-default" id="scan-button">Scan fingerprints</button>
                            <button type="button" class="btn btn-default" id="photo-button">Take picture</button>
                            <button type="reset" class="btn btn-danger" id="reset-button">Reset</button>

                        <?php form_close() ?>

                    </div>
                    <div class="col-lg-6">
                        <image id="fingerprint-scan" src="/api/fingerprintsscans/5339c7bc-d2ec-11e4-8dbb-cae8083ffdec"></image>
                    </div>
                    
                </div>
                <!-- /.row -->
