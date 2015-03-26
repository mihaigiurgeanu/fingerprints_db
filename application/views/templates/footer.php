
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <!--
    <script src="/js/plugins/morris/raphael.min.js"></script>
    <script src="/js/plugins/morris/morris.min.js"></script>
    <script src="/js/plugins/morris/morris-data.js"></script>
    -->

    <script src="/js/bootstrap-notify.min.js"></script>
<?php foreach ($page_scripts as $script) { ?>
    <script src="/js/<?php echo $script ?>"></script>
<?php } ?>

</body>

</html>
