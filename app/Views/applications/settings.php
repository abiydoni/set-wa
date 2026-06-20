<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center">
    <a href="<?= base_url('applications') ?>" class="mr-4 text-gray-400 hover:text-primary transition-colors">
        <i class="fa-solid fa-arrow-left text-xl"></i>
    </a>
    <div>
        <h1 class="text-3xl font-bold text-gray-800"><?= lang('App.app_settings') ?>: <?= esc($app['name']) ?></h1>
        <p class="text-gray-500 mt-1"><?= lang('App.app_settings_desc') ?></p>
    </div>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden">
    <div class="bg-white border-b border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800"><i class="fa-solid fa-sliders text-primary mr-2"></i> <?= lang('App.table_config') ?></h3>
    </div>
    
    <div class="p-6">
        <?php if (!empty($error_msg)): ?>
            <div class="bg-red-50 text-red-500 p-4 rounded-xl border border-red-200 mb-6 flex items-start">
                <i class="fa-solid fa-triangle-exclamation mt-1 mr-3"></i>
                <div>
                    <h4 class="font-bold"><?= lang('App.error_reading_config') ?></h4>
                    <p class="text-sm mt-1"><?= esc($error_msg) ?></p>
                </div>
            </div>
            <a href="<?= base_url('applications') ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors"><?= lang('App.back') ?></a>
        <?php elseif (empty($settings)): ?>
            <div class="text-center text-gray-500 py-8">
                <i class="fa-solid fa-database text-4xl mb-3 text-gray-300 block"></i>
                <?= lang('App.no_config_data') ?>
            </div>
        <?php else: ?>
            <form action="<?= base_url('applications/settings/save/'.$app['id']) ?>" method="POST" class="space-y-6">
                
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <h4 class="text-md font-semibold text-blue-800 mb-4"><i class="fa-solid fa-key mr-2"></i> <?= lang('App.api_key_config') ?></h4>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Appsbee API Key</label>
                        <input type="text" name="app_api_key" value="<?= esc($app['appsbee_api_key']) ?>" placeholder="wa-xxxx" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white">
                        <p class="text-xs text-gray-500"><?= lang('App.api_key_desc') ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($settings as $setting): ?>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 capitalize"><?= esc(str_replace('_', ' ', $setting['nama'] ?? $setting['key'] ?? 'Unknown')) ?></label>
                        <?php 
                            // Try to detect what column name they use (nama, key, etc)
                            $keyName = isset($setting['nama']) ? 'nama' : (isset($setting['key']) ? 'key' : null);
                            $valueName = isset($setting['value']) ? 'value' : (isset($setting['nilai']) ? 'nilai' : null);
                            
                            if ($keyName && $valueName):
                        ?>
                            <!-- Jika fieldnya panjang, pakai textarea -->
                            <?php if (strlen($setting[$valueName]) > 50 || strpos($setting[$valueName], "\n") !== false): ?>
                                <textarea name="<?= esc($setting[$keyName]) ?>" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white"><?= esc($setting[$valueName]) ?></textarea>
                            <?php else: ?>
                                <input type="text" name="<?= esc($setting[$keyName]) ?>" value="<?= esc($setting[$valueName]) ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white">
                            <?php endif; ?>
                            
                            <?php if(isset($setting['keterangan'])): ?>
                                <p class="text-xs text-gray-500"><?= esc($setting['keterangan']) ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-red-500 text-xs">Struktur kolom tidak dikenali.</p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> <?= lang('App.save_config') ?>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
