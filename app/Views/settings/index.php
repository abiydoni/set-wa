<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= lang('App.gateway_settings') ?></h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1"><?= lang('App.settings_desc') ?></p>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden">
    <div class="bg-white dark:bg-dark-panel border-b border-gray-100 dark:border-dark-border p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-sliders text-primary mr-2"></i> <?= lang('App.global_config') ?></h3>
    </div>
    
    <div class="p-6 bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
        <form action="<?= base_url('settings/save') ?>" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Appsbee URL -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.appsbee_endpoint') ?></label>
                    <input type="text" name="appsbeeUrl" value="<?= esc($settings['appsbeeUrl']['setting_value'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc($settings['appsbeeUrl']['description'] ?? '') ?></p>
                </div>
                
                <!-- Group ID -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.default_group_id') ?></label>
                    <input type="text" name="groupId" value="<?= esc($settings['groupId']['setting_value'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc($settings['groupId']['description'] ?? '') ?></p>
                </div>
                
                <!-- Manual Message -->
                <div class="space-y-2 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.manual_msg_template') ?></label>
                    <textarea name="manualMessage" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white"><?= esc($settings['manualMessage']['setting_value'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc($settings['manualMessage']['description'] ?? '') ?></p>
                </div>
            </div>

            <div class="mt-8 mb-4 border-t border-gray-200 dark:border-dark-border pt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-database text-primary mr-2"></i> <?= lang('App.default_db_settings') ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4"><?= lang('App.default_db_desc') ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_host') ?></label>
                    <input type="text" name="defaultDbHost" value="<?= esc($settings['defaultDbHost']['setting_value'] ?? 'localhost') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.default_db_prefix') ?></label>
                    <input type="text" name="defaultDbName" value="<?= esc($settings['defaultDbName']['setting_value'] ?? 'appsbeem_') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_user') ?></label>
                    <input type="text" name="defaultDbUser" value="<?= esc($settings['defaultDbUser']['setting_value'] ?? 'root') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_password') ?></label>
                    <input type="text" name="defaultDbPass" value="<?= esc($settings['defaultDbPass']['setting_value'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> <?= lang('App.save_settings') ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
