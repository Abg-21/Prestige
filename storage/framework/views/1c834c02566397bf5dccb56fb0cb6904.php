<div class="container" style="max-width: 800px; margin-top: 40px;">
    <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 28px;">
        <h2 style="color: #447D9B; margin-bottom: 24px;">Detalles del Puesto</h2>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Categoría:</label>
                <p><?php echo e($puesto->Categoría); ?></p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Puesto:</label>
                <p><?php echo e($puesto->Puesto); ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Giro:</label>
                <p><?php echo e($puesto->giro ? $puesto->giro->Nombre : 'Sin giro'); ?></p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Cliente:</label>
                <p><?php echo e($puesto->cliente ? $puesto->cliente->Nombre : 'Sin cliente'); ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Zona:</label>
                <p><?php echo e($puesto->Zona); ?></p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Estado:</label>
                <p><?php echo e($puesto->Estado); ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Edad:</label>
                <p><?php echo e($puesto->Edad); ?></p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Escolaridad:</label>
                <p><?php echo e($puesto->Escolaridad); ?></p>
            </div>
        </div>

        <div class="mb-4">
            <label class="fw-bold">Experiencia:</label>
            <p><?php echo e($puesto->Experiencia); ?></p>
        </div>

        <?php
            // Función helper para decodificar campos JSON de manera segura
            function decodificarCampo($valor) {
                if (empty($valor) || $valor === 'null' || is_null($valor)) {
                    return [];
                }
                
                if (is_array($valor)) {
                    return $valor;
                }
                
                if (is_string($valor)) {
                    // Intentar decodificar JSON
                    if (substr($valor, 0, 1) === '[' || substr($valor, 0, 1) === '{') {
                        $decoded = json_decode($valor, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            return array_filter($decoded); // Remover elementos vacíos
                        }
                    }
                    
                    // Si no es JSON, tratar como texto separado por comas
                    return array_filter(array_map('trim', explode(',', $valor)));
                }
                
                return [];
            }
            
            $conocimientos = decodificarCampo($puesto->Conocimientos);
            $funciones = decodificarCampo($puesto->Funciones);
            $habilidades = decodificarCampo($puesto->Habilidades);
        ?>

        <?php if(!empty($conocimientos)): ?>
        <div class="mb-4">
            <label class="fw-bold">Conocimientos:</label>
            <ul>
                <?php $__currentLoopData = $conocimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conocimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($conocimiento)): ?>
                        <li><?php echo e($conocimiento); ?></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if(!empty($funciones)): ?>
        <div class="mb-4">
            <label class="fw-bold">Funciones:</label>
            <ul>
                <?php $__currentLoopData = $funciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $funcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($funcion)): ?>
                        <li><?php echo e($funcion); ?></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if(!empty($habilidades)): ?>
        <div class="mb-4">
            <label class="fw-bold">Habilidades:</label>
            <ul>
                <?php $__currentLoopData = $habilidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habilidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($habilidad)): ?>
                        <li><?php echo e($habilidad); ?></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-end mt-4">
            <a href="<?php echo e(route('puestos.index')); ?>" class="btn btn-secondary ajax-link">Volver</a>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/puestos/show_puesto.blade.php ENDPATH**/ ?>