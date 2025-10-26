<option value="">Seleccionar puesto</option>
<?php if(isset($puestos)): ?>
    <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($puesto->idPuestos); ?>"><?php echo e($puesto->Puesto); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <?php
        $puestos = \App\Models\Puesto::all();
    ?>
    <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($puesto->idPuestos); ?>"><?php echo e($puesto->Puesto); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/puestos/lista.blade.php ENDPATH**/ ?>