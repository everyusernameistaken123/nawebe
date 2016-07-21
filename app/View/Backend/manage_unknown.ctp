
<h3>Wörterbuch verwalten</h3>

<div class="row-fluid">
<div class="span4 offset4">
	<table>
	<thead>
		<tr>
			<th>Wort</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($unknown as $word) {
		echo '<tr>';
		echo '<td>'.$word['UnknownWord']['word'].'</td>';
		echo '<td>';
		echo '	<a href="#" onclick="return confirmWord('.$word['UnknownWord']['id'].')" class="btn btn-success btn-mini">Ja</a>';
		echo '	<a href="#" onclick="return denyWord('.$word['UnknownWord']['id'].')" class="btn btn-danger btn-mini">Nein</a>';
		echo '	<span id="check'.$word['UnknownWord']['id'].'"></span>';
		echo '</td>';
		echo '</tr>';
	}
	?>
	</tbody>
	</table>
	<a href="" class="btn">Reload</a>
</div>
</div>

<script>

function confirmWord(id) {
	url = '<?php echo $this->Html->Url(array('controller'=>'backend','action'=>'processUnknown')); ?>/'+id+'/1';
	$.ajax({
		url: url,
		success: function (data) {
			if (data == 'OK') {
				//alert("OK");
				$('#check'+id).addClass('fa fa-save alert-success');
			} else {
				alert(data);
			}
		}
	});
	return false;
}
function denyWord(id) {
	url = '<?php echo $this->Html->Url(array('controller'=>'backend','action'=>'processUnknown')); ?>/'+id;
	$.ajax({
		url: url,
		success: function (data) {
			if (data == 'OK') {
				//alert("OK");
				$('#check'+id).addClass('fa fa-save alert-danger');
			} else {
				alert(data);
			}
		}
	});
	return false;
}

</script>