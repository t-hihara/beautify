declare module "laravel-permission-to-vuejs" {
  import type { Plugin } from "vue";

  const LaravelPermissionToVueJS: Plugin;
  export default LaravelPermissionToVueJS;

  export function is(roleOrRoles: string): boolean;
  export function can(permissionOrPermissions: string): boolean;
  export function reloadRolesAndPermissions(route?: string): Promise<void>;
}
