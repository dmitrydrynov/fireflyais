<template>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Edit member</div>
        <div class="panel-body">
          <form v-on:submit.prevent="handleSave">
            <div v-if="errors.submit" class="alert alert-danger" role="alert">
              {{ errors.submit }}
            </div>

            <div
              class="form-group"
              v-bind:class="{ 'has-error': !!errors.email }"
            >
              <label for="email">Email address</label>
              <input
                type="email"
                class="form-control"
                name="email"
                placeholder="Email"
                :value="staticData.email"
                @change="clearErrors()"
                disabled
              />
              <p v-show="!!errors.email" class="help-block">
                {{ errors.email }}
              </p>
            </div>

            <div
              class="form-group"
              v-bind:class="{ 'has-error': !!errors.permissions }"
            >
              <label>Permissions</label>
              <p v-show="!!errors.permissions" class="help-block">
                {{ errors.permissions }}
              </p>
              <div v-for="permission in permissions" :key="permission.name">
                <div class="checkbox">
                  <label>
                    <input
                      type="checkbox"
                      :id="permission.name"
                      :value="permission.name"
                      :disabled="permission.disabled"
                      v-model="formData.permissions"
                      @change="handlePermissionsChange($event)"
                    />
                    {{ permission.title }}
                  </label>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.initialize();
  },
  data: () => {
    return {
      staticData: {
        email: null,
      },
      formData: {
        id: null,
        permissions: [],
      },
      errors: {
        permissions: null,
        submit: null,
      },
      permissions: [
        { name: "ro", title: "Read only", disabled: false },
        {
          name: "view_reports",
          title: "View reports",
          disabled: false,
        },
        { name: "full", title: "Full access", disabled: false },
        {
          name: "change_reps",
          title: "Manage reports",
          disabled: false,
        },
        {
          name: "change_rules",
          title: "Manage rules",
          disabled: false,
        },
        {
          name: "change_tx",
          title: "Manage transactions",
          disabled: false,
        },
        {
          name: "change_piggies",
          title: "Manage piggies",
          disabled: false,
        },
      ],
    };
  },
  methods: {
    initialize() {
      this.staticData.email = email;

      this.formData.id = memberId;
      this.formData.permissions = permissions;

      this.handlePermissionsChange();
    },
    handlePermissionsChange() {
      const { permissions } = this.formData;

      this.clearErrors();

      if (permissions.includes("full")) {
        this.permissions.map((i, idx) => {
          this.permissions[idx].disabled = i.name !== "full";
        });
        this.formData.permissions = ["full"];
      }

      if (permissions.includes("ro")) {
        this.permissions.map((i, idx) => {
          this.permissions[idx].disabled = i.name !== "ro";
        });
        this.formData.permissions = ["ro"];
      }

      if (this.formData.permissions.length === 0) {
        this.permissions.map((i, idx) => {
          this.permissions[idx].disabled = false;
        });
      }
    },
    async handleSave() {
      if (!this.validateFormData()) return;

      try {
        const response = await axios.post("/api/v1/members/update", {
          ...this.formData,
        });

        if (response.data.success) {
          window.location.href = previousUrl + "?message=updated";
        }
      } catch (error) {
        if (error.response.data.code === "23505") {
          this.errors.submit = "User with this email already exist.";
        } else {
          this.errors.submit = "Something wrong. Try again later.";
        }
      }
    },
    validateFormData() {
      if (this.formData.id && this.formData.permissions.length > 0) {
        return true;
      }

      this.clearErrors();

      if (!this.formData.permissions.length) {
        this.errors.permissions = "At least one option must be selected";
      }

      return false;
    },
    clearErrors() {
      this.errors = {
        permissions: null,
        submit: null,
      };
    },
  },
};
</script>
