<h3>{{ naglerror }}</h3>

<?php if (APPLICATION_ENV != \Phalcony\Application::ENV_PRODUCTION && $error): ?>
<?php if ($error) echo $error->message(); ?>
<br>in <?php echo $error->file(); ?> on line <?php echo $error->line(); ?><br>
<?php if ($error->isException()) { ?>
<pre><?php echo $error->exception()->getTraceAsString(); ?></pre>
<?php } ?>
<?php endif; ?>