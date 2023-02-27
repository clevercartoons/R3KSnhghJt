<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('online_exam') ." ". translate('list')?></h4>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-hover mb-none table-condensed exam-list" width="100%">
			<thead>
				<tr>
					<th class="no-sort"><?=translate('sl')?></th>
					<th><?=translate('title')?></th>
					<th><?=translate('class')?> (<?=translate('section')?>)</th>
					<th class="no-sort"><?=translate('subject')?></th>
					<th><?=translate('questions_qty')?></th>
					<th><?=translate('start_time')?></th>
					<th><?=translate('end_time')?></th>
					<th><?=translate('duration')?></th>
					<th class="no-sort"><?=translate('exam_status')?></th>
					<th><?=translate('action')?></th>
				</tr>
			</thead>
		</table>
	</div>
</section>

<div class="zoom-anim-dialog modal-block modal-block-lg mfp-hide payroll-t-modal" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-users-between-lines"></i> <?php echo translate('exam_result'); ?></h4>
		</header>
		<div class="panel-body">
			<div id="quick_view"></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		// initiate Datatable
		initDatatable('.exam-list', 'userrole/getExamListDT', {}, 25);
	});
</script>