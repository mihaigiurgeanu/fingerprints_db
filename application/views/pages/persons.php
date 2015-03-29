<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Persons <small>Manage Persons Database</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-edit"></i> Persons list person
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->

<div class=".row">
    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Descrption</th>
                    <th class="check">Photo</th>
                    <th class="check">Fingerprints</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <a href="/persons/add" class="btn btn-primary"><i class="fa fa-plus"></i> Add person</a>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach($persons as $person) {?>
                <tr>
                    <td><?php echo $person['first_name']; ?></td>
                    <td><?php echo $person['last_name']; ?></td>
                    <td><?php echo $person['description'] ?></td>
                    <td class="check"><?php if($person['photo_id']) {?><i class="fa fa-check fa-lg"></i><?php } ?></td>
                    <td class="check"><?php if($person['fingerprints_id']) {?><i class="fa fa-check fa-lg"></i><?php } ?></td>
                    <td>
                        <a href="#" class="verify-person" title="Verify" data-person-id="<?php echo $person['id'] ?>"><i class="fa fa-cog"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>