<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CacheHelper;

class ClearFastCache extends Command
{
    protected $signature = 'cache:fast-clear';
    protected $description = 'Clear all fast caches for maximum performance';

    public function handle()
    {
        // Limpiar todos los caches personalizados
        CacheHelper::clearAll();
        
        // Limpiar cache de Laravel
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('config:clear');
        
        $this->info('✅ All fast caches cleared successfully!');
        $this->info('🚀 System ready for maximum performance!');
    }
}