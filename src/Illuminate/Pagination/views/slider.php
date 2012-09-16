<?php
	$current = $paginator->getCurrentPage();

	$last = $paginator->getLastPage();
?>

<?php if ($last > 1): ?>
	<div class="pagination">
		<ul>
			<?php echo page_previous($current); ?>

			<?php
				// The hard-coded 13 accounts for the minimum number of elements we need
				// to be able to make a "slider". It includes the current page, every
				// one of the three adjacents on each side, ellipses, and the caps.
			?>
			<?php if ($last < 13): ?>

				<?php echo page_range(1, $last, $paginator); ?>

			<?php else: ?>

				<?php echo page_slider($current, $last, $paginator); ?>

			<?php endif; ?>

			<?php echo page_next($current, $last); ?>
		</ul>
	</div>
<?php endif; ?>