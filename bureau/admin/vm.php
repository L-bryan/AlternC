<?php

require_once("../class/config.php");

$fields = array (
	"action" => array ("get", "string", ''),
	"script" => array ("get", "boolean", 0),
);
getFields($fields);

if (in_array($action, array('start', 'stop', 'monit'))) {
        $res = $hooks->invoke($action, array(), 'lxc');
}

$infos = $lxc->getvm();

if ($script) {
  if (isset($res)) {
  echo "ACTION:".$action."\n";
  echo "RETURN:".intval($res['lxc'])."\n";
  }
  if ($infos) {
    echo "VM_STATUS:OK\n";
    echo "VM_START:".$infos['date_start']."\n";
    echo "VM_RETURN_CODE:".intval($infos['serialized_object']['error'])."\n";
    echo "VM_ID:".$infos['serialized_object']['vm']."\n";
    echo "VM_HOSTNAME:".$infos['serialized_object']['hostname']."\n";
    echo "VM_MSG:".$infos['serialized_object']['msg']."\n";
  } else {
    echo "VM_STATUS:NONE\n";
  }
  die();
}

# Show the header after the "if script" ;)
include_once("head.php");

?>

<h3><?php __('Console access'); ?></h3>
<hr/>
<br/>

<?php if (isset($res) && ! $res['lxc']) { ?>
<div>
<span class="error">
  <?php echo $err->errstr(); ?>
</span>
</div>
<br/>
<br/>
<?php } //isset $res ?>

<div>
<?php if (empty($infos)) { 
  echo '<span class="error">';
  __("You can start a virtual machine.");
  echo "<a href='vm.php?action=start'>"._("Click here to do so.")."</a>";
  echo '</span>';
} else {
 echo "<table class='tedit'>";
 echo "<tr><th>"._("Hostname")."</th><td>".$infos['serialized_object']['hostname']."</td></tr>";
 echo "<tr><th>"._("Start time")."</th><td>".$infos['date_start']."</td></tr>";
 echo "<tr><th>"._("Usefull command")."</th><td><pre>";
   echo "ssh ".$mem->user['login']."@".$infos['serialized_object']['hostname']."\n";
   echo "rsync ".$mem->user['login']."@".$infos['serialized_object']['hostname']."\n";
 echo "</pre></td></tr>";
 echo "<tr><td colspan='2'><a href='vm.php?action=stop'>"._("Click here to stop the machine")."</a></td></tr>";
 echo "</table>"; 

} // empty infos ?>
</div>

<br/>
<br/>
<hr/>
<h3><?php __("Tips"); ?></h3>
<a href="javascript:;" onclick="$('#tips-script').toggle();"><?php __("Can I start or stop my console access with a script?"); ?></a>
<div id='tips-script'>
<fieldset>
<?php __("You can script the launch the console access in command line by using this url:"); ?>
<pre>
http://<?php echo $mem->user['login'].':ALTERNC_PASSWORD@'.$host.'/vm.php?http_auth=1&amp;script=1&amp;action=start' ?>
</pre>
<?php __("You can halt the vm by using:"); ?>
<pre>
http://<?php echo $mem->user['login'].':ALTERNC_PASSWORD@'.$host.'/vm.php?http_auth=1&amp;script=1&amp;action=stop' ?>
</pre>
<?php __("And you can see existing vm informations (if exist) by using:"); ?>
<pre>
http://<?php echo $mem->user['login'].':ALTERNC_PASSWORD@'.$host.'/vm.php?http_auth=1&amp;script=1' ?>
</pre>
<i><?php __("Warning: if you do not use HTTPS, your password will be transfered without any protection"); ?></i>
</fieldset>
</div>

<br/>
<a href="javascript:;" onclick="$('#tips-soft').toggle();"><?php __("Which software can I use?"); ?></a>
<div id='tips-soft'>
<fieldset>
<?php __("To have a remote console with SSH, you can use Putty. To transfert files, you can use Filezilla."); ?>
</fieldset>
</div>

<script type="text/javascript">
$('#tips-script').toggle();
$('#tips-soft').toggle();
</script>

<?php
include_once("foot.php");
?>