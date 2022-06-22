export const can = {
  methods: {
    can(permissionName) {
      return window.App.permissions.indexOf(permissionName) !== -1;
    }
  }
}