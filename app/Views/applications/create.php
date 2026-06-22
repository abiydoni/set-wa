<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center">
    <a href="<?= base_url('applications') ?>" class="mr-4 text-gray-400 hover:text-primary transition-colors">
        <i class="fa-solid fa-arrow-left text-xl"></i>
    </a>
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= lang('App.add_application') ?></h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1"><?= lang('App.add_app_desc') ?></p>
    </div>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden max-w-3xl mb-12">
    <div class="p-6 bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
        <form action="<?= base_url('applications/save') ?>" method="POST" class="space-y-6">
            <div class="space-y-4">
                <div class="border-b border-gray-200 dark:border-dark-border pb-2">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-cube text-primary mr-2"></i> <?= lang('App.app_details') ?></h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.app_name') ?></label>
                        <input type="text" name="name" required placeholder="<?= lang('App.app_name_placeholder') ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.appsbee_api_key') ?> <?= lang('App.optional') ?></label>
                        <input type="text" name="appsbee_api_key" placeholder="<?= lang('App.appsbee_api_key_placeholder') ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="border-b border-gray-200 dark:border-dark-border pb-2">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-database text-primary mr-2"></i> <?= lang('App.database_config') ?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= lang('App.db_config_desc') ?></p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_host') ?></label>
                        <input type="text" name="db_host" id="db_host" value="<?= esc($defaultDb['host'] ?? 'localhost') ?>" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_name') ?> <span class="text-red-500">*</span></label>
                        <input type="text" name="db_name" id="db_name" value="<?= esc($defaultDb['name'] ?? '') ?>" required placeholder="<?= lang('App.db_name_placeholder') ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_user') ?></label>
                        <input type="text" name="db_user" id="db_user" value="<?= esc($defaultDb['user'] ?? 'root') ?>" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.db_password') ?></label>
                        <input type="password" name="db_pass" id="db_pass" value="<?= esc($defaultDb['pass'] ?? '') ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <a href="<?= base_url('applications') ?>" class="px-6 py-2 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-800 dark:hover:text-gray-200 transition-colors mr-4"><?= lang('App.cancel') ?></a>
                <button type="submit" class="px-6 py-2 bg-primary hover:bg-blue-600 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> <?= lang('App.save_app') ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>