
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, So:KNO</div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>