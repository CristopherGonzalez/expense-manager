
<?php 
    $user_session=UserData::getById($_SESSION["user_id"]);
    $company = CompanyData::getById($user_session->empresa);
    if(isset($_SESSION["user_id"])): //si no hay session ?>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
              <b>Version</b> 1.1.1
            </div>
            <strong> <?php echo $company->name." - ".$company->licenciaMRC; ?> <a href="#"></a>.</strong>
        </footer>
    </div>
    <!-- ./wrapper -->
    <!-- jQuery 2.2.3 -->
    <script src="res/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="res/bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="res/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="res/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="res/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="res/dist/js/demo.js"></script>
    
    <!-- Select2 -->
    <script src="res/plugins/select2/select2.min.js" type="text/javascript"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
<?php endif; ?>

</body>
</html>
