
<h3>Doppelte Inhalte</h3>

<div class="row-fluid">
<div class="span10 offset1">
	
	<a href="<?php echo $this->Html->Url(array('action'=>'duplicates', $page-1)); ?>">Prev</a>
	<?php echo "Page: $page";?>
	<a href="<?php echo $this->Html->Url(array('action'=>'duplicates', $page+1)); ?>">Next</a>
	
	<table>
	<thead>
		<tr>
			<th>Titel</th>
			<th>URL</th>
			<th>Words</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($duplicates as $data) {
		echo '<tr>';
		echo '<td>'.$data['w1']['Website']['title'].'<br/>'.$data['w2']['Website']['title'].'</td>';
		echo '<td>'.$data['w1']['Website']['url'].'<br/>'.$data['w2']['Website']['url'].'</td>';
		echo '<td>'.$data['w1']['Website']['words'].'<br/>'.$data['w2']['Website']['words'].'</td>';
		echo '<td>';
		echo '	<a href="#" onclick="return deleteWebsite('.$data['w2']['Website']['id'].');" class="btn btn-danger" title="keep older id, delete newer">Delete</a>';
		echo '	<span id="check'.$data['w2']['Website']['id'].'"></span>';
		echo '</td>';
		echo '</tr>';
	}
	?>
	</tbody>
	</table>
</div>
</div>

<script>

function deleteWebsite(id) {
	url = '<?php echo $this->Html->Url(array('controller'=>'backend','action'=>'deleteWebsite')); ?>/'+id;
	$.ajax({
		url: url,
		success: function (data) {
			if (data == 'OK') {
				//alert("OK");
				$('#check'+id).addClass('fa fa-trash alert-success');
			} else {
				alert(data);
			}
		}
	});
	return false;
}

</script>