<?php

namespace App\Traits;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Get the activity log service instance
     */
    protected function activityLog(): ActivityLogService
    {
        return app(ActivityLogService::class);
    }

    /**
     * Log a create action
     */
    protected function logCreate(Model $model, ?string $customDescription = null): void
    {
        $this->activityLog()->logCreate($model, $customDescription);
    }

    /**
     * Log an update action
     */
    protected function logUpdate(Model $model, ?string $customDescription = null): void
    {
        $this->activityLog()->logUpdate($model, $customDescription);
    }

    /**
     * Log a delete action
     */
    protected function logDelete(Model $model, ?string $customDescription = null): void
    {
        $this->activityLog()->logDelete($model, $customDescription);
    }

    /**
     * Log a restore action
     */
    protected function logRestore(Model $model, ?string $customDescription = null): void
    {
        $this->activityLog()->logRestore($model, $customDescription);
    }

    /**
     * Log a status change
     */
    protected function logStatusChange(Model $model, string $oldStatus, string $newStatus): void
    {
        $this->activityLog()->logStatusChange($model, $oldStatus, $newStatus);
    }

    /**
     * Log a bulk action
     */
    protected function logBulkAction(string $action, string $modelType, int $count): void
    {
        $this->activityLog()->logBulkAction($action, $modelType, $count);
    }

    /**
     * Log an order update
     */
    protected function logOrderUpdate(string $modelType, int $count): void
    {
        $this->activityLog()->logOrderUpdate($modelType, $count);
    }

    /**
     * Log a file upload
     */
    protected function logFileUpload(string $filename, ?Model $relatedModel = null): void
    {
        $this->activityLog()->logFileUpload($filename, $relatedModel);
    }

    /**
     * Log a file delete
     */
    protected function logFileDelete(string $filename, ?Model $relatedModel = null): void
    {
        $this->activityLog()->logFileDelete($filename, $relatedModel);
    }

    /**
     * Log a settings update
     */
    protected function logSettingsUpdate(string $settingName, $oldValue = null, $newValue = null): void
    {
        $this->activityLog()->logSettingsUpdate($settingName, $oldValue, $newValue);
    }

    /**
     * Log a cache clear
     */
    protected function logCacheClear(?string $cacheType = null): void
    {
        $this->activityLog()->logCacheClear($cacheType);
    }

    /**
     * Log a custom action
     */
    protected function logAction(string $description, $subject = null): void
    {
        $this->activityLog()->log($description, $subject);
    }
}
