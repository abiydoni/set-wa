<?= $this->extend('layout/main') ?>
<?php
/**
 * @var array $app
 * @var array $tasks
 */
?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-8">
    <div class="flex items-center">
        <a href="<?= base_url('applications') ?>" class="mr-4 text-gray-400 hover:text-primary transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= lang('App.tasks_for') ?> <?= esc($app['name']) ?></h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1"><?= lang('App.tasks_desc') ?></p>
        </div>
    </div>
    <a href="<?= base_url('applications/tasks/create/'.$app['id']) ?>" class="px-6 py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex items-center">
        <i class="fa-solid fa-plus mr-2"></i> <?= lang('App.add_task') ?>
    </a>
</div>

<!-- Info Cron Job -->
<div class="bg-blue-50 dark:bg-blue-900/30 p-6 rounded-xl border border-blue-100 dark:border-blue-800/50 mb-6 flex items-start">
    <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 text-2xl mr-4 mt-1"></i>
    <div>
        <h3 class="text-lg font-bold text-blue-800 dark:text-blue-300"><?= lang('App.how_to_run_task') ?></h3>
        <p class="text-blue-700 dark:text-blue-200 mt-1 text-sm"><?= lang('App.run_task_desc') ?> <?= lang('App.recommended_log') ?></p>
        <p class="text-blue-700 dark:text-blue-200 mt-2 text-sm font-mono bg-blue-100 dark:bg-blue-800/50 px-3 py-2 rounded whitespace-normal break-all">
            /usr/local/bin/ea-php83 <?= ROOTPATH ?>apps/run_task.php [ID_TASK] &gt;&gt; <?= rtrim(ROOTPATH, '/') ?>/apps/log/log_[ID_TASK].txt 2&gt;&amp;1
        </p>
        <p class="text-blue-600 dark:text-blue-300 mt-3 text-sm font-semibold"><?= lang('App.cron_example') ?></p>
        <p class="text-blue-700 dark:text-blue-200 mt-1 text-sm font-mono bg-white/50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 px-3 py-2 rounded whitespace-normal break-all">
            /usr/local/bin/ea-php83 <?= ROOTPATH ?>apps/run_task.php 1 &gt;&gt; <?= rtrim(ROOTPATH, '/') ?>/apps/log/log_1.txt 2&gt;&amp;1
        </p>
        
        <div class="mt-4 text-xs text-blue-700 dark:text-blue-300 bg-blue-100/50 dark:bg-blue-800/30 p-3 rounded-lg">
            <p class="font-semibold mb-1"><i class="fa-solid fa-circle-question mr-1"></i> <?= lang('App.short_explanation') ?></p>
            <ul class="list-disc list-inside space-y-1 ml-1">
                <li><strong class="font-mono bg-white/50 dark:bg-blue-900/50 px-1 rounded">[ID_TASK]</strong> <?= lang('App.id_task_explanation') ?></li>
                <li><strong class="font-mono bg-white/50 dark:bg-blue-900/50 px-1 rounded">ea-php83</strong> <?= lang('App.php_version_explanation') ?></li>
            </ul>
        </div>
    </div>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-dark-border text-gray-600 dark:text-gray-400 text-sm">
                    <th class="p-4 font-semibold w-16">ID</th>
                    <th class="p-4 font-semibold"><?= lang('App.task_name') ?></th>
                    <th class="p-4 font-semibold"><?= lang('App.task_type') ?></th>
                    <th class="p-4 font-semibold"><?= lang('App.wa_id') ?></th>
                    <th class="p-4 font-semibold">Cron Command</th>
                    <th class="p-4 font-semibold text-center"><?= lang('App.action') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-dark-border bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
                <?php if (empty($tasks)): ?>
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-clipboard-list text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                        <?= lang('App.no_tasks') ?>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                        <td class="p-4 font-bold text-gray-800 dark:text-gray-200">#<?= esc($task['id']) ?></td>
                        <td class="p-4 font-medium text-gray-800 dark:text-gray-200"><?= esc($task['task_name']) ?></td>
                        <td class="p-4">
                            <?php if ($task['task_type'] === 'php'): ?>
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300 rounded text-xs font-semibold whitespace-nowrap border border-purple-200 dark:border-purple-800"><i class="fa-brands fa-php mr-1"></i> Script</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded text-xs font-semibold whitespace-nowrap border border-blue-200 dark:border-blue-800"><i class="fa-solid fa-database mr-1"></i> Query</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-gray-600 dark:text-gray-400">
                            <?= esc($task['wa_id']) ?>
                        </td>
                        <td class="p-4 text-gray-600 dark:text-gray-400">
                            <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-mono border border-gray-200 dark:border-gray-600 truncate inline-block max-w-[250px]" title="/usr/local/bin/ea-php83 <?= ROOTPATH ?>apps/run_task.php <?= esc($task['id']) ?> >> <?= rtrim(ROOTPATH, '/') ?>/apps/log/log_<?= esc($task['id']) ?>.txt 2>&1">
                                /usr/local/bin/ea-php83 ... run_task.php <?= esc($task['id']) ?> >> ...
                            </span>
                        </td>
                        <td class="p-4 text-center space-x-2 whitespace-nowrap">
                            <a href="<?= base_url('applications/tasks/run/'.$task['id']) ?>" class="text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300 p-2 transition-colors" title="<?= lang('App.run') ?>">
                                <i class="fa-solid fa-play"></i>
                            </a>
                            <a href="<?= base_url('applications/tasks/edit/'.$task['id']) ?>" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 p-2 transition-colors" title="<?= lang('App.edit') ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="<?= base_url('applications/tasks/delete/'.$task['id']) ?>" class="text-red-400 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 p-2 transition-colors" hx-confirm="<?= lang('App.confirm_delete') ?>" title="<?= lang('App.delete') ?>">
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
