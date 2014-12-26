
</div>
<?php if(!empty($this->session->userdata('logged_in'))){
if(@$print == null){
?>
<div class="copyright">
&copy;Copyright <?php echo date("Y"); ?>. All Rights Reserved. Zetu Mobile Solutions Ltd.
</div>
<?php 
}
}
?>
</div>
</body>
</html>
