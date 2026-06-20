<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= lang('App.dashboard') ?></h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1"><?= lang('App.overview_desc') ?></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stat Card -->
    <div class="glass rounded-2xl p-6 shadow-sm border-l-4 border-primary hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"><?= lang('App.total_applications') ?></p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= $total_apps ?></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-primary text-xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions Card -->
    <div class="glass rounded-2xl p-6 shadow-sm lg:col-span-3">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4"><?= lang('App.quick_actions') ?></h3>
        <div class="flex flex-wrap gap-4">
            <a href="<?= base_url('applications/create') ?>" class="px-6 py-3 bg-gradient-to-r from-primary to-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all">
                <i class="fa-solid fa-plus mr-2"></i> <?= lang('App.add_application') ?>
            </a>
            <a href="<?= base_url('settings') ?>" class="px-6 py-3 bg-white dark:bg-dark-panel border border-gray-200 dark:border-dark-border text-gray-700 dark:text-gray-200 rounded-xl font-medium shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                <i class="fa-solid fa-gear mr-2"></i> <?= lang('App.gateway_settings') ?>
            </a>
        </div>
    </div>
</div>

<div class="mt-10 glass rounded-2xl p-6 shadow-sm">
    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4"><i class="fa-solid fa-circle-info text-primary mr-2"></i> <?= lang('App.how_it_works') ?></h3>
    <div class="prose max-w-none text-gray-600 dark:text-gray-300">
        <p><?= lang('App.how_it_works_desc') ?></p>
        <ul class="list-disc pl-5 mt-2 space-y-2">
            <li><?= lang('App.how_it_works_1') ?></li>
            <li><?= lang('App.how_it_works_2') ?></li>
        </ul>
    </div>
</div>
<?= $this->endSection() ?>
