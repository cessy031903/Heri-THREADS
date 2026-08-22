<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageDatabase extends Component
{
    use WithFileUploads;

    /**
     * Real content tables only — excludes framework/infra tables
     * (sessions, cache, jobs, migrations, password_reset_tokens) which are
     * transient and regenerate on their own, not meaningful to back up.
     */
    private const CONTENT_TABLES = [
        'dances', 'attires', 'interactive_guides', 'guide_hotspots',
        'showcase_photos', 'users', 'audit_logs',
    ];

    /**
     * audit_logs is included in the backup export (useful history to keep
     * a copy of) but is never destructively replaced on restore — history
     * should be additive. Otherwise the restore action's own log entry
     * (written just before the transaction) would be erased by that same
     * restore, since it deletes-then-reinserts every row in the table.
     */
    private const RESTORE_SKIP_TABLES = ['audit_logs'];

    public $restoreFile;
    public bool $confirmingRestore = false;
    public ?string $pendingRestoreFilename = null;

    public function backup()
    {
        $dump = [
            'exported_at' => now()->toIso8601String(),
            'app'         => config('app.name'),
            'tables'      => [],
        ];

        foreach (self::CONTENT_TABLES as $table) {
            $dump['tables'][$table] = DB::table($table)->get()->toArray();
        }

        AuditLog::record('backup', 'database', 0, 'Full database export');

        $filename = 'heri-threads-backup-'.now()->format('Y-m-d_His').'.json';

        return response()->streamDownload(
            fn () => print(json_encode($dump, JSON_PRETTY_PRINT)),
            $filename,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * First step: validate and stage the uploaded file, but don't touch
     * the database yet — the admin must explicitly confirm.
     */
    public function stageRestore(): void
    {
        $this->validate([
            'restoreFile' => 'required|file|mimes:json|max:51200',
        ]);

        $this->pendingRestoreFilename = $this->restoreFile->getClientOriginalName();
        $this->confirmingRestore = true;
    }

    public function cancelRestore(): void
    {
        $this->reset(['restoreFile', 'confirmingRestore', 'pendingRestoreFilename']);
    }

    /**
     * Second step: the admin has confirmed. Automatically back up the
     * CURRENT database to storage first (so a bad restore file can be
     * recovered from), then replace each content table's rows.
     */
    public function confirmRestore(): void
    {
        if (! $this->restoreFile) {
            $this->cancelRestore();
            return;
        }

        $raw = json_decode(file_get_contents($this->restoreFile->getRealPath()), true);

        if (! is_array($raw) || ! isset($raw['tables']) || ! is_array($raw['tables'])) {
            $this->addError('restoreFile', 'This file is not a valid Heri-THREADS backup.');
            return;
        }

        // Safety net: snapshot the current data before overwriting anything.
        $safetyDump = ['exported_at' => now()->toIso8601String(), 'app' => config('app.name'), 'tables' => []];
        foreach (self::CONTENT_TABLES as $table) {
            $safetyDump['tables'][$table] = DB::table($table)->get()->toArray();
        }
        $safetyPath = 'backups/pre-restore-'.now()->format('Y-m-d_His').'.json';
        Storage::disk('local')->put($safetyPath, json_encode($safetyDump));

        // Logged BEFORE the destructive work: if the incoming backup's users
        // table wipes the currently signed-in admin's own row, auth()->id()
        // would no longer satisfy the audit_logs foreign key afterward.
        AuditLog::record('restore', 'database', 0, $this->pendingRestoreFilename ?? 'backup file');

        $currentUserId = auth()->id();

        DB::transaction(function () use ($raw, $currentUserId) {
            foreach (self::CONTENT_TABLES as $table) {
                if (in_array($table, self::RESTORE_SKIP_TABLES, true)) {
                    continue;
                }
                if (! isset($raw['tables'][$table]) || ! is_array($raw['tables'][$table])) {
                    continue;
                }

                $rows = array_map(fn ($row) => (array) $row, $raw['tables'][$table]);

                // Never delete the account that's actively performing the
                // restore — losing it mid-transaction would both break the
                // audit_logs foreign key and lock the admin out of their own
                // session with no way back in except direct DB access.
                if ($table === 'users') {
                    DB::table('users')->where('id', '!=', $currentUserId)->delete();
                    $rows = array_filter($rows, fn ($row) => (int) ($row['id'] ?? 0) !== $currentUserId);
                } else {
                    DB::table($table)->delete();
                }

                foreach (array_chunk($rows, 200) as $chunk) {
                    if ($chunk) {
                        DB::table($table)->insert($chunk);
                    }
                }
            }
        });

        $this->dispatch('toast', message: 'Database restored successfully.', type: 'success');

        $this->reset(['restoreFile', 'confirmingRestore', 'pendingRestoreFilename']);
    }

    public function render()
    {
        return view('livewire.admin.settings.manage-database')
            ->layout('layouts.admin', ['title' => 'Manage Database']);
    }
}
