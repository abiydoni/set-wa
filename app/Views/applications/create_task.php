<?php
/**
 * @var array $app
 * @var string $defaultPhpScript
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- CodeMirror CSS & JS moved to main.php -->
<style>
.CodeMirror {
    height: 500px;
    font-family: 'Fira Code', 'Consolas', monospace;
    font-size: 14px;
    border-radius: 0 0 0.75rem 0.75rem;
}
.editor-fullscreen {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    z-index: 9999 !important;
    background-color: #111827 !important;
    padding: 2rem !important;
    margin: 0 !important;
    overflow-y: auto !important;
}
.editor-fullscreen .CodeMirror {
    height: calc(100vh - 200px) !important;
}
.editor-fullscreen .bg-gray-50 {
    background-color: #1f2937 !important;
    border-color: #374151 !important;
    color: #9ca3af !important;
}
body.is-fullscreen .glass {
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}
body.is-fullscreen aside, 
body.is-fullscreen header {
    display: none !important;
}
body.is-fullscreen .z-10 {
    z-index: 9999 !important;
}
</style>
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center">
        <a href="<?= base_url('applications/tasks/' . $app['id']) ?>" class="mr-4 text-gray-400 hover:text-primary transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?= lang('App.add_task') ?></h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Aplikasi: <span class="font-semibold text-primary"><?= esc($app['name']) ?></span></p>
        </div>
    </div>
</div>

<div class="mb-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded-r-xl shadow-sm text-sm text-blue-800 dark:text-blue-300">
    <h3 class="font-semibold mb-2 flex items-center"><i class="fa-solid fa-circle-info mr-2"></i> <?= lang('App.usage_instructions') ?></h3>
    <p class="mb-2"><?= lang('App.task_instructions_desc') ?></p>
    <ul class="list-disc list-inside space-y-1 ml-2">
        <li><b>SQL Query:</b> <?= lang('App.sql_query_desc') ?></li>
        <li><b>Custom PHP Script:</b> <?= lang('App.php_script_desc') ?></li>
    </ul>
</div>

<div class="w-full">
    <!-- Form Container -->
    <div>
        <div class="glass rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="p-6 bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
                <form action="<?= base_url('applications/tasks/save/' . $app['id']) ?>" method="POST" class="space-y-6">
                    
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 dark:border-dark-border pb-2">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-cube text-primary mr-2"></i> <?= lang('App.task_identity') ?></h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.task_name') ?> <span class="text-red-500">*</span></label>
                                <input type="text" name="task_name" required placeholder="e.g. Daily Expired Report" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.wa_id') ?> <span class="text-red-500">*</span></label>
                                <input type="text" name="wa_id" required placeholder="Format: 628123xxx atau 1203xxx@g.us" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="border-b border-gray-200 dark:border-dark-border pb-2">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-server text-primary mr-2"></i> <?= lang('App.data_source') ?></h3>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.source_type') ?></label>
                            <select name="task_type" id="task_type" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                                <option value="sql">Database (SQL Query)</option>
                                <option value="php">Custom PHP Script</option>
                            </select>
                        </div>

                        <!-- WADAH UNTUK PHP SCRIPT -->
                        <div id="php_container" style="display: none;" class="space-y-0">
                            <div class="flex justify-between items-center bg-gray-800 p-3 rounded-t-xl border border-gray-700">
                                <label class="block text-sm font-medium text-gray-300">
                                    <?= lang('App.php_editor') ?> <span class="text-xs text-red-400"><?= lang('App.advanced') ?></span>
                                </label>
                                <div class="flex space-x-2">
                                    <button type="button" id="btnFullscreen" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-md shadow flex items-center transition-colors">
                                        <i class="fa-solid fa-expand mr-1"></i> <?= lang('App.fullscreen') ?>
                                    </button>
                                    <button type="button" id="btnCopyCode" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-md shadow flex items-center transition-colors">
                                        <i class="fa-solid fa-copy mr-1"></i> <?= lang('App.copy') ?>
                                    </button>
                                    <button type="button" id="btnTestPhp" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md shadow flex items-center transition-colors">
                                        <i class="fa-solid fa-play mr-1"></i> <?= lang('App.test_script') ?>
                                    </button>
                                </div>
                            </div>
                            <div class="border border-t-0 border-gray-700 rounded-b-xl overflow-hidden">
                                <textarea name="php_script" id="php_script" class="hidden"><?= esc($defaultPhpScript) ?></textarea>
                            </div>
                            
                            <div class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border dark:border-gray-700 mt-3">
                                <b class="dark:text-gray-300"><?= lang('App.php_guide') ?></b>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li><?= lang('App.php_guide_1') ?></li>
                                    <li><?= lang('App.php_guide_2') ?></li>
                                    <li><?= lang('App.php_guide_3') ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- WADAH UNTUK SQL & TEMPLATE (Hanya muncul jika tipe = sql) -->
                    <div id="sql_container" class="space-y-6">
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 dark:border-dark-border pb-2 flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-code text-primary mr-2"></i> <?= lang('App.custom_query') ?></h3>
                                <button type="button" id="btnTestQuery" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md shadow flex items-center transition-colors">
                                    <i class="fa-solid fa-play mr-1"></i> <?= lang('App.test_query') ?>
                                </button>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.sql_query_label') ?></label>
                                <textarea name="custom_query" id="custom_query" rows="4" placeholder="SELECT * FROM table_name WHERE condition" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-gray-50 dark:bg-gray-800 dark:text-white font-mono text-sm"></textarea>
                            </div>
                            <!-- Test Results Container -->
                            <div id="query_results" class="hidden mt-3 border dark:border-dark-border rounded-lg overflow-hidden text-sm"></div>
                        </div>
                        
                        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded-r-lg">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-1"><i class="fa-solid fa-lightbulb mr-2"></i> <?= lang('App.formatting_guide') ?></h4>
                            <p class="text-sm text-blue-700 dark:text-blue-200 mb-2"><?= lang('App.formatting_desc') ?></p>
                            <ul class="text-sm text-blue-700 dark:text-blue-200 list-disc list-inside space-y-1">
                                <li><code>{tanggal}</code> : <?= lang('App.tag_date') ?></li>
                                <li><code>{waktu}</code> : <?= lang('App.tag_time') ?></li>
                                <li><code>{jumlah_data}</code> : <?= lang('App.tag_count') ?></li>
                                <li><code>{no}</code> : <?= lang('App.tag_no') ?></li>
                                <li><code>{nama_kolom}</code> : <?= lang('App.tag_column') ?></li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <div class="border-b border-gray-200 dark:border-dark-border pb-2">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-gear text-primary mr-2"></i> <?= lang('App.msg_config') ?></h3>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.header_msg') ?></label>
                                <textarea name="message_template" rows="2" placeholder="*LAPORAN STOK HARIAN*" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white font-mono text-sm"></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.body_msg') ?></label>
                                <textarea name="body_message" rows="2" placeholder="*{no}. {name}* | Stok: {current_stock}" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white font-mono text-sm"></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= lang('App.footer_msg') ?></label>
                                <textarea name="message_footer" rows="2" placeholder="_Pesan otomatis dari sistem_" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white font-mono text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end border-t border-gray-200 dark:border-dark-border">
                        <a href="<?= base_url('applications/tasks/' . $app['id']) ?>" class="px-8 py-3 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-800 dark:hover:text-gray-200 transition-colors mr-4"><?= lang('App.cancel') ?></a>
                        <button type="submit" class="px-8 py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> <?= lang('App.save') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Test Script Modal -->
<div id="testModal" style="z-index: 10000;" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-dark-panel rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-4 border-b dark:border-dark-border flex justify-between items-center bg-gray-50 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200"><i class="fa-solid fa-vial text-primary mr-2"></i> <?= lang('App.test_php_result') ?></h3>
            <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 dark:text-gray-300">
            <div id="modalContent" class="text-sm"></div>
        </div>
        <div class="p-4 border-t dark:border-dark-border bg-gray-50 dark:bg-gray-800 flex justify-end">
            <button type="button" id="closeModalBtn" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors"><?= lang('App.close') ?></button>
        </div>
    </div>
</div>

<script>
(function() {
    const taskTypeSelect = document.getElementById('task_type');
    const sqlContainer = document.getElementById('sql_container');
    const phpContainer = document.getElementById('php_container');
    const phpTextArea = document.getElementById('php_script');

    // Initialize CodeMirror
    const editor = CodeMirror.fromTextArea(phpTextArea, {
        lineNumbers: true,
        mode: "text/x-php",
        theme: "dracula",
        matchBrackets: true,
        indentUnit: 4,
        indentWithTabs: false
    });

    editor.on('change', function() {
        phpTextArea.value = editor.getValue();
    });

    // Copy logic
    document.getElementById('btnCopyCode').addEventListener('click', function() {
        navigator.clipboard.writeText(editor.getValue()).then(() => {
            const icon = this.querySelector('i');
            icon.className = 'fa-solid fa-check mr-1 text-green-400';
            this.innerHTML = icon.outerHTML + ' Copied!';
            setTimeout(() => {
                icon.className = 'fa-solid fa-copy mr-1';
                this.innerHTML = icon.outerHTML + ' Copy';
            }, 2000);
        });
    });

    // Fullscreen logic
    document.getElementById('btnFullscreen').addEventListener('click', function(e) {
        e.preventDefault();
        const isFullscreen = phpContainer.classList.toggle('editor-fullscreen');
        document.body.classList.toggle('is-fullscreen', isFullscreen);
        
        if (isFullscreen) {
            this.innerHTML = '<i class="fa-solid fa-compress mr-1"></i> Exit Fullscreen';
        } else {
            this.innerHTML = '<i class="fa-solid fa-expand mr-1"></i> Fullscreen';
        }
        setTimeout(() => editor.refresh(), 50);
    });

    taskTypeSelect.addEventListener('change', function() {
        if(this.value === 'php') {
            sqlContainer.style.display = 'none';
            phpContainer.style.display = 'block';
            document.getElementById('custom_query').removeAttribute('required');
            setTimeout(() => editor.refresh(), 10);
        } else {
            sqlContainer.style.display = 'block';
            phpContainer.style.display = 'none';
        }
    });

    // Modal Logic
    const testModal = document.getElementById('testModal');
    const modalContent = document.getElementById('modalContent');
    const closeBtn1 = document.getElementById('closeModal');
    const closeBtn2 = document.getElementById('closeModalBtn');

    function hideModal() {
        testModal.classList.add('hidden');
    }

    closeBtn1.addEventListener('click', hideModal);
    closeBtn2.addEventListener('click', hideModal);

    document.getElementById('btnTestQuery').addEventListener('click', function() {
        const query = document.getElementById('custom_query').value;
        const resultsDiv = document.getElementById('query_results');
        
        if (!query.trim()) {
            alert('Query kosong!');
            return;
        }

        resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengeksekusi query...</div>';
        resultsDiv.classList.remove('hidden');

        fetch('<?= base_url('applications/tasks/test-query/' . $app['id']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'query=' + encodeURIComponent(query)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let html = '<div class="bg-green-50 dark:bg-green-900/30 border-b border-green-100 dark:border-green-800 p-2 px-4 flex justify-between items-center">';
                html += '<span class="text-green-700 dark:text-green-400 font-medium"><i class="fa-solid fa-check-circle mr-1"></i> Query Berhasil</span>';
                html += '<span class="text-xs text-green-600 dark:text-green-500">Total: ' + data.count + ' baris (Menampilkan max 5 baris)</span>';
                html += '</div>';
                
                if (data.count > 0 && data.preview.length > 0) {
                    html += '<div class="overflow-x-auto"><table class="w-full text-left border-collapse">';
                    
                    // Headers
                    html += '<thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs"><tr>';
                    Object.keys(data.preview[0]).forEach(key => {
                        html += '<th class="p-2 border-b dark:border-gray-700 whitespace-nowrap">' + key + '</th>';
                    });
                    html += '</tr></thead><tbody class="dark:bg-gray-900/50">';
                    
                    // Rows
                    data.preview.forEach(row => {
                        html += '<tr class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">';
                        Object.values(row).forEach(val => {
                            let displayVal = val;
                            if(val === null) displayVal = '<i class="text-gray-400">NULL</i>';
                            html += '<td class="p-2 whitespace-nowrap text-gray-700 dark:text-gray-300">' + displayVal + '</td>';
                        });
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table></div>';
                } else {
                    html += '<div class="p-4 text-gray-500 text-center">Query berhasil tetapi tidak ada data yang dikembalikan.</div>';
                }
                
                resultsDiv.innerHTML = html;
            } else {
                resultsDiv.innerHTML = '<div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Error: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            resultsDiv.innerHTML = '<div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Error koneksi.</div>';
        });
    });

    document.getElementById('btnTestPhp').addEventListener('click', function() {
        const phpScript = document.getElementById('php_script').value;
        
        if (!phpScript.trim()) {
            alert('PHP Script kosong!');
            return;
        }

        modalContent.innerHTML = '<div class="p-8 text-center text-gray-500"><i class="fa-solid fa-spinner fa-spin text-3xl mb-4 block"></i> Menjalankan script di server...</div>';
        testModal.classList.remove('hidden');

        fetch('<?= base_url('applications/tasks/test-php/' . $app['id']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'php_script=' + encodeURIComponent(phpScript)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let html = '<div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4 flex items-center">';
                html += '<i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>';
                html += '<div><h4 class="text-green-800 dark:text-green-400 font-bold">Script Berhasil Dijalankan</h4><p class="text-green-600 dark:text-green-500 text-xs mt-1">Berikut adalah preview pesan yang akan dikirim ke WhatsApp.</p></div>';
                html += '</div>';
                
                html += '<div class="bg-[#e5ddd5] dark:bg-[#111b21] p-4 rounded-xl relative">';
                html += '<div class="bg-white dark:bg-[#202c33] rounded-lg p-3 inline-block max-w-[85%] shadow-sm relative">';
                html += '<pre class="whitespace-pre-wrap text-[13.5px] text-gray-800 dark:text-[#e9edef] font-sans leading-relaxed">' + data.preview + '</pre>';
                html += '<div class="text-[10px] text-gray-400 dark:text-[#8696a0] text-right mt-1">' + new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + '</div>';
                html += '</div></div>';
                
                modalContent.innerHTML = html;
            } else {
                modalContent.innerHTML = '<div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4"><div class="flex items-center text-red-600 dark:text-red-400 mb-2"><i class="fa-solid fa-triangle-exclamation text-xl mr-2"></i><h4 class="font-bold">Error Eksekusi</h4></div><p class="text-red-700 dark:text-red-300 text-sm whitespace-pre-wrap">' + data.message + '</p></div>';
            }
        })
        .catch(error => {
            modalContent.innerHTML = '<div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-lg"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Error koneksi. Gagal menghubungi server.</div>';
        });
    });
})();
</script>
<?= $this->endSection() ?>
