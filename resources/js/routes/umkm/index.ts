import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/umkm/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UmkmController::exportMethod
* @see app/Http/Controllers/UmkmController.php:21
* @route '/umkm/export'
*/
exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportMethod.form = exportMethodForm

/**
* @see \App\Http\Controllers\UmkmController::destroy
* @see app/Http/Controllers/UmkmController.php:28
* @route '/umkm/{umkm}'
*/
export const destroy = (args: { umkm: string | number | { id: string | number } } | [umkm: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/umkm/{umkm}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\UmkmController::destroy
* @see app/Http/Controllers/UmkmController.php:28
* @route '/umkm/{umkm}'
*/
destroy.url = (args: { umkm: string | number | { id: string | number } } | [umkm: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { umkm: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { umkm: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            umkm: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        umkm: typeof args.umkm === 'object'
        ? args.umkm.id
        : args.umkm,
    }

    return destroy.definition.url
            .replace('{umkm}', parsedArgs.umkm.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UmkmController::destroy
* @see app/Http/Controllers/UmkmController.php:28
* @route '/umkm/{umkm}'
*/
destroy.delete = (args: { umkm: string | number | { id: string | number } } | [umkm: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\UmkmController::destroy
* @see app/Http/Controllers/UmkmController.php:28
* @route '/umkm/{umkm}'
*/
const destroyForm = (args: { umkm: string | number | { id: string | number } } | [umkm: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UmkmController::destroy
* @see app/Http/Controllers/UmkmController.php:28
* @route '/umkm/{umkm}'
*/
destroyForm.delete = (args: { umkm: string | number | { id: string | number } } | [umkm: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\UmkmController::restore
* @see app/Http/Controllers/UmkmController.php:35
* @route '/umkm/{umkm}/restore'
*/
export const restore = (args: { umkm: string | number } | [umkm: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restore.url(args, options),
    method: 'patch',
})

restore.definition = {
    methods: ["patch"],
    url: '/umkm/{umkm}/restore',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\UmkmController::restore
* @see app/Http/Controllers/UmkmController.php:35
* @route '/umkm/{umkm}/restore'
*/
restore.url = (args: { umkm: string | number } | [umkm: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { umkm: args }
    }

    if (Array.isArray(args)) {
        args = {
            umkm: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        umkm: args.umkm,
    }

    return restore.definition.url
            .replace('{umkm}', parsedArgs.umkm.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UmkmController::restore
* @see app/Http/Controllers/UmkmController.php:35
* @route '/umkm/{umkm}/restore'
*/
restore.patch = (args: { umkm: string | number } | [umkm: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: restore.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\UmkmController::restore
* @see app/Http/Controllers/UmkmController.php:35
* @route '/umkm/{umkm}/restore'
*/
const restoreForm = (args: { umkm: string | number } | [umkm: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: restore.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UmkmController::restore
* @see app/Http/Controllers/UmkmController.php:35
* @route '/umkm/{umkm}/restore'
*/
restoreForm.patch = (args: { umkm: string | number } | [umkm: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: restore.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

restore.form = restoreForm

const umkm = {
    export: Object.assign(exportMethod, exportMethod),
    destroy: Object.assign(destroy, destroy),
    restore: Object.assign(restore, restore),
}

export default umkm