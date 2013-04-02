<?php /* Smarty version 2.6.26, created on 2012-07-29 22:10:38
         compiled from test.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'test.tpl', 13, false),)), $this); ?>
<!DOCTYPE html>
<html>
<body>

<h1><?php echo $this->_tpl_vars['myHeading1']; ?>
</h1>

<p><?php echo $this->_tpl_vars['myParagraph']; ?>
</p>

<h2><?php echo $this->_tpl_vars['myHeading2']; ?>
</h2>

<table>
<?php $_from = $this->_tpl_vars['myCommunities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
	<tr style="background: <?php echo smarty_function_cycle(array('values' => 'silver, gray'), $this);?>
">
		<td><?php echo $this->_tpl_vars['k']; ?>
</td>
		<td><?php echo $this->_tpl_vars['v']; ?>
</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
</table>

</body>
</html>
