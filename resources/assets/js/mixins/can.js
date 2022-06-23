export const can = {
  methods: {
    can(permissionName) {
      if (window.App.roles.indexOf('superadmin') !== -1 || window.App.roles.indexOf('owner') !== -1) {
        return true;
      }

      return window.App.permissions.indexOf(permissionName) !== -1;
    }
  }
}