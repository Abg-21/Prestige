ELIMINADOS DEBUG - FUNCIONANDO

<h2>Usuario actual:</h2>
<p>Logueado: <?php echo e(auth()->check() ? 'SÍ' : 'NO'); ?></p>
<?php if(auth()->check()): ?>
    <p>ID: <?php echo e(auth()->id()); ?></p>
    <p>Nombre: <?php echo e(auth()->user()->nombre ?? auth()->user()->name ?? 'N/A'); ?></p>
    <p>Roles: 
        <?php if(auth()->user()->roles): ?>
            <?php echo e(auth()->user()->roles->pluck('nombre')->join(', ')); ?>

        <?php else: ?>
            Sin roles
        <?php endif; ?>
    </p>
<?php endif; ?>

<h2>Permisos:</h2>
<p>Eliminados: <?php echo e(\App\Helpers\PermissionHelper::hasPermission('eliminados') ? 'SÍ' : 'NO'); ?></p>

<h2>Datos:</h2>
<p>Total eliminados: <?php echo e(\App\Models\Eliminado::count()); ?></p>
<p>No restaurados: <?php echo e(\App\Models\Eliminado::whereNull('restaurado_en')->count()); ?></p>

<h2>Test de controlador:</h2>
<a href="<?php echo e(route('eliminados.index')); ?>" style="background: #FE7743; color: white; padding: 10px; text-decoration: none; border-radius: 4px;">IR A ELIMINADOS</a>

<script>
console.log('Debug cargado correctamente');
</script><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/debug-eliminados.blade.php ENDPATH**/ ?>