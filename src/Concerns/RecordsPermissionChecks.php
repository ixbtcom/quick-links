<?php

namespace Niladam\QuickLinks\Concerns;

use BackedEnum;
use Niladam\QuickLinks\GateCollector;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Traits\HasPermissions;

trait RecordsPermissionChecks
{
    use HasPermissions {
        hasPermissionTo as protected spatieHasPermissionTo;
        checkPermissionTo as protected spatieCheckPermissionTo;
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $this->recordPermissionCheck($permission);

        return $this->spatieHasPermissionTo($permission, $guardName);
    }

    public function checkPermissionTo($permission, $guardName = null): bool
    {
        $this->recordPermissionCheck($permission);

        return $this->spatieCheckPermissionTo($permission, $guardName);
    }

    protected function recordPermissionCheck($permission): void
    {
        GateCollector::record(
            $this->normalizePermissionName($permission),
            [],
        );
    }

    protected function normalizePermissionName($permission): string
    {
        return match (true) {
            $permission instanceof BackedEnum => $permission->value,
            $permission instanceof PermissionContract => $permission->name,
            default => (string) $permission,
        };
    }
}
