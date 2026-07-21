<?php

namespace App\Models\Concerns;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasEmpresaScope
{
    protected static function bootHasEmpresaScope(): void
    {
        static::creating(function ($model) {
            if (empty($model->empresa_id)) {
                $model->empresa_id = static::resolveEmpresaId();
            }
        });

        static::addGlobalScope('empresa', function (Builder $builder) {
            $empresaId = static::resolveEmpresaId();

            if ($empresaId !== null) {
                $builder->where($builder->getModel()->getTable() . '.empresa_id', $empresaId);
            }
        });
    }

    protected static function resolveEmpresaId(): ?int
    {
        if (Auth::check()) {
            return Auth::user()?->empresa_id;
        }

        if (app()->runningInConsole()) {
            $empresa = Empresa::query()->orderBy('id')->first();

            return $empresa?->id;
        }

        return null;
    }
}
