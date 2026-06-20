<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= lang('App.applications') ?></h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1"><?= lang('App.manage_apps_desc') ?></p>
    </div>
    <a href="<?= base_url('applications/create') ?>" class="px-6 py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex items-center">
        <i class="fa-solid fa-plus mr-2"></i> <?= lang('App.add_application') ?>
    </a>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-dark-border text-gray-600 dark:text-gray-400 text-sm">
                    <th class="p-4 font-semibold"><?= lang('App.name') ?></th>
                    <th class="p-4 font-semibold"><?= lang('App.db_name') ?></th>
                    <th class="p-4 font-semibold text-center"><?= lang('App.action') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-dark-border bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
                <?php if (empty($apps)): ?>
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                        <?= lang('App.no_apps_found') ?>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($apps as $app): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                        <td class="p-4 font-medium text-gray-800 dark:text-gray-200"><?= esc($app['name']) ?></td>
                        <td class="p-4 text-gray-600 dark:text-gray-400">
                            <?= esc($app['db_name']) ?> <span class="text-xs text-gray-400 dark:text-gray-500">(<?= esc($app['db_host']) ?>)</span>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <a href="<?= base_url('applications/tasks/'.$app['id']) ?>" class="text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300 p-2 transition-colors" title="<?= lang('App.manage_tasks') ?>">
                                <i class="fa-solid fa-list-check"></i>
                            </a>
                            <a href="<?= base_url('applications/edit/'.$app['id']) ?>" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 p-2 transition-colors" title="<?= lang('App.edit') ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="<?= base_url('applications/delete/'.$app['id']) ?>" class="text-red-400 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 p-2 transition-colors" onclick="return confirm('<?= lang('App.confirm_delete') ?>')" title="<?= lang('App.delete') ?>">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
