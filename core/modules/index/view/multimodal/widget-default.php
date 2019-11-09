<?php 
if(isset($_SESSION["user_id"]) && $_SESSION['user_id']!= "1"):
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
        <h2>Stacked Bootstrap Modal Example.</h2>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<p> lorem ipsum </p>
<a data-toggle="modal" href="#myModal" class="btn btn-primary">Launch modal</a>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Modal 1</h4>

      </div>
      <div class="container"></div>
      <div class="modal-body">Content for the dialog / modal goes here.
        <br>
        <br>
        <br>
        <p>more content</p>
        <br>
        <br>
        <br> <a data-toggle="modal" href="#myModal2" class="btn btn-primary">Launch modal</a>

      </div>
      <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
        <a href="#" class="btn btn-primary">Save changes</a>

      </div>
    </div>
  </div>
</div>






<div class="modal fade" id="myModal2">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Modal 2</h4>

      </div>
      <div class="container"></div>
      <div class="modal-body">Content for the dialog / modal goes here.
        <br>
        <br>
        <p>come content</p>
        <br>
        <br>
        <br> <a data-toggle="modal" href="#myModal3" class="btn btn-primary">Launch modal</a>

      </div>
      <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
        <a href="#" class="btn btn-primary">Save changes</a>

      </div>
    </div>
  </div>
</div>






<div class="modal fade" id="myModal3">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Modal 3</h4>

      </div>
      <div class="container"></div>
      <div class="modal-body">Content for the dialog / modal goes here.
        <br>
        <br>
        <br>
        <br>
        <br> <a data-toggle="modal" href="#myModal4" class="btn btn-primary">Launch modal</a>

      </div>
      <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
        <a href="#" class="btn btn-primary">Save changes</a>

      </div>
    </div>
  </div>
</div>






<div class="modal fade" id="myModal4">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Modal 4</h4>

      </div>
      <div class="container"></div>
      <div class="modal-body">Content for the dialog / modal goes here.</div>
      <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
        <a href="#" class="btn btn-primary">Save changes</a>

      </div>
    </div>
  </div>
</div>

        </div>
        
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php   include "res/resources/js.php"; ?>
<script type="text/javascript" src="res/plugins/multimodal/multimodal.js"></script>
<?php else: Core::redir("./"); endif;?> 