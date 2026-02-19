<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log a generic admin action
     */
    public function log(string $description, $subject = null, ?User $user = null): ActivityLog
    {
        return ActivityLog::log($description, $subject, $user);
    }

    /**
     * Log a CREATE action
     */
    public function logCreate(Model $model, ?string $customDescription = null): ActivityLog
    {
        $modelName = $this->getModelDisplayName($model);
        $description = $customDescription ?? "Created {$modelName}: {$this->getModelIdentifier($model)}";

        return $this->log($description, $model);
    }

    /**
     * Log an UPDATE action
     */
    public function logUpdate(Model $model, ?string $customDescription = null): ActivityLog
    {
        $modelName = $this->getModelDisplayName($model);
        $description = $customDescription ?? "Updated {$modelName}: {$this->getModelIdentifier($model)}";

        return $this->log($description, $model);
    }

    /**
     * Log a DELETE action
     */
    public function logDelete(Model $model, ?string $customDescription = null): ActivityLog
    {
        $modelName = $this->getModelDisplayName($model);
        $description = $customDescription ?? "Deleted {$modelName}: {$this->getModelIdentifier($model)}";

        return $this->log($description, $model);
    }

    /**
     * Log a RESTORE action (for soft deletes)
     */
    public function logRestore(Model $model, ?string $customDescription = null): ActivityLog
    {
        $modelName = $this->getModelDisplayName($model);
        $description = $customDescription ?? "Restored {$modelName}: {$this->getModelIdentifier($model)}";

        return $this->log($description, $model);
    }

    /**
     * Log a STATUS CHANGE action
     */
    public function logStatusChange(Model $model, string $oldStatus, string $newStatus): ActivityLog
    {
        $modelName = $this->getModelDisplayName($model);
        $description = "Changed {$modelName} status from '{$oldStatus}' to '{$newStatus}': {$this->getModelIdentifier($model)}";

        return $this->log($description, $model);
    }

    /**
     * Log a BULK action
     */
    public function logBulkAction(string $action, string $modelType, int $count): ActivityLog
    {
        $description = "Performed bulk {$action} on {$count} {$modelType}(s)";

        return $this->log($description);
    }

    /**
     * Log an ORDER UPDATE action
     */
    public function logOrderUpdate(string $modelType, int $count): ActivityLog
    {
        $description = "Updated sort order for {$count} {$modelType}(s)";

        return $this->log($description);
    }

    /**
     * Log a FILE UPLOAD action
     */
    public function logFileUpload(string $filename, ?Model $relatedModel = null): ActivityLog
    {
        $description = "Uploaded file: {$filename}";

        if ($relatedModel) {
            $modelName = $this->getModelDisplayName($relatedModel);
            $description .= " for {$modelName}: {$this->getModelIdentifier($relatedModel)}";
        }

        return $this->log($description, $relatedModel);
    }

    /**
     * Log a FILE DELETE action
     */
    public function logFileDelete(string $filename, ?Model $relatedModel = null): ActivityLog
    {
        $description = "Deleted file: {$filename}";

        if ($relatedModel) {
            $modelName = $this->getModelDisplayName($relatedModel);
            $description .= " from {$modelName}: {$this->getModelIdentifier($relatedModel)}";
        }

        return $this->log($description, $relatedModel);
    }

    /**
     * Log a SETTINGS UPDATE action
     */
    public function logSettingsUpdate(string $settingName, $oldValue = null, $newValue = null): ActivityLog
    {
        $description = "Updated setting: {$settingName}";

        if ($oldValue !== null && $newValue !== null) {
            $description .= " (from '{$oldValue}' to '{$newValue}')";
        }

        return $this->log($description);
    }

    /**
     * Log a LOGIN action
     */
    public function logLogin(?User $user = null): ActivityLog
    {
        $user = $user ?? Auth::user();
        $description = 'Admin logged in';

        return $this->log($description, null, $user);
    }

    /**
     * Log a LOGOUT action
     */
    public function logLogout(?User $user = null): ActivityLog
    {
        $user = $user ?? Auth::user();
        $description = 'Admin logged out';

        return $this->log($description, null, $user);
    }

    /**
     * Log a FAILED LOGIN attempt
     */
    public function logFailedLogin(string $email): ActivityLog
    {
        $description = "Failed login attempt for email: {$email}";

        return ActivityLog::create([
            'user_id' => null,
            'user_name' => 'Unknown',
            'user_type' => 'system',
            'description' => $description,
            'performed_at' => now(),
        ]);
    }

    /**
     * Log a PERMISSION CHANGE action
     */
    public function logPermissionChange(User $targetUser, string $action, ?string $details = null): ActivityLog
    {
        $description = "Changed permissions for user '{$targetUser->name}': {$action}";

        if ($details) {
            $description .= " - {$details}";
        }

        return $this->log($description);
    }

    /**
     * Log a ROLE ASSIGNMENT action
     */
    public function logRoleAssignment(User $targetUser, string $roleName): ActivityLog
    {
        $description = "Assigned role '{$roleName}' to user '{$targetUser->name}'";

        return $this->log($description);
    }

    /**
     * Log a CACHE CLEAR action
     */
    public function logCacheClear(?string $cacheType = null): ActivityLog
    {
        $description = $cacheType
            ? "Cleared {$cacheType} cache"
            : 'Cleared application cache';

        return $this->log($description);
    }

    /**
     * Log a DATABASE action
     */
    public function logDatabaseAction(string $action, ?string $details = null): ActivityLog
    {
        $description = "Database action: {$action}";

        if ($details) {
            $description .= " - {$details}";
        }

        return $this->log($description);
    }

    /**
     * Log an EXPORT action
     */
    public function logExport(string $exportType, ?int $recordCount = null): ActivityLog
    {
        $description = "Exported {$exportType}";

        if ($recordCount) {
            $description .= " ({$recordCount} records)";
        }

        return $this->log($description);
    }

    /**
     * Log an IMPORT action
     */
    public function logImport(string $importType, int $recordCount, ?int $failedCount = null): ActivityLog
    {
        $description = "Imported {$recordCount} {$importType}(s)";

        if ($failedCount && $failedCount > 0) {
            $description .= " ({$failedCount} failed)";
        }

        return $this->log($description);
    }

    /**
     * Get recent activities for a specific user
     */
    public function getRecentActivitiesByUser(int $userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::byUser($userId)
            ->orderBy('performed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent admin activities
     */
    public function getRecentAdminActivities(int $limit = 100): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::byUserType('admin')
            ->orderBy('performed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities for a specific model
     */
    public function getModelActivities(Model $model, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::bySubject(get_class($model), $model->id)
            ->orderBy('performed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities within a date range
     */
    public function getActivitiesByDateRange(\DateTime $startDate, \DateTime $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::whereBetween('performed_at', [$startDate, $endDate])
            ->orderBy('performed_at', 'desc')
            ->get();
    }

    /**
     * Get activity statistics
     */
    public function getActivityStats(int $days = 30): array
    {
        $activities = ActivityLog::recent($days)->get();

        return [
            'total' => $activities->count(),
            'by_user_type' => $activities->groupBy('user_type')->map->count(),
            'by_date' => $activities->groupBy(function ($item) {
                return $item->performed_at->format('Y-m-d');
            })->map->count(),
            'top_users' => $activities->groupBy('user_name')
                ->map->count()
                ->sortDesc()
                ->take(10),
        ];
    }

    /**
     * Clean old activity logs
     */
    public function cleanOldLogs(int $daysToKeep = 180): int
    {
        $cutoffDate = now()->subDays($daysToKeep);

        $deletedCount = ActivityLog::where('performed_at', '<', $cutoffDate)->delete();

        if ($deletedCount > 0) {
            $this->log("Cleaned {$deletedCount} old activity log(s) older than {$daysToKeep} days");
        }

        return $deletedCount;
    }

    /**
     * Get a human-readable model display name
     */
    protected function getModelDisplayName(Model $model): string
    {
        $className = class_basename($model);

        // Convert PascalCase to readable format
        return preg_replace('/(?<!^)([A-Z])/', ' $1', $className);
    }

    /**
     * Get a model identifier (title, name, or ID)
     */
    protected function getModelIdentifier(Model $model): string
    {
        // Try common identifier fields
        if (isset($model->title)) {
            return $model->title;
        }

        if (isset($model->name)) {
            return $model->name;
        }

        if (isset($model->slug)) {
            return $model->slug;
        }

        // Fallback to ID
        return "ID #{$model->id}";
    }
}
