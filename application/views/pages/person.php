        <div id="page-wrapper">

            <div class="container-fluid">

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
                    <div class="col-lg-6">

                        <form role="form">

                            <div class="form-group">
                                <label for="first-name">First Name:</label>
                                <input id="first-name" class="form-control"></input>
                            </div>

                            <div class="form-group">
                                <label for="last-name">Last Name:</label>
                                <input id="last-name" class="form-control"></input>
                            </div>

                            <div class="form-group">
                                <label>Description:</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>


                            <button type="submit" class="btn btn-primary" onclick="return false;">Submit Button</button>
                            <button type="button" class="btn btn-default" onclick="window.open('http://localhost:3000');return false;">Scan fingerprints</button>
                            <button type="button" class="btn btn-default" onclick="window.open('http://localhost:3000');return false;">Take picture</button>
                            <button type="reset" class="btn btn-danger" onclick="return false;">Reset Button</button>

                        </form>

                    </div>
                    
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
